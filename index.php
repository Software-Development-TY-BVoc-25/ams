<?php
require_once 'config.php';

$url = $_GET['url'] ?? '';
$page = trim($url, '/');

$pageFile = 'pages/' . ($page ? $page : 'dashboard') . '.php';
$pageCssFile = 'assets/css/' . ($page ? $page : 'dashboard') . '.css';


// Handle form submission and update cookies
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Track previous values to reset dependent fields
    $prevCourse     = $_COOKIE['course'] ?? '';

    // Set cookies for all fields, always use empty string for unset
    foreach (['course', 'division', 'semester', 'subject', 'year', 'level'] as $field) {
        $value = isset($_POST[$field]) ? $_POST[$field] : '';
        setcookie($field, $value, time() + (86400 * 30), "/");
        $_COOKIE[$field] = $value;
    }

    // Reset dependent fields if parent changed
    if (isset($_POST['course']) && $_POST['course'] !== $prevCourse) {
        setcookie('division', '', time() - 3600, "/");
        $_COOKIE['division'] = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./assets/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./assets/css/global.css">

    <title>ams</title>
    <?php if (file_exists($pageCssFile)): ?>
        <link rel="stylesheet" href="<?php echo $pageCssFile; ?>">
    <?php endif; ?>

</head>

<body class="min-vh-100 d-flex flex-column">

    <?php include "./inc/header.php"; ?>
    <div class="flex-grow-1 d-flex">
        <aside class="col-md-2 d-flex flex-column p-0 bg-light border-end">
            <?php include "./inc/sidebar.php"; ?>
        </aside>
        <main class="col-md-10 px-4 d-flex flex-column-reverse">
            <div class="flex-grow-1">
                <?php
                if (file_exists($pageFile)) {
                    include $pageFile;
                } else {
                    include 'pages/404.php';
                }
                ?>
            </div>
            <div class="full-width">
                <?php
                if (!isset($hide_filters) || $hide_filters !== true) {
                    include 'inc/filters.php';
                }
                ?>
            </div>

            <!-- <div class="alert alert-info mb-4" role="alert">
                <strong>Selected Year:</strong> <?php echo $_COOKIE['year'] ?? "Not selected"; ?><br>
                <strong>Selected Semester:</strong> <?php echo $_COOKIE['semester'] ?? "Not selected"; ?><br>
                <strong>Selected Subject:</strong> <?php echo $_COOKIE['subject'] ?? "Not selected"; ?><br>
                <strong>Selected Class:</strong> <?php echo $_COOKIE['class'] ?? "Not selected"; ?><br>
                <strong>Selected Month:</strong> <?php echo date('F Y', strtotime($_COOKIE['month'] ?? 'now')); ?><br>
                <strong>Selected Department:</strong> <?php echo $_COOKIE['department'] ?? "Not selected"; ?><br>
                <strong>Selected Course:</strong> <?php echo $_COOKIE['course'] ?? "Not selected"; ?><br>
                <strong>Selected Division:</strong> <?php echo $_COOKIE['division'] ?? "Not selected"; ?><br>
            </div> -->
        </main>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>

</html>
