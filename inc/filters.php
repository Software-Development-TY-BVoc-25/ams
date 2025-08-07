<?php
// filepath: c:\xampp\htdocs\ams\inc\filters.php
require_once __DIR__ . '/../config.php';

// Get selected values from cookies
$selectedYear       = $_COOKIE['year'] ?? '';
$selectedLevel      = $_COOKIE['level'] ?? '';
$selectedCourse     = $_COOKIE['course'] ?? '';
$selectedDivision   = $_COOKIE['division'] ?? '';
$selectedSemester   = $_COOKIE['semester'] ?? '';
$selectedSubject    = $_COOKIE['subject'] ?? '';

// Academic Years
$academicYears = [];
$res = $conn->query("SELECT Year_Label FROM academic_year ORDER BY Year_Label DESC");
while ($row = $res->fetch_assoc()) {
    $academicYears[] = $row['Year_Label'];
}

// Levels (distinct Year_Level from class)
$levels = [];
$res = $conn->query("SELECT DISTINCT Year_Level FROM class ORDER BY FIELD(Year_Level, 'FY', 'SY', 'TY'), Year_Level");
while ($row = $res->fetch_assoc()) {
    $levels[] = $row['Year_Level'];
}

// Courses (all distinct Course_Code from class)
$courses = [];
$res = $conn->query("SELECT DISTINCT Course_Code FROM class ORDER BY Course_Code");
while ($row = $res->fetch_assoc()) {
    $courses[] = $row['Course_Code'];
}
// If selectedCourse is not valid, reset it and dependent fields
if ($selectedCourse && !in_array($selectedCourse, $courses)) {
    $selectedCourse = '';
    $selectedDivision = '';
    $selectedSubject = '';
}

// Divisions (filtered by course)
$divisions = [];
if ($selectedCourse) {
    $stmt = $conn->prepare("SELECT DISTINCT Division FROM class WHERE Course_Code = ?");
    $stmt->bind_param("s", $selectedCourse);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        // Convert NULL to empty string for consistency
        $divisions[] = ($row['Division'] === null) ? '' : $row['Division'];
    }
    // If selectedDivision is not valid, reset it
    if ($selectedDivision && !in_array($selectedDivision, $divisions)) {
        $selectedDivision = '';
    }
}

// Semesters (all)
$semesters = [];
$res = $conn->query("SELECT DISTINCT Semester FROM subject ORDER BY Semester");
while ($row = $res->fetch_assoc()) {
    $semesters[] = $row['Semester'];
}

// Subjects (filtered by course and semester)
$subjects = [];
if ($selectedCourse && $selectedSemester) {
    // Find Department_ID for the selected course
    $stmt = $conn->prepare("SELECT Department_ID FROM class WHERE Course_Code = ? LIMIT 1");
    $stmt->bind_param("s", $selectedCourse);
    $stmt->execute();
    $result = $stmt->get_result();
    $deptRow = $result->fetch_assoc();
    $departmentId = $deptRow ? $deptRow['Department_ID'] : null;

    if ($departmentId) {
        $stmt = $conn->prepare("SELECT Subject_Name FROM subject WHERE Department_ID = ? AND Semester = ?");
        $stmt->bind_param("ii", $departmentId, $selectedSemester);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row['Subject_Name'];
        }
        // If selectedSubject is not valid, reset it
        if ($selectedSubject && !in_array($selectedSubject, $subjects)) {
            $selectedSubject = '';
        }
    }
}
?>
<form method="post" class="row g-3 align-items-center py-3 mt-2 w-100">
    <!-- Year -->
    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-2">
        <div class="input-group">
            <label class="input-group-text small bg-light text-secondary" for="yearSelect" style="font-size: 0.8rem;">Year</label>
            <select id="yearSelect" name="year" class="form-select form-select-sm" style="font-size: 0.8rem;" onchange="this.form.submit()">
                <option value="" disabled <?php echo !$selectedYear ? 'selected' : ''; ?>>Select</option>
                <?php foreach ($academicYears as $year): ?>
                    <option value="<?php echo $year; ?>" <?php echo ($selectedYear == $year) ? 'selected' : ''; ?>>
                        <?php echo $year; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <!-- Level -->
    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-2">
        <div class="input-group">
            <label class="input-group-text small bg-light text-secondary" for="levelSelect" style="font-size: 0.8rem;">Level</label>
            <select id="levelSelect" name="level" class="form-select form-select-sm" style="font-size: 0.8rem;" onchange="this.form.submit()">
                <option value="" disabled <?php echo !$selectedLevel ? 'selected' : ''; ?>>Select</option>
                <?php foreach ($levels as $level): ?>
                    <option value="<?php echo $level; ?>" <?php echo ($selectedLevel == $level) ? 'selected' : ''; ?>>
                        <?php echo $level; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <!-- Course -->
    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-2">
        <div class="input-group">
            <label class="input-group-text small bg-light text-secondary" for="courseSelect" style="font-size: 0.8rem;">Course</label>
            <select id="courseSelect" name="course" class="form-select form-select-sm" style="font-size: 0.8rem;" onchange="this.form.submit()">
                <option value="" disabled <?php echo !$selectedCourse ? 'selected' : ''; ?>>Select</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course; ?>" <?php echo ($selectedCourse == $course) ? 'selected' : ''; ?>>
                        <?php echo $course; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <!-- Division -->
    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-2">
        <div class="input-group">
            <label class="input-group-text small bg-light text-secondary" for="divisionSelect" style="font-size: 0.8rem;">Division</label>
            <select id="divisionSelect" name="division" class="form-select form-select-sm" style="font-size: 0.8rem;" onchange="this.form.submit()" <?php if (!$selectedCourse) echo 'disabled'; ?>>
                <option value="" <?php echo $selectedDivision === '' ? 'selected' : ''; ?>>None</option>
                <?php foreach ($divisions as $division): ?>
                    <?php if ($division !== ''): ?>
                        <option value="<?php echo $division; ?>" <?php echo ($selectedDivision == $division) ? 'selected' : ''; ?>>
                            <?php echo $division; ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <!-- Semester -->
    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-2">
        <div class="input-group">
            <label class="input-group-text small bg-light text-secondary" for="semesterSelect" style="font-size: 0.8rem;">Semester</label>
            <select id="semesterSelect" name="semester" class="form-select form-select-sm" style="font-size: 0.8rem;" onchange="this.form.submit()">
                <option value="" disabled <?php echo !$selectedSemester ? 'selected' : ''; ?>>Select</option>
                <?php foreach ($semesters as $semester): ?>
                    <option value="<?php echo $semester; ?>" <?php echo ($selectedSemester == $semester) ? 'selected' : ''; ?>>
                        <?php echo $semester; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <!-- Subject -->
    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-2">
        <div class="input-group">
            <label class="input-group-text small bg-light text-secondary" for="subjectSelect" style="font-size: 0.8rem;">Subject</label>
            <select id="subjectSelect" name="subject" class="form-select form-select-sm" style="font-size: 0.8rem;" onchange="this.form.submit()" <?php if (!$selectedCourse || !$selectedSemester) echo 'disabled'; ?>>
                <option value="" disabled <?php echo !$selectedSubject ? 'selected' : ''; ?>>Select</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?php echo $subject; ?>" <?php echo ($selectedSubject == $subject) ? 'selected' : ''; ?>>
                        <?php echo $subject; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</form>
</div>
