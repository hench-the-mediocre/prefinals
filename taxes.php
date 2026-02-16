<?php
require_once 'config.php';

if (!isMasterUser()) {
    redirect('dashboard.php');
}

require_once 'header.php';
?>

<h1 class="page-title">
    <i class="bi bi-percent me-2"></i>Tax Management
</h1>

<div id="message"></div>

<div class="glass-card">
    <div class="card-header-custom">
        <h5>Tax List</h5>
        <button type="button" class="btn btn-gradient" id="add-tax-btn">
            <i class="bi bi-plus-circle me-1"></i>Add Tax
        </button>
    </div>
    
    <div class="table-container">
        <table class="table table-striped table-hover" id="tax-table">
            <thead>
                <tr>
                    <th>Tax Name</th>
                    <th>Percentage (%)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Tax Modal -->
<div class="modal fade" id="taxModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add Tax</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="tax-form">
                <div class="modal-body">
                    <div id="form-message"></div>
                    <input type="hidden" id="tax-id" name="id">
                    <input type="hidden" id="action" name="action" value="add">
                    
                    <div class="mb-3">
                        <label for="tax-name" class="form-label">Tax Name</label>
                        <input type="text" class="form-control" id="tax-name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tax-percentage" class="form-label">Percentage (%)</label>
                        <input type="number" class="form-control" id="tax-percentage" name="percentage" step="0.01" min="0" max="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-gradient" id="submit-btn">Add Tax</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<script>
$(document).ready(function() {
    const table = $('#tax-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'api/taxes_api.php',
            type: 'POST',
            data: { action: 'fetch' }
        },
        columns: [
            { data: 'tax_name' },
            { 
                data: 'tax_percentage',
                render: function(data) {
                    return parseFloat(data).toFixed(2) + '%';
                }
            },
            { 
                data: 'tax_status',
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
                    const statusBtn = row.tax_status === 'Enable' 
                        ? `<button class="btn btn-warning btn-sm status-btn" data-id="${row.tax_id}" data-status="${row.tax_status}"><i class="bi bi-toggle-on"></i></button>`
                        : `<button class="btn btn-success btn-sm status-btn" data-id="${row.tax_id}" data-status="${row.tax_status}"><i class="bi bi-toggle-off"></i></button>`;
                    
                    return `
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${row.tax_id}"><i class="bi bi-pencil"></i></button>
                        ${statusBtn}
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.tax_id}"><i class="bi bi-trash"></i></button>
                    `;
                }
            }
        ]
    });

    $('#add-tax-btn').click(function() {
        $('#tax-form')[0].reset();
        $('#tax-id').val('');
        $('#action').val('add');
        $('#modal-title').text('Add Tax');
        $('#submit-btn').text('Add Tax');
        $('#form-message').html('');
        $('#taxModal').modal('show');
    });

    $('#tax-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'api/taxes_api.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#submit-btn').prop('disabled', true).text('Processing...');
            },
            success: function(response) {
                $('#submit-btn').prop('disabled', false).text($('#action').val() === 'add' ? 'Add Tax' : 'Update Tax');
                if (response.success) {
                    $('#taxModal').modal('hide');
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
            url: 'api/taxes_api.php',
            method: 'POST',
            data: { action: 'fetch_single', id: id },
            dataType: 'json',
            success: function(data) {
                $('#tax-id').val(data.tax_id);
                $('#tax-name').val(data.tax_name);
                $('#tax-percentage').val(data.tax_percentage);
                $('#action').val('edit');
                $('#modal-title').text('Edit Tax');
                $('#submit-btn').text('Update Tax');
                $('#form-message').html('');
                $('#taxModal').modal('show');
            }
        });
    });

    $(document).on('click', '.status-btn', function() {
        const id = $(this).data('id');
        const status = $(this).data('status');
        const nextStatus = status === 'Enable' ? 'Disable' : 'Enable';
        
        if (confirm(`Are you sure you want to ${nextStatus.toLowerCase()} this tax?`)) {
            $.ajax({
                url: 'api/taxes_api.php',
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
        if (confirm('Are you sure you want to delete this tax?')) {
            $.ajax({
                url: 'api/taxes_api.php',
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
