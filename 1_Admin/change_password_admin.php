<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: signin_admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_SESSION['admin_id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    
    if ($new_password !== $confirm_new_password) {
        $error = "New passwords do not match!";
    } else {
        
        $stmt = $mysqli->prepare("SELECT password FROM admins WHERE id = ?");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($current_password_hash);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($old_password, $current_password_hash)) {
            $error = "Old password is incorrect!";
        } else {
            $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);

            $stmt = $mysqli->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_password_hash, $admin_id);

            if ($stmt->execute()) {
                $success = "Password changed successfully!";
            } else {
                $error = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
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
    <style>
        .card-container {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
    <title>Change Password</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <a href="admin_settings.php"><img src="../3_image/back.png" width= "30" alt="Back"/> </a>
    <div class="container mt-5 card-container">
        <div class="card">
            <div class="card-header text-black">
                <h2 class="text-center">Change Password</h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
                <form action="change_password_admin.php" method="POST">
                    <div class="form-group">
                        <label for="old_password">Old Password</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" required placeholder="Old Password">
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_new_password">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required placeholder="Confirm New Password">
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Change Password</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>