<?php
$hide_filters = true;
?>


<div class="container">
    <div class="row">
        <div>
            <h2 class="mb-4 h1 text-dark mt-4">Add Students</h2>
            <form id="uploadForm" method="POST" class="w-100" enctype="multipart/form-data">
                <div class="upload-area mb-4 w-100" onclick="document.getElementById('csvFile').click()">
                    <i class="fas fa-cloud-upload-alt fa-3x text-success mb-3"></i>
                    <h5 class="text-success">Click to select Student CSV file</h5>
                    <p class="text-muted mb-0">or drag and drop file here</p>
                    <p class="text-muted small mb-0 mt-2">Max file size: 10MB | Accepted format: .csv | For student data import</p>
                    <input type="file" id="csvFile" name="csvFile" class="file-input" accept=".csv">
                </div>

                <div id="validationMessage" class="text-start d-none mb-3"></div>

                <div id="fileInfo" class="alert alert-info d-none">
                    <i class="fas fa-file-csv me-2"></i>
                    <span id="fileName"></span>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary w-100" id="uploadButton" disabled>
                        <i class="fa fa-upload me-2"></i>
                        Upload
                    </button>
                </div>

                <div id="csvPreview" class="table-responsive d-none mt-3"></div>
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
    const uploadButton = document.getElementById('uploadButton');
    const csvPreview = document.getElementById('csvPreview');
    const expectedColumns = [
        'Student_Name', 'Student_Rollno', 'Course_Code', 'Year_Level', 'Division', 'Semester', 'Academic_Year'
    ];

    function validateFile(file) {
        const maxSize = 10 * 1024 * 1024;
        const allowedExtensions = ['csv'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            return {
                valid: false,
                message: 'Invalid file type. Please select a CSV file.'
            };
        }
        if (file.size > maxSize) {
            return {
                valid: false,
                message: 'File size too large. Maximum allowed size is 10MB.'
            };
        }
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

    function validateRow(row) {
        let errors = [];
        if (row.length !== expectedColumns.length) errors.push('Incorrect number of columns');
        if (!row[0]) errors.push('Student_Name missing');
        if (!/^\d+$/.test(row[1])) errors.push('Student_Rollno invalid');
        if (!row[2]) errors.push('Course_Code missing');
        if (!['fy', 'sy', 'ty'].includes(row[3].toLowerCase())) errors.push('Year_Level invalid');
        if (row[4] && !/^[A-Za-z]$/.test(row[4])) errors.push('Division invalid');
        if (!/^\d+$/.test(row[5])) errors.push('Semester invalid');
        if (!/^\d{4}-\d{2}$/.test(row[6])) errors.push('Academic_Year invalid');
        return errors;
    }

    function previewCSV(file) {
        const reader = new FileReader();
        reader.onload = e => {
            const lines = e.target.result.split(/\r?\n/).filter(l => l.trim());
            if (!lines.length) {
                showValidationMessage('CSV file is empty.', true);
                csvPreview.classList.add('d-none');
                uploadButton.disabled = true;
                return;
            }
            const header = lines[0].split(',').map(h => h.trim());
            if (header.length !== expectedColumns.length || !header.every((h, i) => h === expectedColumns[i])) {
                showValidationMessage('CSV header must be: ' + expectedColumns.join(', '), true);
                csvPreview.classList.add('d-none');
                uploadButton.disabled = true;
                return;
            }
            let table = `<table class="table table-bordered"><thead><tr>`;
            header.forEach(h => table += `<th>${h}</th>`);
            table += `</tr></thead><tbody>`;
            let hasErrors = false;
            for (let i = 1; i < lines.length; i++) {
                const row = lines[i].split(',').map(cell => cell.trim());
                const errors = validateRow(row);
                table += `<tr${errors.length ? ' class="table-danger error-row" data-error="' + encodeURIComponent(errors.join(', ')) + '"' : ''}>`;
                row.forEach(cell => table += `<td>${cell}</td>`);
                table += `</tr>`;
                hasErrors = hasErrors || errors.length;
            }
            table += `</tbody></table>`;
            csvPreview.innerHTML = table;
            csvPreview.classList.remove('d-none');
            uploadButton.disabled = hasErrors;
            // Tooltip for error rows
            setTimeout(() => {
                document.querySelectorAll('.error-row').forEach(row => {
                    row.addEventListener('mouseenter', function() {
                        let tooltip = document.createElement('div');
                        tooltip.className = 'csv-error-tooltip';
                        tooltip.innerHTML = decodeURIComponent(row.getAttribute('data-error'));
                        tooltip.style.position = 'absolute';
                        tooltip.style.background = '#f8d7da';
                        tooltip.style.color = '#721c24';
                        tooltip.style.border = '1px solid #f5c6cb';
                        tooltip.style.padding = '6px 12px';
                        tooltip.style.borderRadius = '4px';
                        tooltip.style.zIndex = '1000';
                        tooltip.style.fontSize = '14px';
                        tooltip.style.top = (row.getBoundingClientRect().top + window.scrollY + row.offsetHeight) + 'px';
                        tooltip.style.left = (row.getBoundingClientRect().left + window.scrollX) + 'px';
                        tooltip.classList.add('csv-tooltip-active');
                        document.body.appendChild(tooltip);
                        row._tooltip = tooltip;
                    });
                    row.addEventListener('mouseleave', function() {
                        if (row._tooltip) {
                            document.body.removeChild(row._tooltip);
                            row._tooltip = null;
                        }
                    });
                });
            }, 100);
            showValidationMessage(hasErrors ? 'Some rows have errors. Hover red rows for details.' : 'All rows valid. Ready to upload.', hasErrors);
        };
        reader.readAsText(file);
    }

    function showValidationMessage(message, isError = false) {
        validationMessage.innerHTML = `<p class="mb-1">${message}</p>`;
        validationMessage.className = isError ? 'text-center error-message' : 'text-center success-message';
        validationMessage.classList.remove('d-none');
    }

    function hideValidationMessage() {
        validationMessage.classList.add('d-none');
    }

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            previewCSV(file);
        } else {
            hideValidationMessage();
            csvPreview.classList.add('d-none');
            uploadButton.disabled = true;
        }
    });

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
            fileInput.files = files;
            previewCSV(files[0]);
        }
    });

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
        uploadButton.disabled = true;
        uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
        const formData = new FormData(this);
        fetch('./handlers/add_students_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                let message = Array.isArray(data.message) ? data.message.join('<br>') : data.message;
                showValidationMessage(message, !data.success);

                if (Array.isArray(data.data) && data.data.length) {
                    let table = `<table class="table table-bordered mt-3"><thead><tr>`;
                    expectedColumns.forEach(h => table += `<th>${h}</th>`);
                    table += `</tr></thead><tbody>`;

                    // Collect error rows to render at the top
                    let errorRows = [];

                    // Helper to extract student name/rollno from row
                    function getNameRoll(row) {
                        if (!row) return {
                            name: '',
                            roll: ''
                        };
                        return {
                            name: row.Student_Name || row[0] || '',
                            roll: row.Student_Rollno || row[1] || ''
                        };
                    }

                    // Collect skippedRows and otherErrors
                    data.data.forEach(group => {
                        if (group.type === 'skippedRows') {
                            group.rows.forEach(row => {
                                // Try to find error message for this row
                                let idx = data.data.indexOf(group);
                                let errorMsg = '';
                                if (Array.isArray(data.message)) {
                                    errorMsg = data.message[idx] || 'Skipped row';
                                }
                                let {
                                    name,
                                    roll
                                } = getNameRoll(row);
                                errorRows.push({
                                    error: errorMsg,
                                    name,
                                    roll
                                });
                            });
                        }
                        if (group.type === 'otherErrors') {
                            group.rows.forEach(row => {
                                let idx = data.data.indexOf(group);
                                let errorMsg = '';
                                if (Array.isArray(data.message)) {
                                    errorMsg = data.message[idx] || 'Other error';
                                }
                                let {
                                    name,
                                    roll
                                } = getNameRoll(row);
                                errorRows.push({
                                    error: errorMsg,
                                    name,
                                    roll
                                });
                            });
                        }
                    });

                    // Render error rows at the top
                    errorRows.forEach(err => {
                        table += `<tr class="table-warning">
                            <td colspan="${expectedColumns.length}">
                                <strong>Error:</strong> ${err.error}
                                ${err.name || err.roll ? `<span class="ms-3 text-danger">[${err.name ? 'Name: ' + err.name : ''}${err.name && err.roll ? ', ' : ''}${err.roll ? 'Roll: ' + err.roll : ''}]</span>` : ''}
                            </td>
                        </tr>`;
                    });

                    // Helper to render normal rows
                    function renderRows(rows, rowClass = '') {
                        rows.forEach(row => {
                            table += `<tr${rowClass ? ` class="${rowClass}"` : ''}>`;
                            expectedColumns.forEach(col => table += `<td>${row[col] ?? ''}</td>`);
                            table += `</tr>`;
                        });
                    }

                    // Render non-error rows
                    data.data.forEach(group => {
                        if (group.type === 'createdEnrollments' || group.type === 'insertedStudents') {
                            renderRows(group.rows, 'table-success');
                        }
                        if (group.type === 'existingEnrollments') {
                            renderRows(group.rows, 'table-danger');
                        }
                    });

                    table += `</tbody></table>`;
                    csvPreview.innerHTML = table;
                    csvPreview.classList.remove('d-none');
                } else {
                    csvPreview.classList.add('d-none');
                }

                if (data.success) {
                    this.reset();
                    fileInfo.classList.add('d-none');
                    setTimeout(() => hideValidationMessage(), 5000);
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                showValidationMessage('Upload failed. Please try again.', true);
            })
            .finally(() => {
                uploadButton.disabled = false;
                uploadButton.innerHTML = '<i class="fas fa-upload me-2"></i>Upload';
            });
    });

    // CSS for tooltip
    const style = document.createElement('style');
    style.innerHTML = `
    .csv-error-tooltip {
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        pointer-events: none;
        transition: opacity 0.2s;
    }
    .csv-tooltip-active {
        opacity: 1 !important;
    }
    `;
    document.head.appendChild(style);
</script>
<?php
$enrollmentErrors = [];
// Inside your enrollment check:
if (isset($enrollExists) && $enrollExists) {
    if (isset($csvRollno)) {
        $enrollmentErrors[] = $csvRollno;
    } else {
        error_log("Warning: \$csvRollno is not defined.");
    }
}
if (!empty($enrollmentErrors)) {
    $response['message'][] = "Enrollment already exists for: " . implode(', ', $enrollmentErrors);
} else {
    $response['message'][] = "No enrollment conflicts detected.";
}
// After processing all rows:
if (!empty($enrollmentErrors)) {
    $response['message'][] = "Enrollment already exists for: " . implode(',', $enrollmentErrors);
}


?>
