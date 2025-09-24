<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

$error = $success = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $birthday = $_POST['birthday'] ?? '';
    $address = $_POST['address'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $student_id = $_POST['student_id'] ?? '';
    $status = $_POST['status'] ?? '';
    $school_year = $_POST['school_year'] ?? '';
    $course = $_POST['course'] ?? '';

    
    if (empty($email) || empty($password) || empty($confirm_password) || empty($first_name) || empty($last_name) || empty($birthday) || empty($address) || empty($mobile) || empty($student_id) || empty($status) || empty($school_year) || empty($course)) {
        $error = "Please fill in all required fields.";
    } elseif ($password != $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? OR student_id = ?");
        $stmt->bind_param("ss", $email, $student_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "A user with this email or student ID already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("INSERT INTO users (email, password, first_name, last_name, middle_name, birthday, address, mobile, student_id, status, school_year, course) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $email, $hashed_password, $first_name, $last_name, $middle_name, $birthday, $address, $mobile, $student_id, $status, $school_year, $course);

            if ($stmt->execute()) {
                $success = "Student added successfully.";
            } else {
                $error = "An error occurred. Please try again.";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
<link rel="stylesheet" href="add_student.css">
    <title>Add Student</title>
</head>
<body>
    <?php include 'header.php'; ?>  
    <a href="student_profile.php"><img src="../3_image/back.png" width= "30" alt="Back"/> </a>
    <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <?php if (!empty($success)) { echo "<p style='color:white;'>$success</p>"; } ?>
    
    <div class="container">
        <div class="box from-box">



            <h2>Add Student</h2>
                <form action="add_student.php" method="POST">
                
                <div class="fields">

                <div class="input-field">
                <label>Email:</label>
                <input type="email" name="email" required>
                </div>

                <div class="input-field">
                <label>Password:</label>
                <input type="password" name="password" required>
                </div>

                <div class="input-field">
                <label>Confirm Password:</label> 
                <input type="password" name="confirm_password" required>
                </div>

                <div class="input-field">
                <label>First Name:</label> 
                <input type="text" name="first_name" required>
                </div>

                <div class="input-field">
                <label>Last Name:</label>
                <input type="text" name="last_name" required> 
                </div>


                <div class="input-field">
                <label>Middle Name:</label>
                <input type="text" name="middle_name">
                </div>

                <div class="input-field">
                <label>Birthday:</label>
                <input type="date" name="birthday" required> 
                </div>

                <div class="input-field">
                <label>Address:</label>
                <input type="text" name="address" required> 
                </div>

                <div class="input-field">
                <label>Mobile:</label>
                <input type="text" name="mobile" required>
                </div>
                
                <div class="input-field">
                <label>Student ID:</label>
                <input type="text" name="student_id" required>
                </div>

                <div class="input-field">
                <label>School Year Admitted:</label> 
                <input type="text" name="school_year" required>
                </div>
            
                <div class="input-field">
                <label>Status:</label>
                <select name="status" required>
                    <option value="enrolled">Enrolled</option>
                    <option value="graduated">Graduated</option>
                    <option value="graduating">Graduating</option>
                    <option value="dropped">Did Not Finish/Dropped</opt>
                </select>
                </div>  
                
                <div class="course">
                <label>Course:</label>
                <select id="courseOptions" name="course"> 
                        <option value="BSOA">Bachelor of Science in Office Administration</option>
                        <option value="BSIT">Bachelor of Science in Information Technology</option>
                        <option value="COA">Certificate in Office Administration</option>
                        <option value="CCS">Certificate in Computer Science</option>
                        <option value="CHRM">Certificate in Hotel and Restaurant Management</option>
                        <option value="ABA">Associate in Business Administration</option>
                        <option value="AAIS">Associate in Accounting Information System</option>
                    </select>
                </div>

                <button type="submit" class="btn"><b>Register</b></button>
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
