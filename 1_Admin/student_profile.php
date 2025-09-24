<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT id, email, first_name, last_name, student_id FROM users WHERE first_name LIKE ? OR last_name LIKE ? OR student_id LIKE ?";
$search_param = '%' . $search . '%';
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss", $search_param, $search_param, $search_param);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $email, $first_name, $last_name, $student_id);

$students = [];
while ($stmt->fetch()) {
    $students[] = ['id' => $id, 'email' => $email, 'first_name' => $first_name, 'last_name' => $last_name, 'student_id' => $student_id];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <link rel="stylesheet" href="student_profile.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Student Profile</title>
    <style>
        .header-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-buttons form {
            margin: 0;
        }
    </style>

    <script>
        function confirm_request(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this student? It will delete all related requests created by this student.",
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
    <br>
    <div class="header-buttons">
        <br>
        <h2>Student Profile</h2>
        <form method="GET" action="add_student.php">
            <button type="submit">Create Student Account</button>
        </form>
    </div>
    <div class="container">
        <form method="GET" action="student_profile.php">
            <label for="search">Search:</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Enter student name or ID">
            <button type="submit">Search</button>
        </form>
    </div>
    <h3>Student List</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Student ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="4">No students found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $index => $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['first_name']) ?> <?= htmlspecialchars($student['last_name']) ?></td>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                        <td><?= htmlspecialchars($student['student_id']) ?></td>
                        <td>
                            <form method="POST" action="admin_reset_password.php" style="display:inline;">
                                <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['id']) ?>">
                                <button type="submit">Forgot Password</button>
                            </form>
                            <form method="GET" action="show_details.php" style="display:inline;">
                                <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['id']) ?>">
                                <button type="submit">Student Profile</button>
                            </form>
                            <form method="POST" action="delete_student.php" id="delete-form-<?= $index ?>" style="display:inline;">
                                <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['id']) ?>">
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
