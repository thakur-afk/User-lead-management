<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">User Management</h2>

    <button class="btn btn-success mb-3" id="addUserBtn">Add User</button>

    <table id="usersTable" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Created</th><th>Updated</th><th>Actions</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="userForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">User Form</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="userId">
          <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
          <div id="formErrors" class="text-danger"></div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- JS scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
let userTable;

$(document).ready(function() {
    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    console.log("Page Loaded");  // Check if script runs

    userTable = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= site_url('user/fetch_users') ?>",
            type: "POST"
        }
    });

    // Add User
    $('#addUserBtn').click(function() {
      console.log("Add User button clicked"); 
        $('#userForm')[0].reset();
        $('#userId').val('');
        $('#formErrors').html('');
        userModal.show();
    });

    // Submit form
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#userId').val();
        const url = id ? "<?= site_url('user/update/') ?>" + id : "<?= site_url('user/create') ?>";

        $.ajax({
            url: url,
            type: "POST",
            data: $('#userForm').serialize(),
            dataType: "json",
            success: function(res) {
                if (res.error) {
                    $('#formErrors').html(res.error);
                } else {
                    userModal.hide();
                    userTable.ajax.reload();
                }
            }
        });
    });

    // Edit user
    $('#usersTable').on('click', '.edit', function() {
        const id = $(this).data('id');
        $.get("<?= site_url('user/edit/') ?>" + id, function(data) {
            const user = JSON.parse(data);
            $('#userId').val(user.id);
            $('[name="name"]').val(user.name);
            $('[name="email"]').val(user.email);
            $('[name="status"]').val(user.status);
            $('#formErrors').html('');
            userModal.show();
        });
    });

    // Delete user
    $('#usersTable').on('click', '.delete', function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this user?')) {
            $.get("<?= site_url('user/delete/') ?>" + id, function(res) {
                userTable.ajax.reload();
            });
        }
    });
});
</script>

</body>
</html>
