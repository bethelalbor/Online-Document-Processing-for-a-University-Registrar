<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}


$first_name = '';
$last_name = '';


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT first_name, last_name FROM users WHERE id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($first_name, $last_name);
        $stmt->fetch();
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
    <title>Online Document Request</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="../assets/css/admin_dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    
    <script>
        function checkNotifications() {
            $.ajax({
                url: 'notifications.php',
                success: function(data) {
                    $('#notificationPopup').html(data);
                    if (document.getElementById('new-notification-flag')) {
                        document.getElementById('notificationIcon').src = '../assets/images/newnotification.png';
                    } else {
                        document.getElementById('notificationIcon').src = '../assets/images/notification.png';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error checking notifications:', textStatus, errorThrown);
                }
            });
        }

        $(document).ready(function() {
            checkNotifications();
        });

        function toggleNotifications() {
            var popup = document.getElementById('notificationPopup');
            if (popup.style.display === 'block') {
                popup.style.display = 'none';
            } else {
                $.ajax({
                    url: 'notifications.php',
                    success: function(data) {
                        $('#notificationPopup').html(data);
                        popup.style.display = 'block';
                        
                        
                        if (document.getElementById('new-notification-flag')) {
                            document.getElementById('notificationIcon').src = '../assets/images/newnotification.png';
                        } else {
                            document.getElementById('notificationIcon').src = '../assets/images/notification.png';
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error toggling notifications:', textStatus, errorThrown);
                    }
                });
            }
        }

        window.onclick = function(event) {
            var popup = document.getElementById('notificationPopup');
            if (!event.target.matches('.notification-icon') && !popup.contains(event.target)) {
                if (popup.style.display === 'block') {
                    popup.style.display = 'none';
                }
            }
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-sm navbar-dark navbar-top">
        <div class="container-fluid">
            <a class="navbar-brand titleName" href="">
                <img src="../assets/images/ptc-logo.png" alt="PTC-Logo" width="5%">
                <span class="text-light">Online Document Request</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto">
                    <!-- <div class="container mt-3"></div> -->
                </ul>
                <div class="d-flex align-items-center">
                    <span class="notification-icon" onclick="toggleNotifications()">
                        <img id="notificationIcon" src="../assets/images/notification.png" alt="Notifications" width="20">
                    </span>
                    <div class="notification-popup" id="notificationPopup"></div>
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Hi, <?= htmlspecialchars($first_name . ' ' . $last_name) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="account.php">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <nav class="navbar navbar-side">
                    <div class="container-fluid">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link text-light" href="student_dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="new_request.php">New Requests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="requests.php">Requests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="schedule.php">Request for Scheduling</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col-sm-10 bg-secondary">
                <div id="main-content" class="pointer">
