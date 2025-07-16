<?php
// Simple CSV Upload Handler

// Set response header for JSON
header('Content-Type: application/json');

// Initialize response
$response = [
    'success' => false,
    'message' => ''
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
    $fileName = 'attendance_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.csv';
    $uploadPath = $uploadDir . $fileName;

    // Move uploaded file
    if (move_uploaded_file($uploadedFile['tmp_name'], $uploadPath)) {
        $response['success'] = true;
        $response['message'] = 'Upload successful';
        $response['data'] = [
            'filename' => $fileName,
            'upload_date' => date('Y-m-d H:i:s'),
            'file_size' => $uploadedFile['size']
        ];
    } else {
        throw new Exception('Failed to save file');
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

// Return JSON response
echo json_encode($response);
