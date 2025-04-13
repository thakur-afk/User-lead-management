<!DOCTYPE html>
<html>
<head>
    <title>Lead Management</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Lead Management</h2>
    <button class="btn btn-success mb-3" id="addLeadBtn">Add Lead</button>

    <table id="leadsTable" class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Project ID</th>
            <th>Status</th>
            <th>Assigned To</th>
            <th>Assigned At</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
        </thead>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="leadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="leadForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lead Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="leadId">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Project ID</label>
                        <input type="number" name="project_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="New">New</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Closed">Closed</option>
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
let leadTable;

$(document).ready(function () {
    const leadModal = new bootstrap.Modal(document.getElementById('leadModal'));

    leadTable = $('#leadsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url('leads/fetch_leads') ?>",
            type: "POST"
        }
    });

    $('#addLeadBtn').click(function () {
        $('#leadForm')[0].reset();
        $('#leadId').val('');
        $('#formErrors').html('');
        leadModal.show();
    });

    $('#leadForm').submit(function (e) {
        e.preventDefault();
        const id = $('#leadId').val();
        const url = id ? "<?= base_url('leads/update/') ?>" + id : "<?= base_url('leads/create') ?>";

        $.ajax({
            url: url,
            type: "POST",
            data: $('#leadForm').serialize(),
            dataType: "json",
            success: function (res) {
                if (res.error) {
                    $('#formErrors').html(res.error);
                } else {
                    leadModal.hide();
                    leadTable.ajax.reload();
                }
            }
        });
    });

    $('#leadsTable').on('click', '.edit', function () {
        const id = $(this).data('id');
        $.get("<?= base_url('leads/edit/') ?>" + id, function (data) {
            const lead = JSON.parse(data);
            $('#leadId').val(lead.id);
            $('[name="name"]').val(lead.name);
            $('[name="email"]').val(lead.email);
            $('[name="phone"]').val(lead.phone);
            $('[name="project_id"]').val(lead.project_id);
            $('[name="status"]').val(lead.status);
            $('#formErrors').html('');
            leadModal.show();
        });
    });

    $('#leadsTable').on('click', '.delete', function () {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this lead?')) {
            $.get("<?= base_url('leads/delete/') ?>" + id, function () {
                leadTable.ajax.reload();
            });
        }
    });
});
</script>

</body>
</html>
