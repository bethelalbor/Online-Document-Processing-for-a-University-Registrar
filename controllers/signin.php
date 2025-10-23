<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                session_start();
                $_SESSION['user_id'] = $id;
                header("Location: student_dashboard.php");
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
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
    <link rel="shortcut icon" type="x-icon" href="../assets/images/ptc-logo.png">
    <link rel="stylesheet" href="../assets/css/signin.css">
    <title>Sign In</title>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top " >
        <div class="container-fluid">
        <a class="navbar-brand"> <img class=logo src="../assets/images/ptc-logo.png" width="61px" height= "61px">&nbsp;&nbsp;Pateros Technological College</a>
        </div>
    </nav>
    <div class="container"> 
        <div class="box from-box">
    
        <div class="footer">
            <h1>PATEROS TECHNOLOGICAL COLLEGE</h1>
            <p>205 College Street, Sto. Rosario-Kanluran Pateros, Metro Manila</p>
        </div>


        <h2>Student Login</h2>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
        <?php if (isset($_GET['success'])) { echo "<p>Registration successful. Please sign in.</p>"; } ?>
        <form action="signin.php" method="POST">

        <div class="field input">
            <label class="from-label" for="form3Example3"><b>Email:</b></label>
            <input type="email" name="email" placeholder="Enter your email address"required> <br>
        </div>
        <br>

        <div class="field input">
            <label class="form-label" for="form3Example4"><b>Password:</b></label>
            <input type="password" name="password" placeholder="Enter password" required> <br>
        </div>
            <br>
            <div class="right-links">
            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn" style="padding-left: 2.5rem; padding-right: 2.5rem;"><b>Login</b></button>
            </div>
            <p>Do not have an account yet? <a href="register.php">Signup here!</a></p> 
        </form>
            </div>
        </div>
</body>
</html>
