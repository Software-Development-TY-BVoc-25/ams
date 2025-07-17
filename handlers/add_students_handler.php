<?php
// CSV Upload Handler for Student Data

// Include database configuration
require_once dirname(__DIR__) . '/config.php';

// Set response header for JSON
header('Content-Type: application/json');

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'data' => []
];

try {
    // Check if file was uploaded
    if (!isset($_FILES['csvFile']) || $_FILES['csvFile']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No file uploaded or upload error occurred');
    }

    $uploadedFile = $_FILES['csvFile'];

    // Basic validation
    $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
    if ($fileExtension !== 'csv') {
        throw new Exception('Invalid file type. Only CSV files are allowed.');
    }

    // Create uploads directory if it doesn't exist
    $uploadDir = dirname(__DIR__) . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    $fileName = 'attendance_' . date('Y-m-d_H-i-s') . '.csv';
    $uploadPath = $uploadDir . $fileName;

    // Move uploaded file
    if (move_uploaded_file($uploadedFile['tmp_name'], $uploadPath)) {

        // Process CSV and insert into database
        $csvFile = fopen($uploadPath, 'r');
        if (!$csvFile) {
            // Delete file if can't read it
            if (file_exists($uploadPath)) {
                unlink($uploadPath);
            }
            throw new Exception('Could not read uploaded CSV file');
        }

        // Read header row
        $headers = fgetcsv($csvFile);
        if (!$headers) {
            fclose($csvFile);
            // Delete file on header read error
            if (file_exists($uploadPath)) {
                unlink($uploadPath);
            }
            throw new Exception('Could not read CSV headers');
        }

        // Expected CSV columns for student table
        $expectedHeaders = ['Student_ID', 'Student_Name', 'Student_Rollno', 'Class_ID', 'Department_ID', 'Subject_ID'];

        // Validate headers
        $headerDiff = array_diff($expectedHeaders, $headers);
        if (!empty($headerDiff)) {
            fclose($csvFile);
            // Delete file on validation error
            if (file_exists($uploadPath)) {
                unlink($uploadPath);
            }
            throw new Exception('Missing required columns: ' . implode(', ', $headerDiff));
        }

        // Process CSV data
        $processedRows = 0;
        $insertedRows = 0;
        $errors = [];

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO student (Student_ID, Student_Name, Student_Rollno, Class_ID, Department_ID, Subject_ID) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE Student_Name = VALUES(Student_Name), Student_Rollno = VALUES(Student_Rollno), Class_ID = VALUES(Class_ID), Department_ID = VALUES(Department_ID), Subject_ID = VALUES(Subject_ID)");

        if (!$stmt) {
            fclose($csvFile);
            // Delete file on database error
            if (file_exists($uploadPath)) {
                unlink($uploadPath);
            }
            throw new Exception('Database prepare failed: ' . $conn->error);
        }

        while (($row = fgetcsv($csvFile)) !== false) {
            $processedRows++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Create associative array with headers
            $rowData = array_combine($headers, $row);

            // Validate row data
            $rowErrors = [];

            // Validate required fields
            foreach ($expectedHeaders as $header) {
                if (!isset($rowData[$header]) || trim($rowData[$header]) === '') {
                    $rowErrors[] = "Missing {$header}";
                }
            }

            // Validate numeric fields
            if (isset($rowData['Student_ID']) && !is_numeric($rowData['Student_ID'])) {
                $rowErrors[] = "Student_ID must be numeric";
            }
            if (isset($rowData['Student_Rollno']) && !is_numeric($rowData['Student_Rollno'])) {
                $rowErrors[] = "Student_Rollno must be numeric";
            }
            if (isset($rowData['Class_ID']) && !is_numeric($rowData['Class_ID'])) {
                $rowErrors[] = "Class_ID must be numeric";
            }
            if (isset($rowData['Department_ID']) && !is_numeric($rowData['Department_ID'])) {
                $rowErrors[] = "Department_ID must be numeric";
            }
            if (isset($rowData['Subject_ID']) && !is_numeric($rowData['Subject_ID'])) {
                $rowErrors[] = "Subject_ID must be numeric";
            }

            if (!empty($rowErrors)) {
                $errors[] = "Row {$processedRows}: " . implode(', ', $rowErrors);
                continue;
            }

            // Insert into database
            $stmt->bind_param(
                "isiiii",
                intval($rowData['Student_ID']),
                $rowData['Student_Name'],
                intval($rowData['Student_Rollno']),
                intval($rowData['Class_ID']),
                intval($rowData['Department_ID']),
                intval($rowData['Subject_ID'])
            );

            if ($stmt->execute()) {
                $insertedRows++;
            } else {
                $errors[] = "Row {$processedRows}: Database error - " . $stmt->error;
            }
        }

        fclose($csvFile);
        $stmt->close();

        // Delete the uploaded file after processing
        if (file_exists($uploadPath)) {
            unlink($uploadPath);
        }

        // Prepare response
        if (!empty($errors)) {
            $response['success'] = false;
            $response['message'] = 'Upload completed with errors';
            $response['data'] = [
                'errors' => $errors,
                'total_rows' => $processedRows,
                'inserted_rows' => $insertedRows,
                'error_count' => count($errors)
            ];
        } else {
            $response['success'] = true;
            $response['message'] = 'Upload successful - All student records processed';
            $response['data'] = [
                'filename' => $fileName,
                'upload_date' => date('Y-m-d H:i:s'),
                'file_size' => $uploadedFile['size'],
                'total_rows' => $processedRows,
                'inserted_rows' => $insertedRows
            ];
        }
    } else {
        throw new Exception('Failed to save file');
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

// Return JSON response
echo json_encode($response);
