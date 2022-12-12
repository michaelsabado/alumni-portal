<?php
session_start();
require_once '../classes/jobs.php';

// if (!Auth::checkLogin()) {
//     header('Location:  ../index');
// }

$jobsResult = Jobs::getAllJobs();
$archived = Jobs::getAllArchived();
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
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true"><i class="fas fa-check-circle me-2"></i> Active</button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false"><i class="fas fa-folder-minus me-2"></i> Archived</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0"><input type="search" class="form-control my-3" placeholder="Search" onkeyup="search($(this).val())">
                        <div class="row g-3 mb-3">


                            <?php

                            if ($jobsResult->num_rows > 0) {
                                while ($row = $jobsResult->fetch_assoc()) {
                            ?>
                                    <div class="col-md-4 jobs-data" data-title="<?= $row['title'] ?>" data-company="<?= $row['company'] ?>">
                                        <div class="card jobs-card mb-3 h-100">
                                            <div class="card-body">
                                                <div class="smalltxt fw-normal badge bg-light border text-dark mb-3">Fulltime</div>
                                                <div class="h6 fw-bold mb-0"><?= $row['title'] ?></div>
                                                <div class="h6 smalltxt"><?= $row['company'] ?></div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="d-flex justify-content-between align-items-center justify-self-end">
                                                    <div class="h6 smalltxt mb-0 text-muted"><i class="fas fa-calendar-day me-2"></i> <?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
                                                    <div><a href="view-job?id=<?= $row['id'] ?>" class="text-decoration-none"><i class="fas fa-arrow-circle-right"></i></a></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                            <?php
                                }
                            }

                            ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                        <div class="row g-3 my-3">


                            <?php

                            if ($archived->num_rows > 0) {
                                while ($row = $archived->fetch_assoc()) {
                            ?>
                                    <div class="col-md-4 ">
                                        <div class="card jobs-card mb-3 h-100">
                                            <div class="card-body">
                                                <div class="smalltxt fw-normal badge bg-light border text-dark mb-3">Fulltime</div>
                                                <div class="h6 fw-bold mb-0"><?= $row['title'] ?></div>
                                                <div class="h6 smalltxt"><?= $row['company'] ?></div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="d-flex justify-content-between align-items-center justify-self-end">
                                                    <div class="h6 smalltxt mb-0 text-muted"><i class="fas fa-calendar-day me-2"></i> <?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
                                                    <div><a href="view-job?id=<?= $row['id'] ?>" class="text-decoration-none"><i class="fas fa-arrow-circle-right"></i></a></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                            <?php
                                }
                            }

                            ?>
                        </div>
                    </div>
                </div>

            </div>


        </div>
        <?php include_once '../templates/foot.php' ?>
    </div>


    <?php include_once '../templates/footer.php' ?>
    <script>
        function search(k) {
            let keyword = k.toLowerCase();
            var jobs = $(".jobs-data");

            jobs.removeClass('d-none');

            jobs.each(function(index, e) {
                let title = e.getAttribute('data-title')
                title = title.toLowerCase();
                let com = e.getAttribute('data-company')
                com = com.toLowerCase();
                if (title.includes(keyword) || com.includes(keyword)) {

                } else {
                    e.classList.add('d-none');
                }
            });


        }
    </script>
</body>

</html>