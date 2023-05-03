<?php
session_start();
require_once '../classes/jobs.php';
require_once '../sendmail.php';
$message = '';
$id = $_GET['id'];
$readType = 'jobs';
$jobRes = Jobs::getJob($id);
if ($jobRes->num_rows > 0) {
    $row = $jobRes->fetch_assoc();
} else {
    header('Location: ' . $type);
}

if (isset($_POST['apply'])) {
    $pitch = $conn->real_escape_string($_POST['pitch']);

    $target_dir = "../uploads/resumes/";
    $pdf = uniqid()  . basename($_FILES['resume']["name"]);
    $target_file = $target_dir . $pdf;
    move_uploaded_file($_FILES['resume']["tmp_name"], $target_file);

    if (setData('Job Application - ' . $row['title'] . ' [' . $row['company'] . ']', $pitch, $row['email'], $row['company'], $target_file, $_SESSION['user_info']['email'])) {
        $sql = "INSERT INTO `applications`(`job_id`, `user_id`, `resume`, `description`) VALUES ('" . $row['id'] . "','" . $_SESSION['id'] . "','$pdf','$pitch')";
        $conn->query($sql);
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your application has been forwarded to the company.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    } else {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Oops!</strong> The company email is not yet available at this moment.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
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
                <?= $message ?>
                <button class="btn text-primary" onclick="history.back()"><i class="fas fa-arrow-circle-left"></i> Back</button>
                <div id="news" class="p-3 mb-3">
                    <?php

                    if ($row['picture'] !== null) {
                        echo '<img src="../uploads/jobs/' . $row['picture'] . '" alt="" class="w-100 rounded-2 border">';
                    }

                    ?>

                    <div class="h5 fw-bold"><?= $row['company'] ?></div>
                    <div class="h1 fw-bold mb-3 "><?= $row['title'] ?></div>
                    <div class="smalltxt mb-5 "><i class="fas fa-calendar-day me-2"></i> <?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
                    <div class="h6 lh-base"><?= $row['description'] ?></div>
                </div>
                <?php

                if ($row['status'] == 1) {
                ?>
                    <div class="text-end">
                        <button class="btn btn-info text-white px-5 shadow-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Apply</button>
                    </div>

                    <!-- Modal -->

                <?php
                }

                ?>

            </div>
        </div>
        <?php include_once '../templates/foot.php' ?>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Apply</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="apply_form" enctype="multipart/form-data">
                        <div class="h6">Attach Resume (PDF)</div>
                        <input type="file" name="resume" id="" class="form-control mb-3" accept="application/pdf" required>
                        <div class="h6">Make your Pitch!</div>
                        <textarea name="pitch" id="" cols="30" rows="10" class="form-control" placeholder="Tell the employer why you are best suited for this role. Highlight specific skills and how you can contribute. Avoid generic pitches e.g I am responsible." required></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn border-0" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="apply_form" name="apply" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>

</body>

</html>