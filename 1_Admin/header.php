
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../ODR_FINAL/4_bootstrap 5.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../ODR_FINAL/4_bootstrap 5.3/js/bootstrap.bundle.min.js"></script>
    <link href="../../ODR_FINAL/CSS/admin_dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="Admin_dashboard.css">
    <link rel="stylesheet" href="header.css">
</head>
<body style="background.jpg: url('../ODR_FINAL/image/background.jpg');">
    <nav class="navbar navbar-expand-sm bg-success navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand titleName" href="">
                <img src="../../ODR_FINAL/3_image/PTC-Logo.png" alt="PTC-Logo" width="5%">
                <span class="text-dark">ADMIN DASHBOARD</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto">
                    <div class="container mt-3"></div>
                </ul>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle titleName" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Hi, Admin!
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                        <li><a class="dropdown-item" href="admin_settings.php">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2 bg-dark">
                <nav class="navbar bg-dark">
                    <div class="container-fluid">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link text-light" href="admin_dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="history_log.php">History Log</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link text-light" href="add_student.php">Add Student</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link text-light">Requests</a>
                                <ul>
                                    <li class="nav-item">
                                        <a class="nav-link text-light" href="all_requests.php">All</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-light" href="for_pickup_requests.php">Scheduled for Pickup</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-light" href="archive_requests.php">Archived</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="calendar_schedule.php">Slots Schedule</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="student_profile.php">Student Accounts</a>
                            </li>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col-sm-10 bg-secondary">
                <div id="main-content" class="pointer">
