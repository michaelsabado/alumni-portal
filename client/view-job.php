<?php
session_start();
require_once '../classes/jobs.php';

$id = $_GET['id'];
$readType = 'jobs';
$jobRes = Jobs::getJob($id);
if ($jobRes->num_rows > 0) {
    $row = $jobRes->fetch_assoc();
} else {
    header('Location: ' . $type);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>PCLU | Job Posts</title>
    <style>

    </style>
</head>

<body>
    <div id="wrapper">
        <?php include_once '../templates/client_nav.php' ?>
        <div id="content" style="z-index:9; position: relative">
            <div class="h4 fw-light text-center bg-white shadow-sm mb-0 text-dark" id="page-head"><i class="fas fa-briefcase me-2"></i> Job Posts</div>
            <div class="container pt-5" style="max-width: 800px">

                <button class="btn text-primary" onclick="history.back()"><i class="fas fa-arrow-circle-left"></i> Back</button>
                <div id="news" class="p-3 mb-3">
                    <div class="h5 fw-bold"><?= $row['company'] ?></div>
                    <div class="h1 fw-bold mb-3 "><?= $row['title'] ?></div>
                    <div class="smalltxt mb-5 "><i class="fas fa-calendar-day me-2"></i> <?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
                    <div class="h6 lh-base"><?= $row['description'] ?></div>
                </div>
            </div>
        </div>
        <?php include_once '../templates/foot.php' ?>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>