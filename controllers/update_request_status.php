<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id']) && isset($_POST['status'])) {
    $request_id = $_POST['request_id'];
    $new_status = $_POST['status'];

    $stmt = $mysqli->prepare("SELECT status, user_id, reference_id FROM requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->bind_result($old_status, $user_id, $reference_id);
    $stmt->fetch();
    $stmt->close();

  
    $stmt = $mysqli->prepare("UPDATE requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $request_id);

    if ($stmt->execute()) {
        
        $admin_id = $_SESSION['admin_id'];
        $activity = "changed the request status of $reference_id from $old_status to $new_status";
        $log_stmt = $mysqli->prepare("INSERT INTO history_log (admin_id, activity) VALUES (?, ?)");
        $log_stmt->bind_param("is", $admin_id, $activity);
        $log_stmt->execute();
        $log_stmt->close();

        
        $notification_message = "Your request status has been updated to '$new_status'.";
        $stmt = $mysqli->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $notification_message);
        $stmt->execute();

        header("Location: all_requests.php");
    } else {
        echo "Error updating record: " . $mysqli->error;
    }

    $stmt->close();
}
?>
