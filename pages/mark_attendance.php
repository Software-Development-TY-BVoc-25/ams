<?php

$year = $_COOKIE['year'] ?? '';
$semester = $_COOKIE['semester'] ?? '';
$course = $_COOKIE['course'] ?? '';
$level = $_COOKIE['level'] ?? '';
$division = $_COOKIE['division'] ?? '';
$subject_id = $_COOKIE['subject'] ?? '';

$classId = '';




?>

<div class="container">
    <ul class="d-flex flex-row gap-5 list-unstyled">
        <li> <?php echo $year; ?> </li>
        <li> <?php echo $semester; ?> </li>
        <li> <?php echo $course; ?> </li>
        <li> <?php echo $level; ?> </li>
        <li> <?php echo $division; ?> </li>
        <li> <?php echo $subject_id; ?> </li>
    </ul>


</div>


