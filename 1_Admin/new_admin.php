<?php

session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

$email_admin = "";
$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['admin_role'] !== 'superadmin') {
        $error = "You do not have permission to perform this action.";
    } else {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = "Please fill in all fields.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                $success = "Admin added successfully.";
            } else {
                $error = "An error occurred. Please try again.";
            }

            $stmt->close();
        }
    }
}

$admin_id = $_SESSION['admin_id'];

$stmt = $mysqli->prepare("SELECT email FROM admins WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($email_admin);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="../3_image/PTC-Logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>New Admin</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <a href="manage_admins.php"><img src="../3_image/back.png" width= "30" alt="Back"/> </a>
    <h2 class="text-center">Create Admin Account</h2>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                    <form action="admin_settings.php" method="POST" class="mt-4">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Add Admin</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
