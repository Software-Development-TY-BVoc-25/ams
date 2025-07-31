<?php
require_once 'config.php';

$url = $_GET['url'] ?? '';
$page = trim($url, '/');

$pageFile = 'pages/' . ($page ? $page : 'dashboard') . '.php';
$pageCssFile = 'assets/css/' . ($page ? $page : 'dashboard') . '.css';

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
                include $pageFile;
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
                <strong>Selected Semester:</strong> <?php echo $_COOKIE['semester'] ?? "Not selected"; ?>
            </div> -->
        </main>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>
