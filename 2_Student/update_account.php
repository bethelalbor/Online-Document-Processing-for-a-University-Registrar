<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $birthday = $_POST['birthday'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $student_id = $_POST['student_id'];
    $status = $_POST['status'];
    $school_year = $_POST['school_year'];
    $course = $_POST['course'];

    $stmt = $mysqli->prepare("UPDATE users SET email = ?, first_name = ?, last_name = ?, middle_name = ?, birthday = ?, address = ?, mobile = ?, student_id = ?, status = ?, school_year = ?, course = ? WHERE id = ?");
    $stmt->bind_param("sssssssssssi", $email, $first_name, $last_name, $middle_name, $birthday, $address, $mobile, $student_id, $status, $school_year, $course, $user_id);

    if ($stmt->execute()) {
        $success = "Account details updated successfully.";
    } else {
        $error = "An error occurred. Please try again.";
    }

    $stmt->close();
}

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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="update_account.css">
    <title>Update Account</title>
</head>
<body>
<?php include 'header.php';?>
<h2>Update Account</h2>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
            <?php if (isset($success)) { echo "<p class='text-success'>$success</p>"; } ?>
            <?php if (isset($error)) { echo "<p class='text-danger'>$error</p>"; } ?>
            <form action="update_account.php" method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="first_name">First Name:</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $first_name ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="last_name">Last Name:</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $last_name ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="middle_name">Middle Name:</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= $middle_name ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="birthday">Birthday:</label>
                        <input type="date" class="form-control" id="birthday" name="birthday" value="<?= $birthday ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?= $address ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="mobile">Mobile:</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?= $mobile ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="student_id">Student ID:</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" value="<?= $student_id ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="enrolled" <?= $status == 'enrolled' ? 'selected' : '' ?>>Enrolled</option>
                            <option value="graduating" <?= $status == 'graduating' ? 'selected' : '' ?>>Graduating</option>
                            <option value="graduated" <?= $status == 'graduated' ? 'selected' : '' ?>>Graduated</option>
                            <option value="dropped" <?= $status == 'dropped' ? 'selected' : '' ?>>Dropped</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="school_year">School Year:</label>
                        <input type="text" class="form-control" id="school_year" name="school_year" value="<?= $school_year ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="course">Course:</label>
                        <select class="form-control" id="course" name="course" required>
                            <option value="BSOA" <?= $course == 'BSOA' ? 'selected' : '' ?>>Bachelor of Science in Office Administration</option>
                            <option value="BSIT" <?= $course == 'BSIT' ? 'selected' : '' ?>>Bachelor of Science in Information Technology</option>
                            <option value="COA" <?= $course == 'COA' ? 'selected' : '' ?>>Certificate in Office Administration</option>
                            <option value="CCS" <?= $course == 'CCS' ? 'selected' : '' ?>>Certificate in Computer Science</option>
                            <option value="CHRM" <?= $course == 'CHRM' ? 'selected' : '' ?>>Certificate in Hotel and Restaurant Management</option>
                            <option value="ABA" <?= $course == 'ABA' ? 'selected' : '' ?>>Associate in Business Administration</option>
                            <option value="AAIS" <?= $course == 'AAIS' ? 'selected' : '' ?>>Associate in Accounting Information System</option>
                        </select>
                    </div>
                </div>
                    <button type="submit" class="btn btn-success">Update Details</button>
            </div>
        </div>
    </div>
</form>
<?php include 'footer.php';?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>