<?php
// Handle form submission and set cookies
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['year'])) {
        setcookie('year', $_POST['year'], time() + (86400 * 30), "/"); // Set cookie for 30 days
    }
    if (!empty($_POST['semester'])) {
        setcookie('semester', $_POST['semester'], time() + (86400 * 30), "/"); // Set cookie for 30 days
    }
    if (!empty($_POST['class'])) {
        setcookie('class', $_POST['class'], time() + (86400 * 30), "/"); // Set cookie for 30 days
    }
    if (!empty($_POST['subject'])) {
        setcookie('subject', $_POST['subject'], time() + (86400 * 30), "/"); // Set cookie for 30 days
    }
    // Refresh the page to apply changes
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
?>


<div class="d-flex align-items-center ms-auto py-3 mt-2">
    <form method="post" class="d-flex align-items-center gap-3">
        <!-- Year Selection -->
        <div class="input-group" style="width: 200px;">
            <label class="input-group-text small bg-light text-secondary" for="yearSelect" style="font-size: 0.8rem;">Year</label>
            <select id="yearSelect" name="year" class="form-select form-select-sm" onchange="this.form.submit()" style="font-size: 0.8rem;">
                <option value="" disabled selected>Select</option>
                <?php
                $years = [2025, 2024, 2023, 2022]; // Example data
                foreach ($years as $year) {
                    $selected = (isset($_COOKIE['year']) && $_COOKIE['year'] == $year) ? 'selected' : '';
                    echo "<option value=\"$year\" $selected>$year</option>";
                }
                ?>
            </select>
        </div>

        <!-- Semester Selection -->
        <div class="input-group" style="width: 200px;">
            <label class="input-group-text small bg-light text-secondary" for="semesterSelect" style="font-size: 0.8rem;">Semester</label>
            <select id="semesterSelect" name="semester" class="form-select form-select-sm" onchange="this.form.submit()" style="font-size: 0.8rem;">
                <option value="" disabled selected>Select Semester</option>
                <?php
                $semesters = [1, 2]; // Example data
                foreach ($semesters as $semester) {
                    $selected = (isset($_COOKIE['semester']) && $_COOKIE['semester'] == $semester) ? 'selected' : '';
                    echo "<option value=\"$semester\" $selected>$semester</option>";
                }
                ?>
            </select>
        </div>

        <!-- Class Selection -->
        <div class="input-group" style="width: 200px;">
            <label class="input-group-text small bg-light text-secondary" for="classSelect" style="font-size: 0.8rem;">Class</label>
            <select id="classSelect" name="class" class="form-select form-select-sm" onchange="this.form.submit()" style="font-size: 0.8rem;">
                <option value="" disabled selected>Select Class</option>
                <?php
                $classes = ['FY', 'SY', 'TY']; // Example data
                foreach ($classes as $class) {
                    $selected = (isset($_COOKIE['class']) && $_COOKIE['class'] == $class) ? 'selected' : '';
                    echo "<option value=\"$class\" $selected>$class</option>";
                }
                ?>
            </select>
        </div>

        <!-- Subject Selection -->
        <div class="input-group" style="width: 200px;">
            <label class="input-group-text small bg-light text-secondary" for="subjectSelect" style="font-size: 0.8rem;">Subject</label>
            <select id="subjectSelect" name="subject" class="form-select form-select-sm" onchange="this.form.submit()" style="font-size: 0.8rem;">
                <option value="" disabled selected>Select Subject</option>
                <?php
                $subjects = ['Mathematics', 'Physics', 'Chemistry', 'English']; // Example data
                foreach ($subjects as $subject) {
                    $selected = (isset($_COOKIE['subject']) && $_COOKIE['subject'] == $subject) ? 'selected' : '';
                    echo "<option value=\"$subject\" $selected>$subject</option>";
                }
                ?>
            </select>
        </div>

        <!-- Add more fields like section, etc. here as needed -->
    </form>
</div>
