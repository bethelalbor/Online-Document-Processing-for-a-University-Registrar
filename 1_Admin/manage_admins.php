<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'superadmin') {
    header("Location: signin_admin.php");
    exit();
}

$admins = [];
$stmt = $mysqli->prepare("SELECT id, email, role FROM admins WHERE email != 'superadmin@ptc.com'");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $admins[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <title>Manage Admins</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(adminId) {
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
                    document.getElementById('delete_admin_id').value = adminId;
                    document.getElementById('delete_form').submit();
                }
            });
        }
    </script>
</head>
<body>
    <?php include 'header.php'; ?>
    <a href="admin_settings.php"><img src="../3_image/back.png" width= "30" alt="Back"/> </a>
    <div class="text-right">
                <a href="new_admin.php" class="btn btn-primary">Create New Admin</a>
            </div>
    <div class="container mt-5">
        <h2 class="text-center">Manage Admin Accounts</h2>
            <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?= htmlspecialchars($admin['email']) ?></td>
                            <td><?= htmlspecialchars($admin['role']) ?></td>
                            <td>
                                <a href="reset_admin_password.php?admin_id=<?= htmlspecialchars($admin['id']) ?>" class="btn btn-warning btn-sm">Forgot Password</a>
                                <button type="button" onclick="confirmDelete(<?= htmlspecialchars($admin['id']) ?>)" class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <form id="delete_form" action="manage_admins.php" method="POST" style="display:none;">
            <input type="hidden" id="delete_admin_id" name="delete_admin_id">
        </form>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_admin_id'])) {
    $delete_admin_id = $_POST['delete_admin_id'];

    $stmt = $mysqli->prepare("DELETE FROM admins WHERE id = ?");
    $stmt->bind_param("i", $delete_admin_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_admins.php");
    exit();
}
?>
