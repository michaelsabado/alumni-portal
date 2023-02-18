<?php
session_start();
require_once '../classes/posts.php';
$message = '';
$id = $_GET['id'];
$readType = 'forum';
$postRes = Posts::getPost($id);
if ($postRes->num_rows > 0) {
    $row = $postRes->fetch_assoc();

    switch ($row['status']) {
        case 1:
            $str = '<span class="badge bg-success">Active</span>';
            break;
        case 2:
            $str = '<span class="badge bg-danger">Closed</span>';
            break;
    }
} else {
    header('Location: ' . $type);
}

if (isset($_POST['submit-comment'])) {
    $comment = $conn->real_escape_string($_POST['description']);
    if (Posts::createComment($row['post_id'], $comment)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Message added.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
}

$postRes = Posts::getPost($id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>PCLU | Forum</title>
    <style>

    </style>
</head>

<body>
    <div id="wrapper">
        <?php include_once '../templates/client_nav.php' ?>
        <div id="content" style="z-index:9; position: relative" class="mb-5">
            <div class="h4 fw-light text-center bg-white shadow-sm mb-0 text-dark" id="page-head"><i class="fas fa-comments me-2"></i> Forum</div>
            <div class="container pt-5" style="max-width: 800px">
                <a href="forum" class="btn text-primary"><i class="fas fa-arrow-circle-left"></i> Back</a>
                <?= $message ?>
                <div id="news" class="p-3 mb-3">
                    <div class="h2 fw-bold "><?= $row['title'] ?></div>
                    <div class="smalltxt">by <?= $row['first_name'] . ' ' . $row['last_name'] ?></div>
                    <div class="smalltxt mb-5 "><i class="fas fa-calendar-day me-2"></i> <?= date('M d, Y', strtotime($row['date_posted'])) ?> | <?= $str ?></div>
                    <div class="h6 lh-base"><?= $row['description'] ?></div>
                </div>
                <hr>
                <div class="p-2">
                    <div class="h6 fw-bold"><i class="fas fa-comment-alt me-2"></i> Messages</div>
                    <?php
                    $comments = Posts::getComments($row['post_id']);

                    if ($comments->num_rows > 0) {
                        while ($row1 = $comments->fetch_assoc()) {
                    ?>
                            <div class="card mb-1 border" style="border-radius: 10px;">
                                <div class="card-body p-3">
                                    <div class="d-flex">
                                        <img src="../uploads/profile/<?= $row1['picture'] ?>" alt="" style="width: 40px; height: 40px; border-radius: 100px; object-fit: cover" class="me-2">
                                        <div>
                                            <div class="smalltxt text-primary"><?= $row1['first_name'] . ' ' . $row1['last_name'] ?></div>
                                            <div class="h6 mb-0" style="white-space: pre-wrap"><?= $row1['description'] ?></div>


                                        </div>
                                        <div class="smalltxt text-muted ms-auto"><?= date('M d, Y', strtotime($row1['date_commented'])) ?></div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<div class="smalltxt fst-italic text-muted">No messages, create one.</div>';
                    }

                    ?>

                    <?php
                    if ($row['status'] == 1) {
                    ?>
                        <hr>
                        <form action="" method="post">
                            <div class="d-flex">
                                <img src="../uploads/profile/<?= $_SESSION['user_info']['picture'] ?>" alt="" style="width: 50px; height: 50px; border-radius: 100px; object-fit: cover; aspect-ratio: 1/1" class="me-2">
                                <textarea name="description" id="" cols="30" rows="3" class="form-control" placeholder="Write here..." style="border-radius: 10px" required></textarea>
                                <button type="submit" name="submit-comment" class="btn text-info"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </form>
                    <?php
                    }
                    ?>

                </div>
            </div>


        </div>
        <?php include_once '../templates/foot.php' ?>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>