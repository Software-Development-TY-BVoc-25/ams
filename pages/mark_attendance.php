<?php

$cookie = [
    'course' => $_COOKIE['course'] ?? '',
    'division' => isset($_COOKIE['division']) ? $_COOKIE['division'] : '',
    'semester' => $_COOKIE['semester'] ?? '',
    'subject' => $_COOKIE['subject'] ?? '',
    'year' => $_COOKIE['year'] ?? '',
    'level' => $_COOKIE['level'] ?? ''
];

?>

<div class="container">

    <ul>
        <?php foreach ($cookie as $key => $value): ?>
            <li class="">
                <?php echo ucfirst($key) . ': ' . htmlspecialchars($value); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php
    $semester = $cookie['semester'] ?? '';
    $year = $cookie['year'] ?? '';
    $level = $cookie['level'] ?? '';

    // Use empty string check for Division
    $query = ($cookie['division'] === '' || $cookie['division'] === null)
        ? "SELECT * FROM class WHERE Year_Level = ? AND Course_Code = ? AND (Division = '' OR Division IS NULL)"
        : "SELECT * FROM class WHERE Year_Level = ? AND Course_Code = ? AND Division = ?";

    $stmt = $conn->prepare($query);
    if ($cookie['division'] === '' || $cookie['division'] === null) {
        $stmt->bind_param("ss", $level, $cookie['course']);
    } else {
        $stmt->bind_param("sss", $level, $cookie['course'], $cookie['division']);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo '<div class="text-success">Class found: ' . htmlspecialchars($row['Class_ID']) . '</div>';
        $classId = $row['Class_ID'];
    } else {
        echo '<div class="text-danger">No class found for the selected filters.</div>';
        $classId = null;
    }

    if ($classId && $semester && $year) {
        $query = "SELECT se.*, s.Student_Name
                  FROM student_enrollment se
                  JOIN student s ON se.Student_Rollno = s.Student_Rollno
                  WHERE se.Class_ID = ? AND se.Semester = ? AND se.Year_Label = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $classId, $semester, $year);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo '<div class="student-enrollment">';
            echo '<span class="px-2">Student ID: ' . htmlspecialchars($row['Student_Rollno']) . '</span>';
            echo '<span class="px-2">Name: ' . htmlspecialchars($row['Student_Name']) . '</span>';
            echo '<span class="px-2">Enrollment ID: ' . htmlspecialchars($row['Enrollment_ID']) . '</span>';
            echo '</div>';
        }
    } else {
        echo '<div class="text-warning">Please select all required filters.</div>';
    }
    ?>

</div>
