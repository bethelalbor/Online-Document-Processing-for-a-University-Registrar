<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: signin_admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date']) && isset($_POST['available_slots']) && isset($_POST['statuses']) && isset($_POST['document_types']) && isset($_POST['courses'])) {
    $date = $_POST['date'];
    $available_slots = $_POST['available_slots'];
    $statuses = $_POST['statuses'];
    $document_types = $_POST['document_types'];
    $courses = $_POST['courses'];

    
    $statuses_json = json_encode($statuses);
    $document_types_json = json_encode($document_types);
    $courses_json = json_encode($courses);

    $stmt = $mysqli->prepare("INSERT INTO schedule_slots (date, available_slots, statuses, document_types, courses) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE available_slots = ?, statuses = ?, document_types = ?, courses = ?");
    $stmt->bind_param("sissssiss", $date, $available_slots, $statuses_json, $document_types_json, $courses_json, $available_slots, $statuses_json, $document_types_json, $courses_json);

    if ($stmt->execute()) {
        $success = "Schedule updated successfully.";
    } else {
        $error = "An error occurred. Please try again.";
    }

    $stmt->close();
}


$dates_query = "SELECT 
    date, 
    available_slots, 
    (SELECT COUNT(*) FROM requests 
     WHERE schedule_date = date 
     AND status = 'for_pickup') AS taken_slots,
    statuses, 
    document_types,
    courses
FROM schedule_slots";
$dates_result = $mysqli->query($dates_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="../3_image/PTC-Logo.png">
    <title>Calendar Schedule</title>
    <link rel="stylesheet" href="calendar_schedule.css">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusCheckboxes = document.querySelectorAll('.status-checkbox');
            const documentTypeCheckboxes = document.querySelectorAll('.document-type-checkbox');
            const courseCheckboxes = document.querySelectorAll('.course-checkbox');

            updateInputBox('status');
            updateInputBox('document-type');
            updateInputBox('course');

            document.querySelector('#select_all_statuses').addEventListener('change', (event) => {
                toggleCheckboxes('status-checkbox', event.target);
                updateInputBox('status');
            });

            document.querySelector('#select_all_document_types').addEventListener('change', (event) => {
                toggleCheckboxes('document-type-checkbox', event.target);
                updateInputBox('document-type');
            });

            document.querySelector('#select_all_courses').addEventListener('change', (event) => {
                toggleCheckboxes('course-checkbox', event.target);
                updateInputBox('course');
            });

            statusCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => handleCheckboxChange('status'));
            });

            documentTypeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => handleCheckboxChange('document-type'));
            });

            courseCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => handleCheckboxChange('course'));
            });
        });

        function toggleCheckboxes(className, sourceCheckbox) {
            const checkboxes = document.querySelectorAll(`.${className}`);
            checkboxes.forEach(checkbox => checkbox.checked = sourceCheckbox.checked);
        }

        function handleCheckboxChange(type) {
            const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
            const allSelected = Array.from(checkboxes).every(checkbox => checkbox.checked);
            document.querySelector(`#select_all_${type}s`).checked = allSelected;
            updateInputBox(type);
        }

        function updateInputBox(type) {
            const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
            const allSelected = Array.from(checkboxes).every(checkbox => checkbox.checked);
            const selectedValues = Array.from(checkboxes).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value);

            if (allSelected) {
                document.querySelector(`#${type}_input`).value = 'All';
            } else {
                document.querySelector(`#${type}_input`).value = selectedValues.join(', ');
            }
        }
    </script>
