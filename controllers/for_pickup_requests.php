<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$query = "SELECT r.id, r.document_types, r.status, r.reference_id, r.attachments, u.first_name, u.last_name, u.student_id 
          FROM requests r 
          JOIN users u ON r.user_id = u.id 
          WHERE r.status = 'for pickup' AND r.schedule_date = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $document_types, $status, $reference_id, $attachments,  $first_name, $last_name, $student_id);

$requests = [];
while ($stmt->fetch()) {
    $requests[] = [
        'id' => $id,
        'document_types' => json_decode($document_types, true),
        'status' => $status,
        'reference_id' => $reference_id,
        'attachments' => json_decode($attachments, true),
        'first_name' => $first_name,
        'last_name' => $last_name,
        'student_id' => $student_id
    ];
}
$stmt->close();

$dates_query = "SELECT DISTINCT schedule_date FROM requests WHERE status = 'for pickup'";
$dates_result = $mysqli->query($dates_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <link rel="stylesheet" href="all_requests.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>For Pickup Requests</title>

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
    <h2>For Pickup Requests</h2>
    <form method="GET" action="for_pickup_requests.php">
        <label for="date">Select Date:</label>
        <select name="date" required>
            <?php while ($row = $dates_result->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['schedule_date']) ?>" <?= $selected_date == $row['schedule_date'] ? 'selected' : '' ?>><?= htmlspecialchars($row['schedule_date']) ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Show Requests</button>
    </form>
    <h3>Requests for <?= htmlspecialchars($selected_date) ?></h3>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Reference ID</th>
                <th>Attachments</th>
                <th>Document Types</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="6">No requests found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($requests as $index => $request): ?>
                    <tr>
                        <td><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></td>
                        <td><?= htmlspecialchars($request['reference_id']) ?></td>
                        <td>
                            <?php 
                            $attachments = $request['attachments'];
                            $base_path = "../5_uploads/";
                            foreach ($attachments as $attachment) { 
                                echo "<a href='" . $base_path . htmlspecialchars($attachment) . "' target='_blank'>Attachment</a><br>"; 
                            } 
                            ?>
                        </td>
                        <td><?= !empty($request['document_types']) ? htmlspecialchars(implode(", ", $request['document_types'])) : "N/A" ?></td>
                        <td><?= htmlspecialchars($request['status']) ?></td>
                        <td>
                            <form method="POST" action="update_request_status.php" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?= htmlspecialchars($request['id']) ?>">
                                <select name="status">
                                    <option value="pending" <?= $request['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="for payment" <?= $request['status'] == 'for payment' ? 'selected' : '' ?>>For Payment</option>
                                    <option value="for scheduling" <?= $request['status'] == 'for scheduling' ? 'selected' : '' ?>>For Scheduling</option>
                                    <option value="for pickup" <?= $request['status'] == 'for pickup' ? 'selected' : '' ?>>For Pickup</option>
                                    <option value="released" <?= $request['status'] == 'released' ? 'selected' : '' ?>>Released</option>
                                    <option value="canceled" <?= $request['status'] == 'canceled' ? 'selected' : '' ?>>Canceled</option>
                                </select>
                                <button type="submit">Update Status</button>
                            </form>
                            <form method="GET" action="request_details.php" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?= htmlspecialchars($request['id']) ?>">
                                <button type="submit">Details</button>
                            </form>
                            <form method="POST" action="delete_request.php" id="delete-form-<?= $index ?>" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?= htmlspecialchars($request['id']) ?>">
                                <button type="submit" onclick="confirm_request(event, 'delete-form-<?= $index ?>')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <?php include 'footer.php'; ?>
</body>
</html>
