<?php
$page_css = "
    <style>
        .dashboard-title {
            color: #4593e2ff !important;
            font-weight: 600;
            margin-bottom: 2rem;
        }

        .upload-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .upload-area {
            border: 3px dashed #4593e2ff;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            margin: 20px 0;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .upload-area:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            border-color: #357abd;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(69, 147, 226, 0.15);
        }

        .upload-area.dragover {
            border-color: #28a745;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        }

        .upload-icon {
            font-size: 3rem;
            color: #4593e2ff;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .upload-area:hover .upload-icon {
            transform: scale(1.1);
        }

        .file-input {
            display: none;
        }

        .upload-text {
            font-size: 1.2rem;
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .upload-subtext {
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .file-info {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            display: none;
        }

        .file-name {
            font-weight: 600;
            color: #495057;
        }

        .file-size {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4593e2ff 0%, #357abd 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(69, 147, 226, 0.3);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
            transform: translateY(-1px);
        }

        .requirements-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .requirements-header {
            background: linear-gradient(135deg, #4593e2ff 0%, #357abd 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0;
            font-weight: 600;
        }

        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .example-csv {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
            font-family: monospace;
            font-size: 0.85rem;
            overflow-x: auto;
        }
    </style>
";

// Initialize variables
$message = '';
$messageType = '';

// Handle CSV upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];

    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = "File upload error occurred.";
        $messageType = "danger";
    } elseif ($file['size'] > 5 * 1024 * 1024) { // 5MB limit
        $message = "File size too large. Maximum 5MB allowed.";
        $messageType = "danger";
    } elseif (!in_array(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)), ['csv']) || 
              !in_array(mime_content_type($file['tmp_name']), ['text/csv', 'application/vnd.ms-excel'])) {
        $message = "Only valid CSV files are allowed.";
        $messageType = "danger";
    } else {
        // Process CSV file using PHP's built-in functions
        $csvFile = $file['tmp_name'];
        $successCount = 0;
        $errorCount = 0;
        $errors = array();

        // Open CSV file (no plugins needed - built-in PHP function)
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            $row = 0;

            // Read CSV line by line (built-in PHP function)
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;

                // Skip header row
                if ($row == 1) {
                    // Validate header format
                    $expectedHeaders = ['student_id', 'subject_id', 'teacher_id', 'class_id', 'department_id', 'date', 'status'];
                    $actualHeaders = array_map('strtolower', array_map('trim', $data));
                    if ($actualHeaders !== $expectedHeaders) {
                        $message = "Invalid CSV format. Expected headers: " . implode(', ', $expectedHeaders);
                        $messageType = "danger";
                        break;
                    }
                    continue;
                }

                // Validate required fields
                if (count($data) < 7) {
                    $errors[] = "Row $row: Missing required fields";
                    $errorCount++;
                    continue;
                }

                $student_id = intval(trim($data[0]));
                $subject_id = intval(trim($data[1]));
                $teacher_id = intval(trim($data[2]));
                $class_id = intval(trim($data[3]));
                $department_id = intval(trim($data[4]));
                $date = trim($data[5]);
                $status = strtolower(trim($data[6]));

                // Validate data
                if (empty($student_id) || empty($subject_id) || empty($teacher_id) || empty($class_id) || empty($department_id) || empty($date) || empty($status)) {
                    $errors[] = "Row $row: All fields are required";
                    $errorCount++;
                    continue;
                }

                // Validate date format
                $dateObj = DateTime::createFromFormat('Y-m-d', $date);
                if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
                    $errors[] = "Row $row: Invalid date format. Use YYYY-MM-DD";
                    $errorCount++;
                    continue;
                }

                // Convert status to database format (1 for present, 0 for absent)
                $status_value = null;
                if ($status === 'present') {
                    $status_value = 1;
                } elseif ($status === 'absent') {
                    $status_value = 0;
                } else {
                    $errors[] = "Row $row: Invalid status. Use 'present' or 'absent'";
                    $errorCount++;
                    continue;
                }

                // Check if student exists
                $checkStudent = mysqli_prepare($conn, "SELECT Student_ID FROM student WHERE Student_ID = ?");
                mysqli_stmt_bind_param($checkStudent, "i", $student_id);
                mysqli_stmt_execute($checkStudent);
                $result = mysqli_stmt_get_result($checkStudent);

                if (mysqli_num_rows($result) == 0) {
                    $errors[] = "Row $row: Student ID '$student_id' not found in database";
                    $errorCount++;
                    mysqli_stmt_close($checkStudent);
                    continue;
                }
                mysqli_stmt_close($checkStudent);

                // Check if subject exists
                $checkSubject = mysqli_prepare($conn, "SELECT Subject_ID FROM subject WHERE Subject_ID = ?");
                mysqli_stmt_bind_param($checkSubject, "i", $subject_id);
                mysqli_stmt_execute($checkSubject);
                $result = mysqli_stmt_get_result($checkSubject);

                if (mysqli_num_rows($result) == 0) {
                    $errors[] = "Row $row: Subject ID '$subject_id' not found in database";
                    $errorCount++;
                    mysqli_stmt_close($checkSubject);
                    continue;
                }
                mysqli_stmt_close($checkSubject);

                // Check for duplicate attendance record
                $checkDuplicate = mysqli_prepare($conn, "SELECT * FROM attendance WHERE Student_ID = ? AND Subject_ID = ? AND Date_ = ?");
                mysqli_stmt_bind_param($checkDuplicate, "iis", $student_id, $subject_id, $date);
                mysqli_stmt_execute($checkDuplicate);
                $result = mysqli_stmt_get_result($checkDuplicate);

                if (mysqli_num_rows($result) > 0) {
                    // Update existing record
                    $updateStmt = mysqli_prepare($conn, "UPDATE attendance SET Status_ = ?, Teacher_ID = ?, Class_ID = ?, Department_id = ? WHERE Student_ID = ? AND Subject_ID = ? AND Date_ = ?");
                    mysqli_stmt_bind_param($updateStmt, "iiiiiis", $status_value, $teacher_id, $class_id, $department_id, $student_id, $subject_id, $date);

                    if (mysqli_stmt_execute($updateStmt)) {
                        $successCount++;
                    } else {
                        $errors[] = "Row $row: Database update error - " . mysqli_error($conn);
                        $errorCount++;
                    }
                    mysqli_stmt_close($updateStmt);
                } else {
                    // Insert new record
                    $insertStmt = mysqli_prepare($conn, "INSERT INTO attendance (Student_ID, Subject_ID, Teacher_ID, Class_ID, Department_id, Date_, Status_) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($insertStmt, "iiiiisi", $student_id, $subject_id, $teacher_id, $class_id, $department_id, $date, $status_value);

                    if (mysqli_stmt_execute($insertStmt)) {
                        $successCount++;
                    } else {
                        $errors[] = "Row $row: Database insert error - " . mysqli_error($conn);
                        $errorCount++;
                    }
                    mysqli_stmt_close($insertStmt);
                }

                mysqli_stmt_close($checkDuplicate);
            }
            fclose($handle);

            // Set success/error message
            if (!$headerError) { // Skip if a header error was detected
                if ($successCount > 0 && $errorCount == 0) {
                    $message = "Successfully imported $successCount attendance records.";
                    $messageType = "success";
                } elseif ($successCount > 0) {
                    $message = "Imported $successCount records with $errorCount errors.";
                    $messageType = "warning";
                } else {
                    $message = "Import failed. No records were imported.";
                    $messageType = "danger";
                }
            }
        } else {
            $message = "Error reading CSV file.";
            $messageType = "danger";
        }
    }
}
?>