</head>
<body>
    <?php include 'header.php'; ?>

    <?php if (isset($success)) { echo "<p style='color:green;'>$success</p>"; } ?>
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <h2>Set Available Slots</h2>
    <form method="POST" action="calendar_schedule.php">
        <label for="date">Date:</label>
        <input type="date" name="date" required>
        <label for="available_slots">Number of Slots:</label>
        <input type="number" name="available_slots" required>
        
        <div class="checkbox-container">
            <label for="statuses">Statuses:</label>
            <input type="text" id="status_input" readonly value="All">
            <div class="checkbox-group">
                <div><input type="checkbox" id="select_all_statuses" checked onclick="toggleCheckboxes('status-checkbox', this); updateInputBox('status');"> Select All</div>
                <div><input type="checkbox" name="statuses[]" value="enrolled" class="status-checkbox" checked> Enrolled</div>
                <div><input type="checkbox" name="statuses[]" value="graduating" class="status-checkbox" checked> Graduating</div>
                <div><input type="checkbox" name="statuses[]" value="graduated" class="status-checkbox" checked> Graduated</div>
                <div><input type="checkbox" name="statuses[]" value="dropped" class="status-checkbox" checked> Dropped</div>
            </div>
        </div>
        
        <div class="checkbox-container">
            <label for="document_types">Document Types:</label>
            <input type="text" id="document_type_input" readonly value="All">
            <div class="checkbox-group">
                <div><input type="checkbox" id="select_all_document_types" checked onclick="toggleCheckboxes('document-type-checkbox', this); updateInputBox('document-type');"> Select All</div>
                <div><input type="checkbox" name="document_types[]" value="Transcript of Records" class="document-type-checkbox" checked> Transcript of Records</div>
                <div><input type="checkbox" name="document_types[]" value="Transfer Credentials" class="document-type-checkbox" checked> Transfer Credentials (TOR, Honorable Dismissal, Certificate of Good Moral Character, Certificate of Grades, Certificate of Transfer)</div>
                <div><input type="checkbox" name="document_types[]" value="Diploma" class="document-type-checkbox" checked> Diploma</div>
                <div><input type="checkbox" name="document_types[]" value="Certificate of Transfer" class="document-type-checkbox" checked> Certificate of Transfer</div>
                <div><input type="checkbox" name="document_types[]" value="Certificate of Enrollment/Registration" class="document-type-checkbox" checked> Certificate of Enrollment/Registration</div>
                <div><input type="checkbox" name="document_types[]" value="Certificate of Grade" class="document-type-checkbox" checked> Certificate of Grade</div>
                <div><input type="checkbox" name="document_types[]" value="Certificate of Honorable Dismissal" class="document-type-checkbox" checked> Certificate of Honorable Dismissal</div>
                <div><input type="checkbox" name="document_types[]" value="Certificate of Graduation" class="document-type-checkbox" checked> Certificate of Graduation</div>
                <div><input type="checkbox" name="document_types[]" value="Certificate of General Weighted Average" class="document-type-checkbox" checked> Certificate of General Weighted Average</div>
                <div><input type="checkbox" name="document_types[]" value="Certificate of Study Load" class="document-type-checkbox" checked> Certificate of Study Load</div>
                <div><input type="checkbox" name="document_types[]" value="Certification-Authentication-Verification (CAV) of Grades of TOR/Diploma (CTC)" class="document-type-checkbox" checked> Certification-Authentication-Verification (CAV) of Grades of TOR/Diploma (CTC)</div>
                <div><input type="checkbox" name="document_types[]" value="Certified True Copy of Transcript of Record" class="document-type-checkbox" checked> Certified True Copy of Transcript of Record</div>
                <div><input type="checkbox" name="document_types[]" value="Certified True Copy of Diploma" class="document-type-checkbox" checked> Certified True Copy of Diploma</div>
                <div><input type="checkbox" name="document_types[]" value="Certified True Copy of Certificate of Good Moral Character" class="document-type-checkbox" checked> Certified True Copy of Certificate of Good Moral Character</div>
                <div><input type="checkbox" name="document_types[]" value="Certified True Copy of Certificate of Graduation" class="document-type-checkbox" checked> Certified True Copy of Certificate of Graduation</div>
                <div><input type="checkbox" name="document_types[]" value="Certified True Copy of Certificate of General Weighted Average" class="document-type-checkbox" checked> Certified True Copy of Certificate of General Weighted Average</div>
                <div><input type="checkbox" name="document_types[]" value="Form 138 A (CTC)" class="document-type-checkbox" checked> Form 138 A (CTC)</div>
                <div><input type="checkbox" name="document_types[]" value="Form 137" class="document-type-checkbox" checked> Form 137</div>
            </div>
        </div>
        
        <div class="checkbox-container">
            <label for="courses">Courses:</label>
            <input type="text" id="course_input" readonly value="All">
            <div class="checkbox-group">
                <div><input type="checkbox" id="select_all_courses" checked onclick="toggleCheckboxes('course-checkbox', this); updateInputBox('course');"> Select All</div>
                <div><input type="checkbox" name="courses[]" value="BSOA" class="course-checkbox" checked> Bachelor of Science in Office Administration</div>
                <div><input type="checkbox" name="courses[]" value="BSIT" class="course-checkbox" checked> Bachelor of Science in Information Technology</div>
                <div><input type="checkbox" name="courses[]" value="COA" class="course-checkbox" checked> Certificate in Office Administration</div>
                <div><input type="checkbox" name="courses[]" value="CCS" class="course-checkbox" checked> Certificate in Computer Science</div>
                <div><input type="checkbox" name="courses[]" value="CHRM" class="course-checkbox" checked> Certificate in Hotel and Restaurant Management</div>
                <div><input type="checkbox" name="courses[]" value="ABA" class="course-checkbox" checked> Associate in Business Administration</div>
                <div><input type="checkbox" name="courses[]" value="AAIS" class="course-checkbox" checked> Associate in Accounting Information System</div>
            </div>
        </div>

        <button type="submit">Create Schedule</button>
    </form>

    <h3>Existing Slots</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Remaining Slots</th>
                <!-- <th>Taken Slots</th> -->
                <th>Statuses</th>
                <th>Document Types</th>
                <th>Courses</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $dates_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['available_slots'] - $row['taken_slots']) ?></td>
                    <!-- <td><?= htmlspecialchars($row['taken_slots']) ?></td> -->
                    <td><?= htmlspecialchars(formatSelection(json_decode($row['statuses'], true), 4)) ?></td>
                    <td><?= htmlspecialchars(formatSelection(json_decode($row['document_types'], true), 18)) ?></td>
                    <td><?= htmlspecialchars(formatSelection(json_decode($row['courses'], true), 7)) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
function formatSelection($array, $totalCount) {
    if (is_array($array) && count($array) > 0) {
        return count($array) === $totalCount ? 'All' : implode(', ', $array);
    }
    return 'All';
}
?>
