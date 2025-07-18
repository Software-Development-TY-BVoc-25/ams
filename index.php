<?php
require_once 'config.php';

$url = $_GET['url'] ?? '';
$page = trim($url, '/');

// Dynamically include the page file early to set $page_css
$pageFile = 'pages/' . ($page ? $page : 'dashboard') . '.php';
if (file_exists($pageFile)) {
    ob_start();
    include $pageFile;
    $page_content = ob_get_clean();
} else {
    ob_start();
    include 'pages/404.php';
    $page_content = ob_get_clean();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./assets/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/global.css">

    <title>ams</title>
    <?php if (isset($page_css)) echo $page_css; ?>
</head>

<body class="min-vh-100 d-flex flex-column">
    <header class="navbar navbar-expand-lg bg-light border-bottom shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="./">
                <img src="./assets/favicon.png" alt="Logo" width="32" height="32" class="me-2 rounded">
                <span class="fs-5 fw-bold text-dark">Attendance Management</span>
            </a>

            <div class="d-flex align-items-center ms-auto">
                <form method="post" class="d-flex align-items-center me-3">
                    <div class="d-flex flex-column me-4" style="width: 150px;">
                        <label for="yearSelect" class="form-label text-secondary small mb-1 ps-1 ">Year</label>
                        <select id="yearSelect" name="year" class="form-select form-select-sm" aria-label="Select Year" onchange="this.form.submit()">
                            <option value="" disabled selected>Select Year</option>
                            <?php
                            $years = [2025, 2024, 2023, 2022]; // Example data from an object
                            foreach ($years as $year) {
                                $selected = (isset($_COOKIE['year']) && $_COOKIE['year'] == $year) ? 'selected' : '';
                                echo "<option value=\"$year\" $selected>$year</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="d-flex flex-column me-3" style="width: 150px;">
                        <label for="semesterSelect" class="form-label text-secondary small mb-1 ps-1">Semester</label>
                        <select id="semesterSelect" name="semester" class="form-select form-select-sm" aria-label="Select Semester" onchange="this.form.submit()">
                            <option value="" disabled selected>Select Semester</option>
                            <?php
                            $semesters = [1, 2]; // Example data from an object
                            foreach ($semesters as $semester) {
                                $selected = (isset($_COOKIE['semester']) && $_COOKIE['semester'] == $semester) ? 'selected' : '';
                                echo "<option value=\"$semester\" $selected>$semester</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['year'])) {
                    setcookie('year', $_POST['year'], time() + (86400 * 30), "/"); // Set cookie for 30 days
                }
                if (isset($_POST['semester'])) {
                    setcookie('semester', $_POST['semester'], time() + (86400 * 30), "/"); // Set cookie for 30 days
                }
                // Refresh the page to apply changes
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }
            ?>

            <div class="dropdown ps-2">
                <a href="#" class="d-block link-dark text-decoration-none" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bars fa-2x text-dark fs-4"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="settingsDropdown">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Sign out</a></li>
                </ul>
            </div>
        </div>
        </div>
    </header>

    <div class="flex-grow-1 d-flex">
        <div class="col-md-2 d-flex flex-column p-0">
            <?php include "./inc/sidebar.php"; ?>
        </div>
        <main class="col-md-10 p-4 d-flex flex-column">

            <div class="alert alert-info" role="alert">
                <strong>Selected Year:</strong> <?php echo $_COOKIE['year'] ?? "Not selected"; ?><br>
                <strong>Selected Semester:</strong> <?php echo $_COOKIE['semester'] ?? "Not selected"; ?>
            </div>

            <?php echo $page_content; ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>
