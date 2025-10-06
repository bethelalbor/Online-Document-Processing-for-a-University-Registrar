<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT first_name, last_name, student_id, status FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $student_id, $status);
$stmt->fetch();
$stmt->close();


$document_types = [];
$stmt = $mysqli->prepare("SELECT type FROM document_types");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $document_types[] = $row['type'];
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_document_types = isset($_POST['document_types']) ? $_POST['document_types'] : [];
    $message = $_POST['message'];
    $file_path = null;
    $attachments = [];

    if (!empty($_FILES['file']['name'])) {
        $target_dir = "../5_uploads/";
        $file_path = $target_dir . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
        $attachments[] = $file_path;
    }

    $attachments_json = json_encode($attachments);
    $conversation = json_encode([['role' => 'student', 'message' => $message]]);
    $document_types_json = json_encode($selected_document_types);

    $stmt = $mysqli->prepare("INSERT INTO requests (user_id, document_types, attachments, conversation) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $document_types_json, $attachments_json, $conversation);

    if ($stmt->execute()) {
        $request_id = $stmt->insert_id;
        $reference_id = date('mdY') . $request_id;

        $stmt = $mysqli->prepare("UPDATE requests SET reference_id = ? WHERE id = ?");
        $stmt->bind_param("si", $reference_id, $request_id);
        $stmt->execute();

        header("Location: requests.php");
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
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <title>New Request</title>
    <link rel="stylesheet" type="text/css" href="new_request.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirm_request(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "By submitting this request, you agree to the institution's terms and conditions and guarantee that all information or data provided are accurate. If the details are found to be false or the policies are violated, the administrator has complete discretion to cancel your request without prior notification.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#008000',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("new_request").submit();
                }
            });
        }
    </script>
</head>
<body style="background-image: url('../3_image/mybg.png');">
<?php include 'header.php';?>
    <h2>New Request</h2>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    <form id="new_request" action="new_request.php" method="POST" enctype="multipart/form-data" onsubmit="confirm_request(event)">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?= $first_name ?>" readonly> <br>
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?= $last_name ?>" readonly> <br>
        <label>Student ID:</label> 
        <input type="text" name="student_id" value="<?= $student_id ?>" readonly> <br>
        <label>Status:</label> 
        <input type="text" name="status" value="<?= $status ?>" readonly> <br>
        <label>Document Type:</label> <br>
        <div class="checkbox-container">
            <?php foreach ($document_types as $type): ?>
                <input type="checkbox" name="document_types[]" value="<?= htmlspecialchars($type) ?>">
                <span class="checkmark"></span><?= htmlspecialchars($type) ?> <br>
            <?php endforeach; ?>
        </div>
        <label>Attach File:</label>
        <input type="file" name="file">  <br>
        <label>Message:</label> <br>
        <textarea name="message"></textarea> <br>
        <button type="submit">Submit Request</button>
    </form>
    <?php include 'footer.php';?>
</body>
</html>
