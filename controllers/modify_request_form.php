<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'superadmin') {
    header("Location: signin_admin.php");
    exit();
}


$document_types = [];
$stmt = $mysqli->prepare("SELECT id, type FROM document_types");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $document_types[] = $row;
}
$stmt->close();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['new_document_type'])) {
        $new_document_type = $_POST['new_document_type'] ?? '';

        if (!empty($new_document_type)) {
            $stmt = $mysqli->prepare("INSERT INTO document_types (type) VALUES (?)");
            $stmt->bind_param("s", $new_document_type);
            $stmt->execute();
            $stmt->close();

            $success = "Document type added successfully.";
        } else {
            $error = "Document type cannot be empty.";
        }
    } elseif (isset($_POST['delete_document_type_id'])) {
        $delete_document_type_id = $_POST['delete_document_type_id'];

        $stmt = $mysqli->prepare("DELETE FROM document_types WHERE id = ?");
        $stmt->bind_param("i", $delete_document_type_id);
        $stmt->execute();
        $stmt->close();

        $success = "Document type deleted successfully.";
    } elseif (isset($_POST['edit_document_type'])) {
        $edit_document_type_id = $_POST['edit_document_type_id'];
        $edit_document_type = $_POST['edit_document_type'];

        $stmt = $mysqli->prepare("UPDATE document_types SET type = ? WHERE id = ?");
        $stmt->bind_param("si", $edit_document_type, $edit_document_type_id);
        $stmt->execute();
        $stmt->close();

        $success = "Document type updated successfully.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <title>Modify Request Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirm_changes(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Save the changes to document types?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#008000',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("modify_request_form").submit();
                }
            });
        }

        function editType(typeId, typeName) {
            Swal.fire({
                title: 'Edit Document Type',
                input: 'text',
                inputValue: typeName,
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Document type cannot be empty!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('edit_document_type_id').value = typeId;
                    document.getElementById('edit_document_type').value = result.value;
                    document.getElementById('edit_form').submit();
                }
            });
        }

        function confirmDelete(typeId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete_document_type_id').value = typeId;
                    document.getElementById('delete_form').submit();
                }
            });
        }
    </script>

</head>
<body style="background-image: url('../../ODR_FINAL_WEB/3_image/mybg.png');">
    <?php include 'header.php'; ?>
    <a href="admin_settings.php"><img src="../3_image/back.png" width= "30" alt="Back"/> </a>
    <div class="container mt-5">
        <h2 class="text-center">Modify Request Form</h2>
        <div class="card">
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                <form id="modify_request_form" action="modify_request_form.php" method="POST" onsubmit="confirm_changes(event)" class="mb-4">
                <label for="new_document_type">Add New Document Type:</label>
                <div class="input-group mb-3">
                        <input type="text" id="new_document_type" name="new_document_type" class="form-control" required>
                        <button type="submit" class="btn btn-success">Add Document Type</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Existing Document Types</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($document_types as $type): ?>
                                <tr>
                                    <td><?= htmlspecialchars($type['type']) ?></td>
                                    <td>
                                        <button type="button" onclick="editType(<?= htmlspecialchars($type['id']) ?>, '<?= htmlspecialchars($type['type']) ?>')" class="btn btn-warning btn-sm">Edit</button>
                                        <button type="button" onclick="confirmDelete(<?= htmlspecialchars($type['id']) ?>)" class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <form id="edit_form" action="modify_request_form.php" method="POST" style="display:none;">
                    <input type="hidden" id="edit_document_type_id" name="edit_document_type_id">
                    <input type="hidden" id="edit_document_type" name="edit_document_type">
                </form>
                <form id="delete_form" action="modify_request_form.php" method="POST" style="display:none;">
                    <input type="hidden" id="delete_document_type_id" name="delete_document_type_id">
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
