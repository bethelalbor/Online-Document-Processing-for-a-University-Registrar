<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$notifications_query = "SELECT id, message, is_read FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$notifications_stmt = $mysqli->prepare($notifications_query);
$notifications_stmt->bind_param("i", $user_id);
$notifications_stmt->execute();
$notifications_stmt->store_result();
$notifications_stmt->bind_result($notification_id, $notification_message, $is_read);

$notifications = [];
while ($notifications_stmt->fetch()) {
    $notifications[] = [
        'id' => $notification_id,
        'message' => $notification_message,
        'is_read' => $is_read
    ];
}
$notifications_stmt->close();


$stmt = $mysqli->prepare("SELECT status, COUNT(*) as count FROM requests WHERE user_id = ? GROUP BY status");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($status, $count);

$requests = [];
while ($stmt->fetch()) {
    $requests[$status] = $count;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <link rel="stylesheet" type="text/css" href="student_dashboard.css">
    <title>Student Dashboard</title>
</head>
<body>
<?php include 'header.php';?>
    <h2>Your Requests</h2>
    <div class="dashboard-container">
        <div class="status-box pending">
            Pending
            <span><?= $requests['pending'] ?? 0 ?></span>
        </div>
        <div class="status-box for-payment">
            For Payment
            <span><?= $requests['for payment'] ?? 0 ?></span>
        </div>
        <div class="status-box for-scheduling">
            For Scheduling
            <span><?= $requests['for scheduling'] ?? 0 ?></span>
        </div>
        <div class="status-box for-pickup">
            For Pickup
            <span><?= $requests['for pickup'] ?? 0 ?></span>
        </div>
        <div class="status-box released">
            Released
            <span><?= $requests['released'] ?? 0 ?></span>
        </div>
    </div>
    <?php include 'footer.php';?>
</body>
</html>
