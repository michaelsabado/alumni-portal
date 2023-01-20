<?php
session_start();
require_once '../classes/news.php';
$readType = '';
// if (!Auth::checkLogin()) {
//     header('Location:  ../index');
// }

$newsResult = News::getAllNews();

function truncate($string, $limit, $break = " ", $pad = "...")
{
    // return with no change if string is shorter than $limit
    if (strlen($string) <= $limit) return $string;

    $string = substr($string, 0, $limit);
    if (false !== ($breakpoint = strrpos($string, $break))) {
        $string = substr($string, 0, $breakpoint);
    }
    return $string . $pad;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>PCLU | News</title>
    <style>

    </style>
</head>

<body>
    <div id="wrapper">
        <?php include_once '../templates/client_nav.php' ?>
        <div id="content" style="z-index:9; position: relative">
            <div class="h4 fw-light text-center bg-white shadow-sm mb-0 text-dark" id="page-head"><i class="far fa-newspaper me-2"></i> University News</div>
            <div class="container pt-5" style="max-width: 800px">


                <?php

                if ($newsResult->num_rows > 0) {
                    while ($row = $newsResult->fetch_assoc()) {
                ?>
                        <div class="p-3 mb-3 d-flex flex-md-row flex-sm-column flex-column">
                            <div class="me-md-4 me-sm-0 me-0 mb-md-0 mb-sm-3 mb-3">
                                <img src="../uploads/news/<?= $row['picture'] ?>" alt="" class="img-list">
                            </div>
                            <div class="w-100">
                                <div class="h5 mb-0"><?= $row['title'] ?></div>
                                <div class="smalltxt"><?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
                                <hr>
                                <div class="desc"><?= $row['description'] ?></div>
                                <div class="text-">
                                    <a href="read?type=news&id=<?= $row['id'] ?>" class="readmore-txt">Read more <i class="fas fa-angle-double-right"></i></a>
                                </div>
                            </div>

                        </div>
                        <hr>
                <?php
                    }
                } else {
                    echo '<div class="text-center text-muted fst-italic">No news at this moment.</div>';
                }

                ?>


            </div>


        </div>
        <?php include_once '../templates/foot.php' ?>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>