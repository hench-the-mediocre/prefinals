<?php
require_once 'config.php';

if (!isWaiterUser() && !isMasterUser()) {
    redirect('dashboard.php');
}

require_once 'header.php';
?>

<h1 class="page-title">
    <i class="bi bi-cart-check me-2"></i>Order Management
</h1>

<div class="row">
    <div class="col-md-4">
        <div class="glass-card">
            <div class="card-header-custom">
                <h5><i class="bi bi-table me-2"></i>Table Status</h5>
            </div>
            <div id="table-status"></div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="glass-card">
            <div class="card-header-custom">
                <h5><i class="bi bi-receipt me-2"></i>Order Details</h5>
            </div>
            <div id="order-details"></div>
        </div>
    </div>
</div>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Item to Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="order-form">
                <div class="modal-body">
                    <input type="hidden" id="table-id" name="table_id">
                    <input type="hidden" id="order-id" name="order_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" id="category-select" required>
                            <option value="">Select Category</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <select class="form-select" id="product-select" name="product_id" required>
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <select class="form-select" name="quantity" required>
                            <?php for($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-gradient">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<script>
$(document).ready(function() {
    loadTables();
    
    setInterval(loadTables, 10000);
    
    function loadTables() {
        $.ajax({
            url: 'api/orders_api.php',
            method: 'POST',
            data: { action: 'load_tables' },
            success: function(data) {
                $('#table-status').html(data);
            }
        });
    }
    
    $(document).on('click', '.table-btn', function() {
        const tableId = $(this).data('table-id');
        const tableName = $(this).data('table-name');
        const orderId = $(this).data('order-id');
        
        $('#table-id').val(tableId);
        $('#order-id').val(orderId);
        
        loadOrderDetails(orderId);
        loadCategories();
        
        $('#orderModal').modal('show');
    });
    
    function loadCategories() {
        $.ajax({
            url: 'api/orders_api.php',
            method: 'POST',
            data: { action: 'load_categories' },
            success: function(data) {
                $('#category-select').html('<option value="">Select Category</option>' + data);
            }
        });
    }
    
    $('#category-select').change(function() {
        const category = $(this).val();
        if (category) {
            $.ajax({
                url: 'api/orders_api.php',
                method: 'POST',
                data: { action: 'load_products', category: category },
                success: function(data) {
                    $('#product-select').html(data);
                }
            });
        }
    });
    
    $('#order-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'api/orders_api.php',
            method: 'POST',
            data: $(this).serialize() + '&action=add_item',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#orderModal').modal('hide');
                    loadOrderDetails(response.order_id);
                    loadTables();
                } else {
                    alert(response.message);
                }
            }
        });
    });
    
    function loadOrderDetails(orderId) {
        if (!orderId) {
            $('#order-details').html('<div class="text-center text-white py-5"><i class="bi bi-cart-x display-1 mb-3"></i><p>No active order</p></div>');
            return;
        }
        
        $.ajax({
            url: 'api/orders_api.php',
            method: 'POST',
            data: { action: 'load_order_details', order_id: orderId },
            success: function(data) {
                $('#order-details').html(data);
            }
        });
    }
    
    $(document).on('change', '.quantity-select', function() {
        const itemId = $(this).data('item-id');
        const orderId = $(this).data('order-id');
        const quantity = $(this).val();
        
        $.ajax({
            url: 'api/orders_api.php',
            method: 'POST',
            data: { action: 'update_quantity', item_id: itemId, order_id: orderId, quantity: quantity },
            success: function() {
                loadOrderDetails(orderId);
            }
        });
    });
    
    $(document).on('click', '.remove-item-btn', function() {
        if (confirm('Remove this item?')) {
            const itemId = $(this).data('item-id');
            const orderId = $(this).data('order-id');
            
            $.ajax({
                url: 'api/orders_api.php',
                method: 'POST',
                data: { action: 'remove_item', item_id: itemId, order_id: orderId },
                success: function() {
                    loadOrderDetails(orderId);
                    loadTables();
                }
            });
        }
    });
});
</script>

</body>
</html>
