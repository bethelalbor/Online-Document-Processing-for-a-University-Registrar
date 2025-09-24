<?php
session_start();
require 'config.php';
ob_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$query = "SELECT status, course FROM users WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($student_status, $student_course);
$stmt->fetch();
$stmt->close();

$query = "SELECT id, document_types, status, schedule_date, reference_id FROM requests WHERE user_id = ? AND status IN ('for scheduling', 'for pickup')";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $document_types, $status, $schedule_date, $reference_id);

$requests = [];
while ($stmt->fetch()) {
    $requests[] = [
        'id' => $id,
        'document_types' => json_decode($document_types, true),
        'status' => $status,
        'schedule_date' => $schedule_date,
        'reference_id' => $reference_id
    ];
}
$stmt->close();

$available_dates = [];
foreach ($requests as $request) {
    if ($request['status'] == 'for scheduling') {
        $document_types_json = json_encode($request['document_types']);
        $query = "SELECT date, available_slots 
                  FROM schedule_slots 
                  WHERE available_slots > 0 
                  AND JSON_CONTAINS(statuses, '\"$student_status\"') 
                  AND JSON_CONTAINS(document_types, ?)
                  AND JSON_CONTAINS(courses, '\"$student_course\"')";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $document_types_json);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($date, $available_slots);
        while ($stmt->fetch()) {
            $available_dates[] = [
                'date' => $date,
                'available_slots' => $available_slots
            ];
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
    <link rel="stylesheet" type="text/css" href="schedule.css">
    <title>Schedule</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmSchedule(requestId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "By confirming this schedule, you acknowledge that this can't be changed and you will have to abide by the schedule you selected as slots are limited for this date.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#008000',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirm it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('scheduleForm_' + requestId).submit();
                }
            })
        }
    </script>
</head>
<body>
<?php include 'header.php';?>

    <div class="schedule-container">
        <h2>Your Requests for Scheduling:</h2>
        <ul class="schedule-list">
            <?php if (empty($requests)): ?>
                <li><i>Your requests will appear here when you are asked to select a pickup date.</i></li>
            <?php else: ?>
                <?php foreach ($requests as $request): ?>
                    <?php if ($request['status'] == 'for scheduling'): ?>
                        <li>
                            <strong>Reference ID:</strong> <?= htmlspecialchars($request['reference_id']) ?> <br>
                            <strong>Document Types:</strong> <?= !empty($request['document_types']) ? htmlspecialchars(implode(", ", $request['document_types'])) : "N/A" ?> <br>
                            <form id="scheduleForm_<?= $request['id'] ?>" method="POST" action="schedule.php" class="schedule-form">
                                <label for="schedule_date">Pick a Schedule Date:</label>
                                <select name="schedule_date" required>
                                    <?php foreach ($available_dates as $date): ?>
                                        <option value="<?= htmlspecialchars($date['date']) ?>"><?= htmlspecialchars($date['date']) ?> (<?= htmlspecialchars($date['available_slots']) ?> slots available)</option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                <button type="button" onclick="confirmSchedule(<?= $request['id'] ?>)">Pick Date</button>
                            </form>
                        </li>
                    <?php elseif ($request['status'] == 'for pickup'): ?>
                        <li>
                            <strong>Reference ID:</strong> <?= htmlspecialchars($request['reference_id']) ?> <br>
                            <strong>Document Types:</strong> <?= !empty($request['document_types']) ? htmlspecialchars(implode(", ", $request['document_types'])) : "N/A" ?> <br>
                            <strong>Pickup Date:</strong> <?= htmlspecialchars($request['schedule_date']) ?> <br>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['schedule_date']) && isset($_POST['request_id'])) {
        $schedule_date = $_POST['schedule_date'];
        $request_id = $_POST['request_id'];

        $stmt = $mysqli->prepare("UPDATE requests SET status = 'for pickup', schedule_date = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $schedule_date, $request_id, $user_id);
        if ($stmt->execute()) {
            $stmt = $mysqli->prepare("UPDATE schedule_slots SET available_slots = available_slots - 1 WHERE date = ?");
            $stmt->bind_param("s", $schedule_date);
            $stmt->execute();

            header("Location: schedule.php");
            exit();
        } else {
            echo "An error occurred. Please try again.";
        }
        $stmt->close();
    }
    ob_end_flush();
    ?>
    <?php include 'footer.php';?>
</body>
</html>