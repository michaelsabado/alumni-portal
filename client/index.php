<?php
session_start();
require_once '../classes/news.php';
require_once '../classes/events.php';
require_once '../classes/jobs.php';

$readType = '';
$newsResult = News::getAllNews(3);
$eventsResult = Events::getAllEvents(3);
$jobsResult = Jobs::getAllJobs(3);

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
    <title>PCLU | Home</title>
    <style>

    </style>
</head>

<body>
    <div id="wrapper">
        <?php include_once '../templates/client_nav.php' ?>
        <div id="header">
            <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" id="cutout">
                <path fill="#ffffff" fill-opacity="1" d="M0,160L40,144C80,128,160,96,240,96C320,96,400,128,480,144C560,160,640,160,720,154.7C800,149,880,139,960,149.3C1040,160,1120,192,1200,186.7C1280,181,1360,139,1400,117.3L1440,96L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
            </svg> -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" id="cutout">
                <path fill="#fff" fill-opacity="1" d="M0,192L60,181.3C120,171,240,149,360,165.3C480,181,600,235,720,229.3C840,224,960,160,1080,160C1200,160,1320,224,1380,256L1440,288L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
            </svg>
        </div>
        <div class="container" style="z-index:9; position: relative">
            <div id="content">
                <div class="row">
                    <div class="col-md-8 col-sm-6 " id="divider">
                        <div class="h6 fw-bold"><i class="far fa-newspaper me-2"></i> University News</div>
                        <?php

                        if ($newsResult->num_rows > 0) {
                            while ($row = $newsResult->fetch_assoc()) {
                        ?>
                                <div id="news" class="p-3 mb-3">
                                    <div class="news-head mb-3">
                                        <img src="../uploads/news/<?= $row['picture'] ?>" alt="" class="news-img">
                                    </div>
                                    <div class="h6 smalltxt mb-0 text-muted float-end"><i class="far fa-clock"></i> <?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
                                    <div class="h5"><?= $row['title'] ?></div>
                                    <hr>
                                    <div class="desc"><?= $row['description'] ?></div>
                                    <div class="text-end">
                                        <a href="read?type=news&id=<?= $row['id'] ?>" class="readmore-txt">Read more <i class="fas fa-angle-double-right"></i></a>
                                    </div>
                                </div>
                                <hr>
                        <?php
                            }
                        } else {
                            echo '<div class="h6 text-muted fst-italic text-center">No news at this moment.</div>';
                        }

                        ?>

                        <?php

                        if ($newsResult->num_rows == 3) :
                        ?>
                            <div class="text-center py-4 mb-5"><a href="news" class="h6 more-btn readmore-txt">View more <i class="fas fa-angle-double-right"></i></a></div>
                        <?php
                        endif
                        ?>
                    </div>
                    <div class="col-md-4 col-sm-6 ">
                        <div class="h6 fw-bold"><i class="far fa-calendar-check me-2"></i> Events</div>
                        <div id="events" class="p-3">
                            <?php
                            if ($eventsResult->num_rows > 0) {
                                while ($row = $eventsResult->fetch_assoc()) {
                            ?>
                                    <div class="card events-card mb-3 border">
                                        <div class="card-header p-0">
                                            <div class="events-head">
                                                <img src="../uploads/events/<?= $row['picture'] ?>" alt="" class="events-img">
                                            </div>
                                        </div>
                                        <div class="card-body">

                                            <div class="h6 mb-0"><?= $row['title'] ?></div>
                                            <div class="h6 smalltxt mb-0 text-muted"><i class="far fa-clock"></i> <?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
                                            <div class="text-end">
                                                <a href="read?type=events&id=<?= $row['id'] ?>" class="readmore-txt">Details <i class="fas fa-angle-double-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo '<div class="h6 text-muted fst-italic text-center">No events at this moment.</div>';
                            }

                            ?>

                            <?php
                            if ($eventsResult->num_rows == 3) :
                            ?>
                                <div class="text-center py-4 mb-5"><a href="events" class="h6 more-btn readmore-txt">View more <i class="fas fa-angle-double-right"></i></a></div>
                            <?php
                            endif
                            ?>
                        </div>
                        <div class="h6 fw-bold"><i class="fas fa-briefcase me-2"></i> Job Posts</div>
                        <div id="jobs" class="p-3">
                            <?php
                            if ($jobsResult->num_rows > 0) {
                                while ($row = $jobsResult->fetch_assoc()) {
                            ?>
                                    <div class="card jobs-card mb-3">
                                        <div class="card-body">
                                            <div class="smalltxt fw-normal badge bg-light border text-dark float-end">Fulltime</div>
                                            <div class="h6 fw-bold mb-0"><?= $row['title'] ?></div>
                                            <div class="h6 smalltxt"><?= $row['company'] ?></div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="h6 smalltxt mb-0 text-muted"><i class="far fa-clock"></i> <?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
                                                <div><a href="view-job?id=<?= $row['id'] ?>" class="text-decoration-none"><i class="fas fa-arrow-circle-right"></i></a></div>
                                            </div>

                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo '<div class="h6 text-muted fst-italic text-center">No job posts at this moment.</div>';
                            }

                            ?>
                            <?php
                            if ($jobsResult->num_rows == 3) :
                            ?>
                                <div class="text-center py-4 mb-5"><a href="jobs" class="h6 more-btn readmore-txt">View more <i class="fas fa-angle-double-right"></i></a></div>
                            <?php
                            endif
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once '../templates/foot.php' ?>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>