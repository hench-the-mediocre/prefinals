<?php
require_once 'config.php';

if (!isCashierUser() && !isMasterUser()) {
    redirect('dashboard.php');
}

require_once 'header.php';
?>

<h1 class="page-title">
    <i class="bi bi-receipt me-2"></i>Billing Management
</h1>

<div id="message"></div>

<div class="glass-card">
    <div class="card-header-custom">
        <h5>Pending Bills</h5>
    </div>
    
    <div class="table-container">
        <table class="table table-striped table-hover" id="billing-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Table</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Waiter</th>
                    <?php if (isMasterUser()): ?>
                    <th>Cashier</th>
                    <?php endif; ?>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Bill Modal -->
<div class="modal fade" id="billModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bill Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bill-details"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-gradient" id="complete-bill-btn">Complete & Print</button>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<script>
$(document).ready(function() {
    let currentOrderId = 0;
    
    const table = $('#billing-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'api/billing_api.php',
            type: 'POST',
            data: { action: 'fetch' }
        },
        columns: [
            { data: 'order_number' },
            { data: 'table_name' },
            { data: 'order_date' },
            { data: 'order_time' },
            { data: 'waiter_name' },
            <?php if (isMasterUser()): ?>
            { 
                data: 'cashier_name',
                render: function(data) {
                    return data || '-';
                }
            },
            <?php endif; ?>
            { 
                data: 'order_net_amount',
                render: function(data) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            { 
                data: 'order_status',
                render: function(data) {
                    const colors = {
                        'Pending': 'warning',
                        'Completed': 'success',
                        'Cancelled': 'danger'
                    };
                    return `<span class="badge bg-${colors[data]}">${data}</span>`;
                }
            },
            { 
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm view-btn" data-id="${row.order_id}"><i class="bi bi-eye"></i> View</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.order_id}"><i class="bi bi-trash"></i></button>
                    `;
                }
            }
        ]
    });

    $(document).on('click', '.view-btn', function() {
        currentOrderId = $(this).data('id');
        loadBillDetails(currentOrderId);
        $('#billModal').modal('show');
    });
    
    function loadBillDetails(orderId) {
        $.ajax({
            url: 'api/billing_api.php',
            method: 'POST',
            data: { action: 'fetch_single', order_id: orderId },
            success: function(data) {
                $('#bill-details').html(data);
            }
        });
    }
    
    $('#complete-bill-btn').click(function() {
        if (confirm('Complete this bill and mark as paid?')) {
            $.ajax({
                url: 'api/billing_api.php',
                method: 'POST',
                data: { action: 'complete', order_id: currentOrderId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#billModal').modal('hide');
                        showMessage('success', response.message);
                        table.ajax.reload();
                        
                        // Open print window
                        window.open('print_bill.php?order_id=' + currentOrderId, '_blank');
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });
    
    $(document).on('click', '.delete-btn', function() {
        const orderId = $(this).data('id');
        if (confirm('Are you sure you want to delete this order?')) {
            $.ajax({
                url: 'api/billing_api.php',
                method: 'POST',
                data: { action: 'delete', order_id: orderId },
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
