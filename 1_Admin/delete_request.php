<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];

    $stmt = $mysqli->prepare("DELETE FROM requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);

    if ($stmt->execute()) {
        header("Location: all_requests.php");
    } else {
        echo "Error deleting record: " . $mysqli->error;
    }

    $stmt->close();
}
?>
