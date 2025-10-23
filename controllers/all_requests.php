<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$filter_document_types = isset($_GET['document_types']) ? $_GET['document_types'] : '';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT r.id, r.document_types, r.status, r.reference_id, r.attachments, u.first_name, u.last_name, u.student_id 
          FROM requests r 
          JOIN users u ON r.user_id = u.id 
          WHERE (u.first_name LIKE ? OR u.last_name LIKE ? OR u.student_id LIKE ? OR r.reference_id LIKE ?)";

$params = ["%$search%", "%$search%", "%$search%", "%$search%"];

if (!empty($filter_status)) {
    $query .= " AND r.status = ?";
    $params[] = $filter_status;
}

if (!empty($filter_document_types)) {
    $query .= " AND JSON_CONTAINS(r.document_types, '\"" . $filter_document_types . "\"')";
}

$query .= " ORDER BY FIELD(r.status, 'canceled', 'released'), r.id DESC";

$stmt = $mysqli->prepare($query);
$types = str_repeat('s', count($params));
$stmt->bind_param($types, ...$params);

$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $document_types, $status, $reference_id, $attachments, $first_name, $last_name, $student_id);

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href=".../assets/images/ptc-logo.png">
    <link rel="stylesheet" href="../assets/css/all_requests.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>All Requests</title>

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
    <h2>All Requests</h2>
    <div class="container">
        <form method="GET" action="all_requests.php">
            <label for="search">Search:</label>
            <br>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Enter student name, ID, or reference ID">
            <button type="submit">Search</button>
            <br>
            <label for="status">Filter by Status:</label>
            <br>
            <select name="status">
                <option value="">All</option>
                <option value="pending" <?= $filter_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="for payment" <?= $filter_status == 'for payment' ? 'selected' : '' ?>>For Payment</option>
                <option value="for scheduling" <?= $filter_status == 'for scheduling' ? 'selected' : '' ?>>For Scheduling</option>
                <option value="for pickup" <?= $filter_status == 'for pickup' ? 'selected' : '' ?>>For Pickup</option>
                <option value="released" <?= $filter_status == 'released' ? 'selected' : '' ?>>Released</option>
                <option value="canceled" <?= $filter_status == 'canceled' ? 'selected' : '' ?>>Canceled</option>
            </select>
            <br>
            <label for="document_types">Filter by Document Type:</label>
            <br>
            <select name="document_types">
                <option value="">All</option>
                <option value="Transcript of Records">Transcript of Records</option>
                <option value="Transfer Credentials">Transfer Credentials</option>
                <option value="Diploma">Diploma</option>
                <option value="Certificate of Transfer">Certificate of Transfer</option>
                <option value="Certificate of Enrollment/Registration">Certificate of Enrollment/Registration</option>
                <option value="Certificate of Grade">Certificate of Grade</option>
                <option value="Certificate of Honorable Dismissal">Certificate of Honorable Dismissal</option>
                <option value="Certificate of Graduation">Certificate of Graduation</option>
                <option value="Certificate of General Weighted Average">Certificate of General Weighted Average</option>
                <option value="Certificate of Study Load">Certificate of Study Load</option>
                <option value="Certification-Authentication-Verification (CAV) of Grades of TOR/Diploma (CTC)">Certification-Authentication-Verification (CAV) of Grades of TOR/Diploma (CTC)</option>
                <option value="Certified True Copy of Transcript of Record">Certified True Copy of Transcript of Record</option>
                <option value="Certified True Copy of Diploma">Certified True Copy of Diploma</option>
                <option value="Certified True Copy of Certificate of Good Moral Character">Certified True Copy of Certificate of Good Moral Character</option>
                <option value="Certified True Copy of Certificate of Graduation">Certified True Copy of Certificate of Graduation</option>
                <option value="Certified True Copy of Certificate of General Weighted Average">Certified True Copy of Certificate of General Weighted Average</option>
                <option value="Form 138 A (CTC)">Form 138 A (CTC)</option>
                <option value="Form 137">Form 137</option>
            </select>
            <br>
            <button type="submit">Apply Filters</button>
        </form>
    </div>

    <h3>Requests</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Reference ID</th>
                <th>Attachments</th>
                <th>Requested Documents</th>
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
