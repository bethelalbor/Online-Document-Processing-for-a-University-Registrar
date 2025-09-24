<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$notifications_query = "SELECT id, message, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$notifications_stmt = $mysqli->prepare($notifications_query);
$notifications_stmt->bind_param("i", $user_id);
$notifications_stmt->execute();
$notifications_stmt->store_result();
$notifications_stmt->bind_result($notification_id, $notification_message, $is_read, $created_at);

$notifications = [];
$unread_notifications = false;
while ($notifications_stmt->fetch()) {
    $notifications[] = [
        'id' => $notification_id,
        'message' => $notification_message,
        'is_read' => $is_read,
        'created_at' => $created_at
    ];
    if (!$is_read) {
        $unread_notifications = true;
    }
}
$notifications_stmt->close();


$update_query = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
$update_stmt = $mysqli->prepare($update_query);
$update_stmt->bind_param("i", $user_id);
$update_stmt->execute();
$update_stmt->close();

if ($unread_notifications) {
    echo '<span id="new-notification-flag" style="display: none;">true</span>';
}
?>

<ul>
    <?php if (empty($notifications)): ?>
        <li>No notifications</li>
    <?php else: ?>
        <?php foreach ($notifications as $notification): ?>
            <li>
                <?php if (!empty($notification['created_at'])): ?>
                    <strong><?= htmlspecialchars($notification['created_at']) ?>:</strong> 
                <?php endif; ?>
                <?= htmlspecialchars($notification['message']) ?>
                <?= $notification['is_read'] ? '' : '<strong>(New)</strong>' ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
