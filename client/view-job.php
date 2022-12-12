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
        <!-- <div id="header">
          
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" id="cutout">
                <path fill="#fff" fill-opacity="1" d="M0,192L60,181.3C120,171,240,149,360,165.3C480,181,600,235,720,229.3C840,224,960,160,1080,160C1200,160,1320,224,1380,256L1440,288L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
            </svg>
        </div> -->
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