<?php
$page_css =
    "<style>
        .upload-area {
            border: 2px dashed #28a745;
            border-radius: 8px;
            padding: 3rem;
            text-align: center;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .upload-area:hover {
            background-color: #e8f5e8;
            border-color: #1e7e34;
        }
        .upload-area.dragover {
            background-color: #d4edda;
            border-color: #1e7e34;
        }
        .file-input {
            display: none;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        .success-message {
            color: #28a745;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>";
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h2 class="mb-4 text-center h1 text-dark">Upload Attendance</h2>

            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                <div class="upload-area mb-4" onclick="document.getElementById('csvFile').click()">
                    <i class="fas fa-cloud-upload-alt fa-3x text-success mb-3"></i>
                    <h5 class="text-success">Click to select CSV file</h5>
                    <p class="text-muted mb-0">or drag and drop file here</p>
                    <p class="text-muted small mb-0 mt-2">Max file size: 10MB | Accepted format: .csv</p>
                    <input type="file" id="csvFile" name="csvFile" class="file-input" accept=".csv">
                </div>

                <div id="validationMessage" class="text-center d-none mb-3"></div>

                <div id="fileInfo" class="alert alert-info d-none">
                    <i class="fas fa-file-csv me-2"></i>
                    <span id="fileName"></span>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-upload me-2"></i>
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const uploadArea = document.querySelector('.upload-area');
    const fileInput = document.getElementById('csvFile');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const validationMessage = document.getElementById('validationMessage');
    const uploadButton = document.querySelector('button[type="submit"]');

    // File validation function
    function validateFile(file) {
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        const allowedExtensions = ['csv'];

        // Check file extension
        const fileExtension = file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            return {
                valid: false,
                message: 'Invalid file type. Please select a CSV file.'
            };
        }

        // Check file size
        if (file.size > maxSize) {
            return {
                valid: false,
                message: 'File size too large. Maximum allowed size is 10MB.'
            };
        }

        // Check if file is empty
        if (file.size === 0) {
            return {
                valid: false,
                message: 'File is empty. Please select a valid CSV file.'
            };
        }

        return {
            valid: true,
            message: 'File is valid and ready to upload.'
        };
    }

    // Display validation message
    function showValidationMessage(message, isError = false) {
        validationMessage.textContent = message;
        validationMessage.className = isError ? 'text-center error-message' : 'text-center success-message';
        validationMessage.classList.remove('d-none');
    }

    // Hide validation message
    function hideValidationMessage() {
        validationMessage.classList.add('d-none');
    }

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Handle file selection
    function handleFileSelection(file) {
        const validation = validateFile(file);

        if (validation.valid) {
            fileName.textContent = `${file.name} (${formatFileSize(file.size)})`;
            fileInfo.classList.remove('d-none');
            showValidationMessage(validation.message, false);
            uploadButton.disabled = false;
        } else {
            fileInfo.classList.add('d-none');
            showValidationMessage(validation.message, true);
            uploadButton.disabled = true;
            fileInput.value = ''; // Clear the input
        }
    }

    // File selection handling
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelection(file);
        } else {
            hideValidationMessage();
            uploadButton.disabled = false;
        }
    });

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            handleFileSelection(file);
        }
    });

    // Form submission
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const file = fileInput.files[0];
        if (!file) {
            showValidationMessage('Please select a CSV file to upload.', true);
            return;
        }

        const validation = validateFile(file);
        if (!validation.valid) {
            showValidationMessage(validation.message, true);
            return;
        }

        // Show loading state
        uploadButton.disabled = true;
        uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';

        // Create FormData object
        const formData = new FormData(this);

        // Submit form via AJAX
        fetch('./handlers/upload_attendance_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showValidationMessage(data.message, false);
                    // Reset form on success
                    this.reset();
                    fileInfo.classList.add('d-none');
                    setTimeout(() => hideValidationMessage(), 3000);
                } else {
                    showValidationMessage(data.message, true);
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                showValidationMessage('Upload failed. Please try again.', true);
            })
            .finally(() => {
                // Reset button state
                uploadButton.disabled = false;
                uploadButton.innerHTML = '<i class="fas fa-upload me-2"></i>Upload';
            });
    });
</script>
