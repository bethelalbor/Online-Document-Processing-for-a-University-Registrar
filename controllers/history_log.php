<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT h.activity, h.timestamp, a.email 
          FROM history_log h 
          JOIN admins a ON h.admin_id = a.id 
          WHERE h.activity LIKE ? OR a.email LIKE ?
          ORDER BY h.timestamp DESC";

$search_param = '%' . $search . '%';
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <link rel="stylesheet" href="archive_request.css">
    <title>History Log</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <h2>History Log</h2>
    <div class="container">
        <form method="GET" action="history_log.php">
            <label for="search">Search:</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Enter email or activity">
            <button type="submit">Search</button>
        </form>
    </div>
    <h3>History</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Email</th>
                <th>Activity</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['activity']) ?></td>
                    <td><?= htmlspecialchars($row['timestamp']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php include 'footer.php'; ?>
</body>
</html>
