<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

$stmt = $mysqli->prepare("SELECT status, COUNT(*) as count FROM requests GROUP BY status");
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($status, $count);

$requests = [];
while ($stmt->fetch()) {
    $requests[$status] = $count;
}
$stmt->close();

$query = "
    SELECT 
        DATE(created_at) AS date, 
        COUNT(*) AS new_requests,
        SUM(CASE WHEN status = 'released' THEN 1 ELSE 0 END) AS released_requests
    FROM requests 
    GROUP BY DATE(created_at)
    ORDER BY DATE(created_at)
";
$result = $mysqli->query($query);
$dates = [];
$new_counts = [];
$released_counts = [];

while ($row = $result->fetch_assoc()) {
    $dates[] = $row['date'];
    $new_counts[] = $row['new_requests'];
    $released_counts[] = $row['released_requests'];
}

$dates_json = json_encode($dates);
$new_counts_json = json_encode($new_counts);
$released_counts_json = json_encode($released_counts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../assets/images/ptc-logo.png">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="status-box pending-box">
            <a href="all_requests.php?status=pending">Pending</a>
            <span><?= $requests['pending'] ?? 0 ?></span>
        </div>
        <div class="status-box payment-box">
            <a href="all_requests.php?status=for payment">For Payment</a>
            <span><?= $requests['for payment'] ?? 0 ?></span>
        </div>
        <div class="status-box scheduling-box">
            <a href="all_requests.php?status=for scheduling">For Scheduling</a>
            <span><?= $requests['for scheduling'] ?? 0 ?></span>
        </div>
        <div class="status-box pickup-box">
            <a href="for_pickup_requests.php">For Pickup</a>
            <span><?= $requests['for pickup'] ?? 0 ?></span>
        </div>
        <div class="status-box released-box">
            <a href="archive_requests.php">Released</a>
            <span><?= $requests['released'] ?? 0 ?></span>
        </div>
    </div>

    <div class="bar-graph-container">
        <canvas id="requestsBarGraph"></canvas>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        const ctx = document.getElementById('requestsBarGraph').getContext('2d');
        const requestsBarGraph = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $dates_json ?>,
                datasets: [
                    {
                        label: 'New Requests Received per Day',
                        data: <?= $new_counts_json ?>,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Requests Released per Day',
                        data: <?= $released_counts_json ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 500,
                        ticks: {
                            stepSize: 50
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
