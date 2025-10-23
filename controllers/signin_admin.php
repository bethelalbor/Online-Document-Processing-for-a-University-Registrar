<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password, role FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($admin_id, $hashed_password, $role);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['admin_email'] = $email;
        $_SESSION['admin_role'] = $role; // Set the admin role in the session

        // Log the login activity
        $activity = "logged in";
        $log_stmt = $mysqli->prepare("INSERT INTO history_log (admin_id, activity) VALUES (?, ?)");
        $log_stmt->bind_param("is", $admin_id, $activity);
        $log_stmt->execute();
        $log_stmt->close();

        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../assets/images/ptc-logo.png">
    <link rel="stylesheet" href="../assets/css/signin_admin.css">
    <title>Admin Sign In</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand">
                <img class="logo" src="../assets/images/ptc-logo.png" width="61px" height="61px">&nbsp;&nbsp;Pateros Technological College
            </a>
        </div>
    </nav>
    <div class="container">
        <div class="box from-box">
            <div class="footer">
                <h1>PATEROS TECHNOLOGICAL COLLEGE</h1>
                <p>205 College Street, Sto. Rosario-Kanluran Pateros, Metro Manila</p>
            </div>
            <h2>Admin Sign In</h2>
            <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
            <form action="signin_admin.php" method="POST">
                <label><b>Email:</b></label>
                <input type="email" name="email" required> <br>
                <br>
                <label><b>Password:</b></label>
                <input type="password" name="password" required> <br>
                <button type="submit" class="btn"><b>Sign In</b></button>
            </form>
        </div>
    </div>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
</body>
</html>
