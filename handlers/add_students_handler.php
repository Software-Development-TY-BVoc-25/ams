<?php

include "../config.php";
header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => [],
    'data' => [
        'createdEnrollments' => [],
        'existingEnrollments' => [],
        'insertedStudents' => [],
        'skippedRows' => [],
        'otherErrors' => []
    ]
];

function ensureStudentExists($name, $rollno, $conn) {
    try {
        $query = "SELECT * FROM student WHERE Student_Rollno = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return ['exists' => false, 'name' => $name];
        }
        $stmt->bind_param("i", $rollno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ['exists' => true, 'name' => $row['Student_Name']];
        } else {
            $insertQuery = "INSERT INTO student (Student_Rollno, Student_Name) VALUES (?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            if (!$insertStmt) {
                error_log("Prepare failed: " . $conn->error);
                return ['exists' => false, 'name' => $name];
            }
            $insertStmt->bind_param("is", $rollno, $name);
            $insertStmt->execute();
            return ensureStudentExists($name, $rollno, $conn);
        }
    } catch (Exception $e) {
        error_log("Error checking/inserting student: " . $e->getMessage());
        return ['exists' => false, 'name' => $name];
    }
}

function db_query($conn, $sql, $types = "", $params = []) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    if ($types && $params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {

    $file = $_FILES['csvFile'];
    $open = fopen($file['tmp_name'], "r");
    if (!$open) {
        $response['message'][] = "Failed to open uploaded file.";
        echo json_encode($response);
        exit;
    }
    $headers = fgetcsv($open, 1000, ",");
    if (!$headers || !is_array($headers)) {
        $response['message'][] = "CSV header missing or invalid.";
        fclose($open);
        echo json_encode($response);
        exit;
    }

    $createdEnrollments = [];
    $existingEnrollments = [];
    $insertedStudents = [];
    $skippedRows = [];
    $otherErrors = [];
    $seenRollnos = [];

    while (($tmp = fgetcsv($open, 1000, ",")) !== FALSE) {
        // Skip empty rows
        if (count(array_filter($tmp)) === 0) continue;
        if (count($tmp) !== count($headers)) {
            $skippedRows[] = [
                'error' => 'Row skipped due to header mismatch',
                'row' => $tmp
            ];
            continue;
        }
        $row = array_combine($headers, $tmp);

        $csvName = $row['Student_Name'] ?? '';
        $csvRollno = $row['Student_Rollno'] ?? '';
        $csvDepartment = $row['Department_Name'] ?? '';
        $csvYear = $row['Year_Level'] ?? '';
        $csvDivision = $row['Division'] ?? '';
        $csvSemester = $row['Semester'] ?? '';
        $csvAcademicYear = $row['Academic_Year'] ?? '';

        if (!$csvName || !$csvRollno || !$csvDepartment || !$csvYear || !$csvSemester || !$csvAcademicYear) {
            $skippedRows[] = [
                'error' => 'Row skipped due to missing required data',
                'row' => $tmp
            ];
            continue;
        }

        $result = ensureStudentExists($csvName, (int)$csvRollno, $conn);

        // Get Department_ID
        $deptStmt = db_query($conn, "SELECT Department_ID FROM department WHERE Department_Name = ?", "s", [$csvDepartment]);
        $deptRow = $deptStmt ? $deptStmt->get_result()->fetch_assoc() : null;
        if (!$deptRow) {
            $otherErrors[] = "Department not found: $csvDepartment";
            continue;
        }
        $departmentId = $deptRow['Department_ID'];

        // Get Class_ID
        if (empty($csvDivision)) {
            $classStmt = db_query($conn, "SELECT Class_ID FROM class WHERE Year_Level = ? AND Division IS NULL AND Department_ID = ?", "si", [$csvYear, $departmentId]);
        } else {
            $classStmt = db_query($conn, "SELECT Class_ID FROM class WHERE Year_Level = ? AND Division = ? AND Department_ID = ?", "ssi", [$csvYear, $csvDivision, $departmentId]);
        }
        $classRow = $classStmt ? $classStmt->get_result()->fetch_assoc() : null;
        if (!$classRow) {
            $otherErrors[] = "Class not found for: $csvYear, $csvDivision, $csvDepartment";
            continue;
        }
        $classId = $classRow['Class_ID'];

        // Check if enrollment already exists
        $enrollCheckStmt = db_query(
            $conn,
            "SELECT Enrollment_ID FROM student_enrollment WHERE Student_Rollno = ? AND Class_ID = ? AND Semester = ? AND Year_Label = ?",
            "iiis",
            [(int)$csvRollno, $classId, $csvSemester, $csvAcademicYear]
        );
        $enrollExists = $enrollCheckStmt && $enrollCheckStmt->get_result()->num_rows > 0;

        $rowData = [
            'Student_Rollno' => $csvRollno,
            'Student_Name' => $csvName,
            'Department_Name' => $csvDepartment,
            'Year_Level' => $csvYear,
            'Division' => $csvDivision,
            'Semester' => $csvSemester,
            'Academic_Year' => $csvAcademicYear
        ];

        if (!$enrollExists) {
            $enrollInsertStmt = db_query(
                $conn,
                "INSERT INTO student_enrollment (Student_Rollno, Class_ID, Semester, Year_Label) VALUES (?, ?, ?, ?)",
                "iiis",
                [(int)$csvRollno, $classId, $csvSemester, $csvAcademicYear]
            );
            $createdEnrollments[] = $rowData;
        } else {
            $existingEnrollments[] = $rowData;
        }

        if (!$result['exists']) {
            $insertedStudents[] = $rowData;
        }
    }
    fclose($open);

    // Grouped messages and corresponding data
    $response['message'] = [];
    $response['data'] = [];

    if (!empty($createdEnrollments)) {
        $response['message'][] = "Enrollment created for: " . count($createdEnrollments);
        $response['data'][] = [
            'type' => 'createdEnrollments',
            'rows' => $createdEnrollments
        ];
    }
    if (!empty($existingEnrollments)) {
        $response['message'][] = "Enrollment already exists for: " . count($existingEnrollments);
        $response['data'][] = [
            'type' => 'existingEnrollments',
            'rows' => $existingEnrollments
        ];
    }
    if (!empty($insertedStudents)) {
        $response['message'][] = "Inserted students: " . count($insertedStudents);
        $response['data'][] = [
            'type' => 'insertedStudents',
            'rows' => $insertedStudents
        ];
    }
    if (!empty($skippedRows)) {
        foreach ($skippedRows as $skip) {
            $response['message'][] = $skip['error'];
            $response['data'][] = [
                'type' => 'skippedRows',
                'rows' => [$skip['row']]
            ];
        }
    }
    if (!empty($otherErrors)) {
        $response['message'][] = "Other errors: " . count($otherErrors);
        $response['data'][] = [
            'type' => 'otherErrors',
            'rows' => $otherErrors
        ];
    }
    if (empty($response['message'])) {
        $response['message'][] = "No changes made.";
        $response['data'][] = [
            'type' => 'none',
            'rows' => []
        ];
    }

    $response['success'] = true;
    $response['file'] = $file['name'];
    $response['uploaded'] = true;
} else {
    $response['message'][] = 'No file received or invalid request method.';
}

echo json_encode($response);
