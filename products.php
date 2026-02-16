<?php
require_once 'config.php';

if (!isMasterUser()) {
    redirect('dashboard.php');
}

require_once 'header.php';

// Get categories for dropdown
$stmt = $pdo->query("SELECT category_name FROM product_category_table WHERE category_status = 'Enable' ORDER BY category_name ASC");
$categories = $stmt->fetchAll();
?>

<h1 class="page-title">
    <i class="bi bi-box-seam me-2"></i>Product Management
</h1>

<div id="message"></div>

<div class="glass-card">
    <div class="card-header-custom">
        <h5>Product List</h5>
        <button type="button" class="btn btn-gradient" id="add-product-btn">
            <i class="bi bi-plus-circle me-1"></i>Add Product
        </button>
    </div>
    
    <div class="table-container">
        <table class="table table-striped table-hover" id="product-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="product-form">
                <div class="modal-body">
                    <div id="form-message"></div>
                    <input type="hidden" id="product-id" name="id">
                    <input type="hidden" id="action" name="action" value="add">
                    
                    <div class="mb-3">
                        <label for="category-name" class="form-label">Category</label>
                        <select class="form-select" id="category-name" name="category" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['category_name']) ?>">
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="product-name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product-name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="product-price" class="form-label">Price ($)</label>
                        <input type="number" class="form-control" id="product-price" name="price" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-gradient" id="submit-btn">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<script>
$(document).ready(function() {
    const table = $('#product-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'api/products_api.php',
            type: 'POST',
            data: { action: 'fetch' }
        },
        columns: [
            { data: 'product_name' },
            { data: 'category_name' },
            { 
                data: 'product_price',
                render: function(data) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            { 
                data: 'product_status',
                render: function(data) {
                    return data === 'Enable' 
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }
            },
            { 
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    const statusBtn = row.product_status === 'Enable' 
                        ? `<button class="btn btn-warning btn-sm status-btn" data-id="${row.product_id}" data-status="${row.product_status}"><i class="bi bi-toggle-on"></i></button>`
                        : `<button class="btn btn-success btn-sm status-btn" data-id="${row.product_id}" data-status="${row.product_status}"><i class="bi bi-toggle-off"></i></button>`;
                    
                    return `
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${row.product_id}"><i class="bi bi-pencil"></i></button>
                        ${statusBtn}
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.product_id}"><i class="bi bi-trash"></i></button>
                    `;
                }
            }
        ]
    });

    $('#add-product-btn').click(function() {
        $('#product-form')[0].reset();
        $('#product-id').val('');
        $('#action').val('add');
        $('#modal-title').text('Add Product');
        $('#submit-btn').text('Add Product');
        $('#form-message').html('');
        $('#productModal').modal('show');
    });

    $('#product-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'api/products_api.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#submit-btn').prop('disabled', true).text('Processing...');
            },
            success: function(response) {
                $('#submit-btn').prop('disabled', false).text($('#action').val() === 'add' ? 'Add Product' : 'Update Product');
                if (response.success) {
                    $('#productModal').modal('hide');
                    showMessage('success', response.message);
                    table.ajax.reload();
                } else {
                    $('#form-message').html(`<div class="alert alert-danger">${response.message}</div>`);
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'api/products_api.php',
            method: 'POST',
            data: { action: 'fetch_single', id: id },
            dataType: 'json',
            success: function(data) {
                $('#product-id').val(data.product_id);
                $('#category-name').val(data.category_name);
                $('#product-name').val(data.product_name);
                $('#product-price').val(data.product_price);
                $('#action').val('edit');
                $('#modal-title').text('Edit Product');
                $('#submit-btn').text('Update Product');
                $('#form-message').html('');
                $('#productModal').modal('show');
            }
        });
    });

    $(document).on('click', '.status-btn', function() {
        const id = $(this).data('id');
        const status = $(this).data('status');
        const nextStatus = status === 'Enable' ? 'Disable' : 'Enable';
        
        if (confirm(`Are you sure you want to ${nextStatus.toLowerCase()} this product?`)) {
            $.ajax({
                url: 'api/products_api.php',
                method: 'POST',
                data: { action: 'change_status', id: id, status: status, next_status: nextStatus },
                dataType: 'json',
                success: function(response) {
                    showMessage(response.success ? 'success' : 'danger', response.message);
                    table.ajax.reload();
                }
            });
        }
    });

    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: 'api/products_api.php',
                method: 'POST',
                data: { action: 'delete', id: id },
                dataType: 'json',
                success: function(response) {
                    showMessage(response.success ? 'success' : 'danger', response.message);
                    table.ajax.reload();
                }
            });
        }
    });

    function showMessage(type, message) {
        $('#message').html(`<div class="alert alert-${type} alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`);
        setTimeout(() => $('#message').html(''), 5000);
    }
});
</script>

</body>
</html>
