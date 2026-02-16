<?php
require_once 'config.php';

if (!isMasterUser()) {
    redirect('dashboard.php');
}

require_once 'header.php';
?>

<h1 class="page-title">
    <i class="bi bi-grid-3x3-gap me-2"></i>Category Management
</h1>

<div id="message"></div>

<div class="glass-card">
    <div class="card-header-custom">
        <h5>Category List</h5>
        <button type="button" class="btn btn-gradient" id="add-category-btn">
            <i class="bi bi-plus-circle me-1"></i>Add Category
        </button>
    </div>
    
    <div class="table-container">
        <table class="table table-striped table-hover" id="category-table">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="category-form">
                <div class="modal-body">
                    <div id="form-message"></div>
                    <input type="hidden" id="category-id" name="id">
                    <input type="hidden" id="action" name="action" value="add">
                    
                    <div class="mb-3">
                        <label for="category-name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category-name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-gradient" id="submit-btn">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<script>
$(document).ready(function() {
    const table = $('#category-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'api/categories_api.php',
            type: 'POST',
            data: { action: 'fetch' }
        },
        columns: [
            { data: 'category_name' },
            { 
                data: 'category_status',
                render: function(data) {
                    return data === 'Enable' 
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }
            },
            { data: 'category_created_on' },
            { 
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    const statusBtn = row.category_status === 'Enable' 
                        ? `<button class="btn btn-warning btn-sm status-btn" data-id="${row.category_id}" data-status="${row.category_status}"><i class="bi bi-toggle-on"></i></button>`
                        : `<button class="btn btn-success btn-sm status-btn" data-id="${row.category_id}" data-status="${row.category_status}"><i class="bi bi-toggle-off"></i></button>`;
                    
                    return `
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${row.category_id}"><i class="bi bi-pencil"></i></button>
                        ${statusBtn}
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.category_id}"><i class="bi bi-trash"></i></button>
                    `;
                }
            }
        ]
    });

    // Add category
    $('#add-category-btn').click(function() {
        $('#category-form')[0].reset();
        $('#category-id').val('');
        $('#action').val('add');
        $('#modal-title').text('Add Category');
        $('#submit-btn').text('Add Category');
        $('#form-message').html('');
        $('#categoryModal').modal('show');
    });

    // Submit form
    $('#category-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'api/categories_api.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#submit-btn').prop('disabled', true).text('Processing...');
            },
            success: function(response) {
                $('#submit-btn').prop('disabled', false).text($('#action').val() === 'add' ? 'Add Category' : 'Update Category');
                if (response.success) {
                    $('#categoryModal').modal('hide');
                    showMessage('success', response.message);
                    table.ajax.reload();
                } else {
                    $('#form-message').html(`<div class="alert alert-danger">${response.message}</div>`);
                }
            }
        });
    });

    // Edit category
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'api/categories_api.php',
            method: 'POST',
            data: { action: 'fetch_single', id: id },
            dataType: 'json',
            success: function(data) {
                $('#category-id').val(data.category_id);
                $('#category-name').val(data.category_name);
                $('#action').val('edit');
                $('#modal-title').text('Edit Category');
                $('#submit-btn').text('Update Category');
                $('#form-message').html('');
                $('#categoryModal').modal('show');
            }
        });
    });

    // Change status
    $(document).on('click', '.status-btn', function() {
        const id = $(this).data('id');
        const status = $(this).data('status');
        const nextStatus = status === 'Enable' ? 'Disable' : 'Enable';
        
        if (confirm(`Are you sure you want to ${nextStatus.toLowerCase()} this category?`)) {
            $.ajax({
                url: 'api/categories_api.php',
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

    // Delete category
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: 'api/categories_api.php',
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
