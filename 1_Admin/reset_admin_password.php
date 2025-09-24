<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'superadmin') {
    header("Location: signin_admin.php");
    exit();
}

$admin_id = $_GET['admin_id'] ?? null;
$admin_email = '';

if ($admin_id) {
    $stmt = $mysqli->prepare("SELECT email FROM admins WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->bind_result($admin_email);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("UPDATE admins SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $admin_id);

        if ($stmt->execute()) {
            $success = "Password reset successfully.";
        } else {
            $error = "An error occurred. Please try again.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_settings.css">
    <title>Admin Account Password</title>
</head>
<body>
<?php include 'header.php'; ?>
<a href="manage_admins.php"><img src="../3_image/back.png" width="30" alt="Back"/></a>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2 class="text-center">Reset Admin Account Password</h2>
        </div>
        <div class="card-body">
            <p>Email: <?= htmlspecialchars($admin_email) ?></p>
            <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
            <form action="reset_admin_password.php?admin_id=<?= htmlspecialchars($admin_id) ?>" method="POST">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-success btn-block">Reset Password</button>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

