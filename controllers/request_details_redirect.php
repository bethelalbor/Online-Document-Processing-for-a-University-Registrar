<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

if (!isset($_POST['request_id']) || !isset($_POST['status'])) {
    header("Location: all_requests.php");
    exit();
}

$request_id = $_POST['request_id'];
$status = $_POST['status'];

switch ($status) {
    case 'pending':
        header("Location: pending_requests.php?request_id=" . $request_id);
        break;
    case 'for payment':
        header("Location: for_payment_requests.php?request_id=" . $request_id);
        break;
    case 'for scheduling':
        header("Location: for_scheduling_requests.php?request_id=" . $request_id);
        break;
    case 'for pickup':
        header("Location: for_pickup_requests.php?request_id=" . $request_id);
        break;
    case 'released':
        header("Location: archive_requests.php?request_id=" . $request_id);
        break;
    default:
        header("Location: all_requests.php");
        break;
}
?>