<div class="upload-container">
    <h2 class='dashboard-title'>
        <i class="fas fa-cloud-upload-alt"></i> Upload Attendance
    </h2>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <strong><?php echo $messageType === 'success' ? 'Success!' : ($messageType === 'warning' ? 'Warning!' : 'Error!'); ?></strong>
            <?php echo htmlspecialchars($message); ?>

            <?php if (!empty($errors)): ?>
                <hr>
                <strong>Details:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach (array_slice($errors, 0, 10) as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                    <?php if (count($errors) > 10): ?>
                        <li><em>... and <?php echo count($errors) - 10; ?> more errors</em></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" enctype="multipart/form-data" id="uploadForm">
                        <div class="upload-area" onclick="document.getElementById('csv_file').click()">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <div class="upload-text">Click to select CSV file</div>
                            <div class="upload-subtext">or drag and drop your file here</div>
                            <small class="text-muted">Maximum file size: 5MB | Supported format: CSV</small>
                        </div>

                        <input type="file" id="csv_file" name="csv_file" class="file-input" accept=".csv" required>

                        <div class="file-info" id="fileInfo">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="file-name" id="fileName"></div>
                                    <div class="file-size" id="fileSize"></div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
                                <i class="fas fa-upload"></i> Upload and Process
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="downloadTemplate()">
                                <i class="fas fa-download"></i> Download Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="requirements-card">
                <div class="requirements-header">
                    <i class="fas fa-info-circle"></i> CSV Format Requirements
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Required Columns (in order):</strong>
                        <ol class="mt-2 mb-0">
                            <li><code>student_id</code> (number)</li>
                            <li><code>subject_id</code> (number)</li>
                            <li><code>teacher_id</code> (number)</li>
                            <li><code>class_id</code> (number)</li>
                            <li><code>department_id</code> (number)</li>
                            <li><code>date</code> (YYYY-MM-DD)</li>
                            <li><code>status</code> (present/absent)</li>
                        </ol>
                    </div>

                    <div class="mb-3">
                        <strong>Example CSV:</strong>
                        <div class="example-csv mt-2">
                            student_id,subject_id,teacher_id,class_id,department_id,date,status
                            1,1,1,1,1,2025-07-16,present
                            2,1,1,1,1,2025-07-16,absent
                            3,1,1,1,1,2025-07-16,present
                        </div>
                    </div>

                    <div class="alert alert-info py-2 px-3 mb-3">
                        <small><i class="fas fa-lightbulb"></i> <strong>Note:</strong> IDs must exist in database!</small>
                    </div>

                    <div class="alert alert-warning py-2 px-3 mb-0">
                        <small><i class="fas fa-exclamation-triangle"></i> <strong>Status:</strong> Use 'present' or 'absent' only</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // File input change event
    document.getElementById('csv_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileInfo = document.getElementById('fileInfo');
        const uploadBtn = document.getElementById('uploadBtn');

        if (file) {
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = `Size: ${(file.size / 1024 / 1024).toFixed(2)} MB`;
            fileInfo.style.display = 'block';
            uploadBtn.disabled = false;

            // Update upload area
            const uploadArea = document.querySelector('.upload-area');
            uploadArea.querySelector('.upload-text').textContent = 'File selected: ' + file.name;
            uploadArea.querySelector('.upload-subtext').textContent = 'Click to change file or drag a new one';
        }
    });

    // Clear file selection
    function clearFile() {
        document.getElementById('csv_file').value = '';
        document.getElementById('fileInfo').style.display = 'none';
        document.getElementById('uploadBtn').disabled = true;

        // Reset upload area
        const uploadArea = document.querySelector('.upload-area');
        uploadArea.querySelector('.upload-text').textContent = 'Click to select CSV file';
        uploadArea.querySelector('.upload-subtext').textContent = 'or drag and drop your file here';
    }

    // Download CSV template
    function downloadTemplate() {
        const csvContent = "student_id,subject_id,teacher_id,class_id,department_id,date,status\n1,1,1,1,1,2025-07-16,present\n2,1,1,1,1,2025-07-16,absent\n3,1,1,1,1,2025-07-16,present";

        const blob = new Blob([csvContent], {
            type: 'text/csv;charset=utf-8;'
        });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('hidden', '');
        a.setAttribute('href', url);
        a.setAttribute('download', 'attendance_template.csv');
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    // Drag and drop functionality
    const uploadArea = document.querySelector('.upload-area');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        uploadArea.classList.add('dragover');
    }

    function unhighlight(e) {
        uploadArea.classList.remove('dragover');
    }

    uploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            const file = files[0];
            if (file.type === 'text/csv' || file.name.endsWith('.csv')) {
                document.getElementById('csv_file').files = files;
                const changeEvent = new Event('change', {
                    bubbles: true
                });
                document.getElementById('csv_file').dispatchEvent(changeEvent);
            } else {
                alert('Please select a CSV file only.');
            }
        }
    }

    // Form submission with loading state
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const uploadBtn = document.getElementById('uploadBtn');
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        uploadBtn.disabled = true;
    });
</script>
