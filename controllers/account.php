<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$user_query = "SELECT first_name, last_name FROM users WHERE id = ?";
$user_stmt = $mysqli->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_stmt->store_result();
$user_stmt->bind_result($first_name, $last_name);
$user_stmt->fetch();
$user_stmt->close();

$stmt = $mysqli->prepare("SELECT email, first_name, last_name, middle_name, birthday, address, mobile, student_id, status, school_year, course FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
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
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <link rel="stylesheet" href="account.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Account</title>
</head>
<body>
<?php include 'header.php';?>
<main>
    <!-- <div class="container mt-5"> -->
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h2>Account Information</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="row">Email</th>
                            <td><?= $email ?></td>
                        </tr>
                        <tr>
                            <th scope="row">First Name</th>
                            <td><?= $first_name ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Last Name</th>
                            <td><?= $last_name ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Middle Name</th>
                            <td><?= $middle_name ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Birthday</th>
                            <td><?= $birthday ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Address</th>
                            <td><?= $address ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Mobile</th>
                            <td><?= $mobile ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Student ID</th>
                            <td><?= $student_id ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Status</th>
                            <td><?= $status ?></td>
                        </tr>
                        <tr>
                            <th scope="row">School Year</th>
                            <td><?= $school_year ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Course</th>
                            <td><?= $course ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <button class="btn btn-primary mt-3" onclick="location.href='update_account.php'">Update Account Details</button>
                    <button class="btn btn-secondary mt-3" onclick="location.href='change_password.php'">Change Password</button>
                </div>
            </div>
        </div>
    <!-- </div> -->
</main>
<?php include 'footer.php';?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
