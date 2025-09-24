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

            $stmt = $mysqli->prepare("INSERT INTO admins (email, password, role) VALUES (?, ?, 'admin')");
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
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <link rel="stylesheet" href="admin_settings.css">
    <title>Admin Settings</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>
    <h2 class="text-center">Admin Settings</h2>
    <div class="container">
        <div class="box">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success" role="alert"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <p>Email: <?= htmlspecialchars($email_admin) ?></p>
            <div class="text-center">
                <button class="btn btn-primary" onclick="location.href='change_password_admin.php'">Change Password</button> <br> <br>
                <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                    <button class="btn btn-secondary" onclick="location.href='manage_admins.php'">Manage Admins</button> <br> <br>
                    <button class="btn btn-secondary" onclick="location.href='modify_request_form.php'">Modify Request Form</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
