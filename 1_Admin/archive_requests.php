<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT r.id, r.document_types, r.status, r.reference_id, u.first_name, u.last_name, u.student_id 
          FROM requests r 
          JOIN users u ON r.user_id = u.id 
          WHERE (r.status = 'canceled' OR r.status = 'released') 
          AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.student_id LIKE ? OR r.reference_id LIKE ?)";
$search_param = '%' . $search . '%';
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $document_types, $status, $reference_id, $first_name, $last_name, $student_id);

$requests = [];
while ($stmt->fetch()) {
    $requests[] = [
        'id' => $id,
        'document_types' => json_decode($document_types, true),
        'status' => $status,
        'reference_id' => $reference_id,
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
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <link rel="stylesheet" href="archive_request.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Archived Requests</title>

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
    <h2>Archived Requests</h2>
    <div class="container">
        <form method="GET" action="archive_requests.php">
            <label for="search">Search:</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Enter student name, ID, or reference ID">
            <button type="submit">Search</button>
        </form>
    </div>
    <h3>Requests</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Reference ID</th>
                <th>Document Types</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="5">No archived requests found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($requests as $index => $request): ?>
                    <tr>
                        <td><?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?></td>
                        <td><?= htmlspecialchars($request['reference_id']) ?></td>
                        <td><?= !empty($request['document_types']) ? htmlspecialchars(implode(", ", $request['document_types'])) : "N/A" ?></td>
                        <td><?= htmlspecialchars($request['status']) ?></td>
                        <td>
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
