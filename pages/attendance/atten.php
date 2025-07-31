<?php
$conn = new mysqli("localhost", "root", "", "attendance");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get data from POST
$date = $_POST['date'];
$subject_id = $_POST['subject_id'];
$student_ids = $_POST['student_ids'];
$present_ids = isset($_POST['present']) ? $_POST['present'] : [];

// Save attendance (sample table: attendance)
foreach ($student_ids as $student_id) {
    $status = in_array($student_id, $present_ids) ? 1 : 0;

    // Insert attendance record
    $stmt = $conn->prepare("INSERT INTO attendance (student_id, subject_id, date, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $student_id, $subject_id, $date, $status);
    $stmt->execute();
}

echo "<h3>Attendance Submitted Successfully!</h3>";

echo "<table border='1' cellpadding='10'>
        <tr>
            <th>Student ID</th>
            <th>Status</th>
        </tr>";

foreach ($student_ids as $student_id) {
    $status = in_array($student_id, $present_ids) ? "Present" : "Absent";
    echo "<tr>
            <td>$student_id</td>
            <td>$status</td>
          </tr>";
}

echo "</table>";
