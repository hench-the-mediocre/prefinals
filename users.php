<?php
require_once 'config.php';

if (!isMasterUser()) {
    redirect('dashboard.php');
}

require_once 'header.php';
?>

<h1 class="page-title">
    <i class="bi bi-people me-2"></i>User Management
</h1>

<div id="message"></div>

<div class="glass-card">
    <div class="card-header-custom">
        <h5>User List</h5>
        <button type="button" class="btn btn-gradient" id="add-user-btn">
            <i class="bi bi-plus-circle me-1"></i>Add User
        </button>
    </div>
    
    <div class="table-container">
        <table class="table table-striped table-hover" id="user-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="user-form">
                <div class="modal-body">
                    <div id="form-message"></div>
                    <input type="hidden" id="user-id" name="id">
                    <input type="hidden" id="action" name="action" value="add">
                    
                    <div class="mb-3">
                        <label for="user-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="user-name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="user-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="user-email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="user-password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="user-password" name="password">
                        <small class="text-muted">Leave blank to keep current password (when editing)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="user-type" class="form-label">User Type</label>
                        <select class="form-select" id="user-type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="Waiter">Waiter</option>
                            <option value="Cashier">Cashier</option>
                            <option value="Master">Master</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-gradient" id="submit-btn">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<script>
$(document).ready(function() {
    const table = $('#user-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'api/users_api.php',
            type: 'POST',
            data: { action: 'fetch' }
        },
        columns: [
            { data: 'user_name' },
            { data: 'user_email' },
            { 
                data: 'user_type',
                render: function(data) {
                    const colors = {
                        'Master': 'danger',
                        'Waiter': 'primary',
                        'Cashier': 'success'
                    };
                    return `<span class="badge bg-${colors[data]}">${data}</span>`;
                }
            },
            { 
                data: 'user_status',
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
                    const statusBtn = row.user_status === 'Enable' 
                        ? `<button class="btn btn-warning btn-sm status-btn" data-id="${row.user_id}" data-status="${row.user_status}"><i class="bi bi-toggle-on"></i></button>`
                        : `<button class="btn btn-success btn-sm status-btn" data-id="${row.user_id}" data-status="${row.user_status}"><i class="bi bi-toggle-off"></i></button>`;
                    
                    return `
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${row.user_id}"><i class="bi bi-pencil"></i></button>
                        ${statusBtn}
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.user_id}"><i class="bi bi-trash"></i></button>
                    `;
                }
            }
        ]
    });

    $('#add-user-btn').click(function() {
        $('#user-form')[0].reset();
        $('#user-id').val('');
        $('#action').val('add');
        $('#modal-title').text('Add User');
        $('#submit-btn').text('Add User');
        $('#form-message').html('');
        $('#user-password').prop('required', true);
        $('#userModal').modal('show');
    });

    $('#user-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'api/users_api.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#submit-btn').prop('disabled', true).text('Processing...');
            },
            success: function(response) {
                $('#submit-btn').prop('disabled', false).text($('#action').val() === 'add' ? 'Add User' : 'Update User');
                if (response.success) {
                    $('#userModal').modal('hide');
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
            url: 'api/users_api.php',
            method: 'POST',
            data: { action: 'fetch_single', id: id },
            dataType: 'json',
            success: function(data) {
                $('#user-id').val(data.user_id);
                $('#user-name').val(data.user_name);
                $('#user-email').val(data.user_email);
                $('#user-type').val(data.user_type);
                $('#user-password').val('').prop('required', false);
                $('#action').val('edit');
                $('#modal-title').text('Edit User');
                $('#submit-btn').text('Update User');
                $('#form-message').html('');
                $('#userModal').modal('show');
            }
        });
    });

    $(document).on('click', '.status-btn', function() {
        const id = $(this).data('id');
        const status = $(this).data('status');
        const nextStatus = status === 'Enable' ? 'Disable' : 'Enable';
        
        if (confirm(`Are you sure you want to ${nextStatus.toLowerCase()} this user?`)) {
            $.ajax({
                url: 'api/users_api.php',
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
        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: 'api/users_api.php',
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
