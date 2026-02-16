<?php
require_once 'config.php';

if (!isMasterUser()) {
    redirect('dashboard.php');
}

require_once 'header.php';
?>

<h1 class="page-title">
    <i class="bi bi-table me-2"></i>Table Management
</h1>

<div id="message"></div>

<div class="glass-card">
    <div class="card-header-custom">
        <h5>Table List</h5>
        <button type="button" class="btn btn-gradient" id="add-table-btn">
            <i class="bi bi-plus-circle me-1"></i>Add Table
        </button>
    </div>
    
    <div class="table-container">
        <table class="table table-striped table-hover" id="table-data">
            <thead>
                <tr>
                    <th>Table Name</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Table Modal -->
<div class="modal fade" id="tableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add Table</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="table-form">
                <div class="modal-body">
                    <div id="form-message"></div>
                    <input type="hidden" id="table-id" name="id">
                    <input type="hidden" id="action" name="action" value="add">
                    
                    <div class="mb-3">
                        <label for="table-name" class="form-label">Table Name</label>
                        <input type="text" class="form-control" id="table-name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="table-capacity" class="form-label">Capacity (Persons)</label>
                        <select class="form-select" id="table-capacity" name="capacity" required>
                            <option value="">Select Capacity</option>
                            <?php for($i = 1; $i <= 20; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?> Person<?= $i > 1 ? 's' : '' ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-gradient" id="submit-btn">Add Table</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<script>
$(document).ready(function() {
    const table = $('#table-data').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'api/tables_api.php',
            type: 'POST',
            data: { action: 'fetch' }
        },
        columns: [
            { data: 'table_name' },
            { 
                data: 'table_capacity',
                render: function(data) {
                    return data + ' Person' + (data > 1 ? 's' : '');
                }
            },
            { 
                data: 'table_status',
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
                    const statusBtn = row.table_status === 'Enable' 
                        ? `<button class="btn btn-warning btn-sm status-btn" data-id="${row.table_id}" data-status="${row.table_status}"><i class="bi bi-toggle-on"></i></button>`
                        : `<button class="btn btn-success btn-sm status-btn" data-id="${row.table_id}" data-status="${row.table_status}"><i class="bi bi-toggle-off"></i></button>`;
                    
                    return `
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${row.table_id}"><i class="bi bi-pencil"></i></button>
                        ${statusBtn}
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.table_id}"><i class="bi bi-trash"></i></button>
                    `;
                }
            }
        ]
    });

    $('#add-table-btn').click(function() {
        $('#table-form')[0].reset();
        $('#table-id').val('');
        $('#action').val('add');
        $('#modal-title').text('Add Table');
        $('#submit-btn').text('Add Table');
        $('#form-message').html('');
        $('#tableModal').modal('show');
    });

    $('#table-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'api/tables_api.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#submit-btn').prop('disabled', true).text('Processing...');
            },
            success: function(response) {
                $('#submit-btn').prop('disabled', false).text($('#action').val() === 'add' ? 'Add Table' : 'Update Table');
                if (response.success) {
                    $('#tableModal').modal('hide');
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
            url: 'api/tables_api.php',
            method: 'POST',
            data: { action: 'fetch_single', id: id },
            dataType: 'json',
            success: function(data) {
                $('#table-id').val(data.table_id);
                $('#table-name').val(data.table_name);
                $('#table-capacity').val(data.table_capacity);
                $('#action').val('edit');
                $('#modal-title').text('Edit Table');
                $('#submit-btn').text('Update Table');
                $('#form-message').html('');
                $('#tableModal').modal('show');
            }
        });
    });

    $(document).on('click', '.status-btn', function() {
        const id = $(this).data('id');
        const status = $(this).data('status');
        const nextStatus = status === 'Enable' ? 'Disable' : 'Enable';
        
        if (confirm(`Are you sure you want to ${nextStatus.toLowerCase()} this table?`)) {
            $.ajax({
                url: 'api/tables_api.php',
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
        if (confirm('Are you sure you want to delete this table?')) {
            $.ajax({
                url: 'api/tables_api.php',
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
