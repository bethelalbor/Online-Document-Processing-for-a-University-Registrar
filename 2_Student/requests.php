<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id, document_types, status, conversation, attachments, reference_id, schedule_date FROM requests WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $document_types, $status, $conversation, $attachments, $reference_id, $schedule_date);

$ongoing_requests = [];
$archived_requests = [];
while ($stmt->fetch()) {
    $request = [
        'id' => $id,
        'document_types' => json_decode($document_types, true),
        'status' => $status,
        'conversation' => json_decode($conversation, true),
        'attachments' => json_decode($attachments, true),
        'reference_id' => $reference_id,
        'schedule_date' => $schedule_date
    ];
    if ($status == 'released' || $status == 'canceled') {
        $archived_requests[] = $request;
    } else {
        $ongoing_requests[] = $request;
    }
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reply_message']) && isset($_POST['request_id'])) {
        $request_id = $_POST['request_id'];
        $reply_message = $_POST['reply_message'];
        $role = 'student';

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
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_FILES['file']['name']) && isset($_POST['request_id'])) {
        $request_id = $_POST['request_id'];
        $target_dir = "../5_uploads";
        $file_path = $target_dir . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path);

        
        $stmt = $mysqli->prepare("SELECT attachments FROM requests WHERE id = ?");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->bind_result($attachments);
        $stmt->fetch();
        $stmt->close();

        $attachments = json_decode($attachments, true);
        $attachments[] = $file_path;

        $new_attachments = json_encode($attachments);

        $stmt = $mysqli->prepare("UPDATE requests SET attachments = ? WHERE id = ?");
        $stmt->bind_param("si", $new_attachments, $request_id);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['cancel_request_id'])) {
        $cancel_request_id = $_POST['cancel_request_id'];
        $stmt = $mysqli->prepare("UPDATE requests SET status = 'canceled' WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cancel_request_id, $user_id);
        $stmt->execute();
        header("Location: requests.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <link rel="stylesheet" type="text/css" href="request.css">
    <title>Requests</title>
</head>
<body>
<?php include 'header.php';?>
    <h2>Requests</h2>
    <ul class="requests-list">
        <?php if (empty($ongoing_requests)): ?>
            <li class="request-item">No ongoing requests</li>
        <?php else: ?>
            <?php foreach ($ongoing_requests as $request): ?>
                <li class="request-item">
                    <strong>Reference ID:</strong> <?= htmlspecialchars($request['reference_id']) ?> <br>
                    <strong>Document Types:</strong> <?= !empty($request['document_types']) ? htmlspecialchars(implode(", ", $request['document_types'])) : "N/A" ?> <br>
                    <strong>Status:</strong> <?= htmlspecialchars($request['status']) ?> <br>
                    <?php foreach ($request['attachments'] as $attachment) { echo "<a href='" . htmlspecialchars($attachment) . "'>Attached File</a><br>"; } ?>
                    <h4>Conversation:</h4>
                    <?php foreach ($request['conversation'] as $message): ?>
                        <p><strong><?= htmlspecialchars($message['role']) ?>:</strong> <?= htmlspecialchars($message['message']) ?></p>
                    <?php endforeach; ?>
                    <form method="POST" action="requests.php" enctype="multipart/form-data">
                        <input type="hidden" name="request_id" value="<?= htmlspecialchars($request['id']) ?>">
                        <label for="reply_message">Send message:</label>
                        <textarea name="reply_message"></textarea> <br>
                        <label for="file">Attach File:</label>
                        <input type="file" name="file"> <br>
                        <button type="submit">Send</button>
                    </form> 
                    <form method="POST" action="requests.php"> 
                        <input type="hidden" name="cancel_request_id" value="<?= htmlspecialchars($request['id']) ?>">
                        <button type="submit">Cancel Request</button>
                    </form>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    <h2>Archived Requests</h2>
    <ul class="requests-list">
        <?php if (empty($archived_requests)): ?>
            <li class="request-item">No archived requests</li>
        <?php else: ?>
            <?php foreach ($archived_requests as $request): ?>
                <li class="request-item">
                    <strong>Reference ID:</strong> <?= htmlspecialchars($request['reference_id']) ?> <br>
                    <strong>Document Types:</strong> <?= !empty($request['document_types']) ? htmlspecialchars(implode(", ", $request['document_types'])) : "N/A" ?> <br>
                    <strong>Status:</strong> <?= htmlspecialchars($request['status']) ?> <br>
                    <?php foreach ($request['attachments'] as $attachment) { echo "<a href='" . htmlspecialchars($attachment) . "'>Attached File</a><br>"; } ?>
                    <h4>Conversation:</h4>
                    <?php foreach ($request['conversation'] as $message): ?>
                        <p><strong><?= htmlspecialchars($message['role']) ?>:</strong> <?= htmlspecialchars($message['message']) ?></p>
                    <?php endforeach; ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    <?php include 'footer.php';?>
</body>
</html>