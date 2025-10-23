<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

if (!isset($_GET['request_id'])) {
    header("Location: all_requests.php");
    exit();
}

$request_id = $_GET['request_id'];

$stmt = $mysqli->prepare("SELECT r.id, r.document_types, r.status, r.conversation, r.attachments, r.reference_id, r.schedule_date, u.first_name, u.last_name, u.student_id, u.status as student_status 
                          FROM requests r 
                          JOIN users u ON r.user_id = u.id 
                          WHERE r.id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$stmt->bind_result($id, $document_types, $status, $conversation, $attachments, $reference_id, $schedule_date, $first_name, $last_name, $student_id, $student_status);
$stmt->fetch();
$stmt->close();

$document_types = !empty($document_types) ? json_decode($document_types, true) : [];
$conversation = !empty($conversation) ? json_decode($conversation, true) : [];
$attachments = !empty($attachments) ? json_decode($attachments, true) : [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply_message']) && isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
    $reply_message = $_POST['reply_message'];
    $role = 'admin';

    
    $stmt = $mysqli->prepare("SELECT conversation FROM requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->bind_result($conversation);
    $stmt->fetch();
    $stmt->close();

    $conversation = json_decode($conversation, true);
    $conversation[] = ['role' => $role, 'message' => $reply_message];

    $new_conversation = json_encode($conversation);

    $stmt = $mysqli->prepare("UPDATE requests SET conversation = ? WHERE id = ?");
    $stmt->bind_param("si", $new_conversation, $request_id);

    if ($stmt->execute()) {
        
        $stmt = $mysqli->prepare("SELECT user_id FROM requests WHERE id = ?");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        
        $notification_message = "You have received a reply for your request.";
        $stmt = $mysqli->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $notification_message);
        $stmt->execute();

        header("Location: request_details.php?request_id=" . $request_id);
    } else {
        $error = "An error occurred. Please try again.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../assets/images/ptc-logo.png">
    <link rel="stylesheet" href="../assets/css/request_details.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Request Details</title>

    <script>
        function confirm_request(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this request? All the requests created by this student will be deleted as well.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#008000',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
</head>
<body>
<?php include 'header.php'; ?>
<a href="all_requests.php"><img src="../assets/images/back.png" width= "30" alt="Back"/> </a>

<h2>Request Details</h2>
<p><strong>Reference ID:</strong> <?= htmlspecialchars($reference_id) ?></p>
<p><strong>Name:</strong> <?= htmlspecialchars($first_name . ' ' . $last_name) ?></p>
<p><strong>Status:</strong> <?= htmlspecialchars($student_status) ?></p>
<p><strong>Attachments:</strong>
    <?php foreach ($attachments as $attachment) { 
        echo "<a href='" . htmlspecialchars($attachment) . "'>Attachment</a><br>"; 
    } ?>
</p>
<p><strong>Requested Documents:</strong> <?= !empty($document_types) ? htmlspecialchars(implode(", ", $document_types)) : "N/A" ?></p>

<h3>Conversation:</h3>
<?php foreach ($conversation as $message): ?>
    <p><strong><?= htmlspecialchars($message['role']) ?>:</strong> <?= htmlspecialchars($message['message']) ?></p>
<?php endforeach; ?>

<form method="POST" action="update_request_status.php">
    <input type="hidden" name="request_id" value="<?= htmlspecialchars($id) ?>">
    <label for="status">Status:</label>
    <select name="status">
        <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
        <option value="for payment" <?= $status == 'for payment' ? 'selected' : '' ?>>For Payment</option>
        <option value="for scheduling" <?= $status == 'for scheduling' ? 'selected' : '' ?>>For Scheduling</option>
        <option value="released" <?= $status == 'released' ? 'selected' : '' ?>>Released</option>
        <option value="canceled" <?= $status == 'canceled' ? 'selected' : '' ?>>Canceled</option>
    </select>
    <button type="submit">Update Status</button>
</form>

<form method="POST" action="request_details.php?request_id=<?= $request_id ?>">
    <input type="hidden" name="request_id" value="<?= htmlspecialchars($id) ?>">
    <label for="reply_message">Send a message:</label> <br>
    <textarea name="reply_message"></textarea> <br>
    <button type="submit">Send Reply</button>
</form> 

<form method="GET" action="show_details2.php">
    <input type="hidden" name="student_id" value="<?= htmlspecialchars($student_id) ?>">
    <input type="hidden" name="request_id" value="<?= htmlspecialchars($request_id) ?>">
    <button type="submit">Student Profile</button>
</form>

<form method="POST" action="delete_request.php" id="delete-form" onsubmit="confirm_request(event, 'delete-form')">
    <input type="hidden" name="request_id" value="<?= htmlspecialchars($id) ?>">
    <button type="submit">Delete</button>
</form>

<?php include 'footer.php'; ?>
</body>
</html>
