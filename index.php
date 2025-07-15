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
    <header class="d-flex justify-content-between align-items-center bg-dark text-white py-2" style="min-height: 10vh;">
        <h1 class="fs-5 p-3">Attendance Management System</h1>
    </header>

    <div class="flex-grow-1 d-flex">
        <div class="col-md-2 d-flex flex-column p-0">
            <?php include "./inc/sidebar.php"; ?>
        </div>
        <main class="col-md-10 p-4 d-flex flex-column">
            <?php echo $page_content; ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>
