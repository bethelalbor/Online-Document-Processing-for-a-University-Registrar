<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    
    $mysqli->begin_transaction();

    try {
        
        $stmt = $mysqli->prepare("DELETE FROM notifications WHERE user_id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->close();

       
        $stmt = $mysqli->prepare("DELETE FROM requests WHERE user_id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->close();

        
        $stmt = $mysqli->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->close();

        $mysqli->commit();
        
        header("Location: student_profile.php");
        exit();
    } catch (Exception $e) {
        $mysqli->rollback();
        echo "An error occurred while deleting the student. Please try again.";
    }
} else {
    header("Location: student_profile.php");
    exit();
}
?>
