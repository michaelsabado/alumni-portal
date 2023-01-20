<?php
session_start();
require_once '../classes/events.php';
$readType = '';
// if (!Auth::checkLogin()) {
//     header('Location:  ../index');
// }

$eventsResult = Events::getAllEvents();

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
    <title>PCLU | Events</title>
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
            <div class="h4 fw-light text-center bg-white shadow-sm mb-0 text-dark" id="page-head"><i class="far fa-calendar-check me-2"></i> Events</div>
            <div class="container pt-5" style="max-width: 800px">


                <?php

                if ($eventsResult->num_rows > 0) {
                    while ($row = $eventsResult->fetch_assoc()) {
                ?>
                        <div class="p-3 mb-3 d-flex flex-md-row flex-sm-column flex-column">
                            <div class="me-md-4 me-sm-0 me-0 mb-md-0 mb-sm-3 mb-3">
                                <img src="../uploads/events/<?= $row['picture'] ?>" alt="" class="img-list">
                            </div>
                            <div class="w-100">
                                <div class="h5 mb-0"><?= $row['title'] ?></div>
                                <div class="smalltxt"><?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
                                <hr>
                                <div class="desc"><?= $row['description'] ?></div>

                                <a href="read?type=events&id=<?= $row['id'] ?>" class="readmore-txt">Read more <i class="fas fa-angle-double-right"></i></a>

                            </div>

                        </div>
                        <hr>
                <?php
                    }
                } else {
                    echo '<div class="text-center text-muted fst-italic">No events at this moment.</div>';
                }

                ?>

            </div>


        </div>
        <?php include_once '../templates/foot.php' ?>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>