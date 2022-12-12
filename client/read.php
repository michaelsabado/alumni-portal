<?php
session_start();
require_once '../classes/news.php';
require_once '../classes/events.php';
$type = $_GET['type'];
$id = $_GET['id'];

if ($type == 'news') {
    $title = '<i class="far fa-newspaper me-2"></i> University News';
    $newsResult = News::getNews($id);
    if ($newsResult->num_rows > 0) {
        $row = $newsResult->fetch_assoc();
    } else {
        header('Location: ' . $type);
    }
    $str = "news";
} else if ($type == 'events') {
    $title = '<i class="far fa-calendar-check me-2"></i> Events';
    $eventsResult = Events::getEvent($id);
    if ($eventsResult->num_rows > 0) {
        $row = $eventsResult->fetch_assoc();
    } else {
        header('Location: ' . $type);
    }
    $str = "events";
} else {
    header('Location: index');
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>PCLU | <?= ucfirst($type) ?></title>
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
            <div class="h4 fw-light text-center bg-white shadow-sm mb-0 text-dark" id="page-head"><?= $title ?></div>
            <div class="container pt-5" style="max-width: 800px">
                <button class="btn text-primary" onclick="history.back()"><i class="fas fa-arrow-circle-left"></i> Back</button>
                <div id="news" class="p-3 mb-3">
                    <div class="h4 fw-bold"><?= $row['title'] ?></div>
                    <div class="smalltxt mb-5"><?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
                    <div class="news-head mb-5">
                        <img src="../uploads/<?= $str ?>/<?= $row['picture'] ?>" alt="" class="news-img">
                    </div>

                    <div class="h6 lh-base"><?= $row['description'] ?></div>

                </div>

            </div>


        </div>
        <?php include_once '../templates/foot.php' ?>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>