<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

if (!isset($_GET['student_id']) || !isset($_GET['request_id'])) {
    header("Location: request_details.php?request_id=" . urlencode($_GET['request_id']));
exit();
 
    exit();
}

$student_id = $_GET['student_id'];
$request_id = $_GET['request_id'];

$stmt = $mysqli->prepare("SELECT email, first_name, last_name, middle_name, birthday, address, mobile, id, status, school_year, course FROM users WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($email, $first_name, $last_name, $middle_name, $birthday, $address, $mobile, $student_id, $status, $school_year, $course);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../assets/images/ptc-logo.png">
    <link rel="stylesheet" href="../assets/css/show_details.css">
    <title>Student Details</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <h2>Student Details</h2>
    <table border="1">
        <tr>
            <td>Email</td>
            <td><?= htmlspecialchars($email) ?></td>
        </tr>
        <tr>
            <td>First Name</td>
            <td><?= htmlspecialchars($first_name) ?></td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><?= htmlspecialchars($last_name) ?></td>
        </tr>
        <tr>
            <td>Middle Name</td>
            <td><?= htmlspecialchars($middle_name) ?></td>
        </tr>
        <tr>
            <td>Birthday</td>
            <td><?= htmlspecialchars($birthday) ?></td>
        </tr>
        <tr>
            <td>Address</td>
            <td><?= htmlspecialchars($address) ?></td>
        </tr>
        <tr>
            <td>Mobile</td>
            <td><?= htmlspecialchars($mobile) ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td><?= htmlspecialchars($status) ?></td>
        </tr>
        <tr>
            <td>School Year</td>
            <td><?= htmlspecialchars($school_year) ?></td>
        </tr>
        <tr>
            <td>Course</td>
            <td><?= htmlspecialchars($course) ?></td>
        </tr>
    </table>
    <form method="GET" action="request_details.php">
        <input type="hidden" name="request_id" value="<?= htmlspecialchars($request_id) ?>">
        <button type="submit">Back to Request Details</button>
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>