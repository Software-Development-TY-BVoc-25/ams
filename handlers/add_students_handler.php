<?php

include "../config.php";
header('Content-Type: application/json');

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'data' => []
];

$uploadDir = dirname(__DIR__) . '/uploads/';

// this function validates the uploaded CSV file to check for required headers and data types
function validateFile($filePath) {
    $expectedHeaders = ['Student_ID', 'Student_Name', 'Student_Rollno', 'Class_ID', 'Department_ID', 'Subject_ID'];
    $errors = [];
    $rowCount = 0;
    $validRows = 0;

    if (!file_exists($filePath)) {
        return ['errors' => ['File not found'], 'validRows' => 0, 'rowCount' => 0];
    }

    $handle = fopen($filePath, 'r');
    if (!$handle) {
        return ['errors' => ['Unable to open file'], 'validRows' => 0, 'rowCount' => 0];
    }

    $headers = fgetcsv($handle);
    if (!$headers) {
        fclose($handle);
        return ['errors' => ['Unable to read headers'], 'validRows' => 0, 'rowCount' => 0];
    }

    // Check for missing headers
    $missing = array_diff($expectedHeaders, $headers);
    if (!empty($missing)) {
        fclose($handle);
        return ['errors' => ['Missing columns: ' . implode(', ', $missing)], 'validRows' => 0, 'rowCount' => 0];
    }

    // Map header positions
    $headerMap = array_flip($headers);

    // Validate each row
    while (($row = fgetcsv($handle)) !== false) {
        $rowCount++;
        if (empty(array_filter($row))) continue; // skip empty rows
        $rowErrors = [];
        // Check required fields
        foreach ($expectedHeaders as $header) {
            $idx = $headerMap[$header];
            if (!isset($row[$idx]) || trim($row[$idx]) === '') {
                $rowErrors[] = "$header missing";
            }
        }
        // Check numeric fields
        foreach (['Student_ID', 'Student_Rollno', 'Class_ID', 'Department_ID', 'Subject_ID'] as $header) {
            $idx = $headerMap[$header];
            if (isset($row[$idx]) && trim($row[$idx]) !== '' && !is_numeric($row[$idx])) {
                $rowErrors[] = "$header must be numeric";
            }
        }
        if (!empty($rowErrors)) {
            $errors[] = "Row $rowCount: " . implode(', ', $rowErrors);
        } else {
            $validRows++;
        }
    }
    fclose($handle);
    return ['errors' => $errors, 'validRows' => $validRows, 'rowCount' => $rowCount];
}

//prepare the SQL statement
$stmt = $conn->prepare(
    "INSERT INTO student (Student_ID, Student_Name, Student_Rollno, Class_ID, Department_ID, Subject_ID)
     VALUES (?, ?, ?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE
        Student_Name = VALUES(Student_Name),
        Student_Rollno = VALUES(Student_Rollno),
        Class_ID = VALUES(Class_ID),
        Department_ID = VALUES(Department_ID),
        Subject_ID = VALUES(Subject_ID)"
);



//get the file from post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csvFile'])) {
        $file = $_FILES['csvFile'];

        //if uploads dir is present else create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        //move the uploaded file to the uploads directory
        $filePath = $uploadDir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Validate the file
            $validation = validateFile($filePath);
            if (!empty($validation['errors'])) {
                $response['message'] = 'File validation failed: ' . implode('; ', $validation['errors']);
            } else {

                // Open the file again for inserting
                $handle = fopen($filePath, 'r');
                $headers = fgetcsv($handle); // skip header row
                $insertedRows = 0;
                $rowCount = 0;

                while (($row = fgetcsv($handle)) !== false) {
                    $rowCount++;
                    if (empty(array_filter($row))) continue; // skip empty rows

                    // Map header positions
                    $headerMap = array_flip($headers);
                    $rowData = [];
                    foreach (['Student_ID', 'Student_Name', 'Student_Rollno', 'Class_ID', 'Department_ID', 'Subject_ID'] as $header) {
                        $rowData[] = $row[$headerMap[$header]];
                    }

                    // Bind and execute
                    $stmt->bind_param(
                        "isiiii",
                        $rowData[0], // Student_ID
                        $rowData[1], // Student_Name
                        $rowData[2], // Student_Rollno
                        $rowData[3], // Class_ID
                        $rowData[4], // Department_ID
                        $rowData[5]  // Subject_ID
                    );
                    if ($stmt->execute()) {
                        $insertedRows++;
                    }
                }
                fclose($handle);

                $response['success'] = true;
                $response['message'] = "Upload successful. $insertedRows students added/updated.";
                $response['data'] = [
                    'inserted_rows' => $insertedRows,
                    'total_rows' => $rowCount
                ];
            }
        } else {
            $response['message'] = 'Failed to move uploaded file';
        }
    } else {
        $response['message'] = 'No file uploaded';
    }
}


echo json_encode($response);
