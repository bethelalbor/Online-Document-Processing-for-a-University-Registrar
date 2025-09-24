<?php
session_start();
require 'config.php';

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    $activity = "logged out";
    $log_stmt = $mysqli->prepare("INSERT INTO history_log (admin_id, activity) VALUES (?, ?)");
    $log_stmt->bind_param("is", $admin_id, $activity);
    $log_stmt->execute();
    $log_stmt->close();
}

session_unset();
session_destroy();

header("Location: signin_admin.php");
exit();
?>
