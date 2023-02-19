<?php
session_start();
require_once '../classes/user.php';
require_once '../classes/posts.php';
$message = '';
$id = $_GET['id'];

$usersResult = User::getUser($id);
if ($usersResult->num_rows > 0) {
    $user = $usersResult->fetch_assoc();
} else {
    header('Location: index');
}

$usersResult = User::getUser($id);
if ($usersResult->num_rows > 0) {
    $user = $usersResult->fetch_assoc();
} else {
    header('Location: index');
}

$postsResult = Posts::getAllUserPosts($id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>PCLU | <?= $user['first_name'] . ' ' . $user['last_name'] ?></title>
    <style>
        #user-profile {
            aspect-ratio: 1/1;
            object-fit: cover;
            max-width: 140px;
            border-radius: 100%;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <?php include_once '../templates/client_nav.php' ?>
        <div id="content" style="z-index:9; position: relative">
            <div class="h4 fw-light  bg-white shadow-sm mb-0 text-dark" id="page-head">
                <div class="container" style="max-width: 1000px">

                    <div class="d-flex align-items-center">
                        <img src="../uploads/profile/<?= $user['picture'] ?>" alt="" id="user-profile" class="mb-3 w-100 me-4">

                        <div>
                            <div class="h2 mb-0 ">
                                <?= $user['first_name'] . ' ' . $user['last_name'] ?>
                            </div>
                            <div class="h6">
                                <?= $user['course'] ?> | Batch <?= $user['batch'] ?>
                            </div>
                            <div class="h6 text-secondary">
                                <?= $user['email'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container pt-5" style="max-width: 1000px">
                <a href="javascript: history.back()" class="btn text-primary"><i class="fas fa-arrow-circle-left"></i> Back</a>
                <!-- <div class="h5 fw-bold">Public Information</div>
                <div class="smalltxt"></div>
                <hr> -->
                <?= $message ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="smalltxt fw-bold">Birthdate</div>
                                <div class="h6 mb-3">
                                    <?= date('F d, Y', strtotime($user['birth_date'])) ?>
                                </div>
                                <div class="smalltxt fw-bold">Address</div>
                                <div class="h6 mb-3">
                                    <?= $user['address_line'] . ', ' . $user['muncity'] . ', ' . $user['province'] . ' ' . $user['zip_code'] ?>
                                </div>
                                <div class="smalltxt fw-bold">Employment Status</div>
                                <div class="h6 mb-0">
                                    <?php
                                    switch ($user['employment_status']) {
                                        case '1':
                                            echo 'Employed';
                                            break;
                                        case '2':
                                            echo 'Unemployed';
                                            break;
                                        case '3':
                                            echo 'Self Employed';
                                            break;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <input type="search" class="form-control d-inline w-auto float-end" name="" id="" placeholder="Find discussion" onkeyup="search($(this).val())">

                                <div class="h6 fw-bold"><i class="fas fa-user-graduate me-2"></i> Discussions</div>
                                <br>
                                <div style="max-height: 80vh; overflow: auto;">


                                    <?php

                                    if ($postsResult->num_rows > 0) {
                                        while ($row = $postsResult->fetch_assoc()) {

                                            switch ($row['status']) {
                                                case 0:
                                                    $str = '<span class="badge bg-warning">For Approval</span>';
                                                    break;
                                                case 1:
                                                    $str = '<span class="badge bg-success">Active</span>';
                                                    break;
                                                case 2:
                                                    $str = '<span class="badge bg-danger">Closed</span>';
                                                    break;
                                                case 3:
                                                    $str = '<span class="badge bg-dark">Disabled</span>';
                                                    break;
                                                case 4:
                                                    $str = '<span class="badge bg-danger">Not Approved</span>';
                                                    break;
                                            }
                                    ?>

                                            <div class="card mt-2 posts-res" data-title="<?= $row['title'] ?>" data-author="<?= $row['first_name'] . ' ' . $row['last_name'] ?>">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <a href="view-post?id=<?= $row['post_id'] ?>" class="h6 fw-bold text-decoration-none"><?= $row['title'] ?></a>
                                                            <div class="smalltxt">by <?= $row['first_name'] . ' ' . $row['last_name'] ?> | <?= $str ?></div>
                                                        </div>

                                                        <div class="smalltxt"><?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
                                                    </div>

                                                </div>
                                            </div>
                                    <?php
                                        }
                                    } else {
                                        echo '<div class="h6 text-muted fst-italic text-center">No discussions at this moment.</div>';
                                    }


                                    ?>
                                </div>
                            </div>
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
            var jobs = $(".posts-res");

            jobs.removeClass('d-none');

            jobs.each(function(index, e) {
                let title = e.getAttribute('data-title')
                title = title.toLowerCase();
                let com = e.getAttribute('data-author')
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