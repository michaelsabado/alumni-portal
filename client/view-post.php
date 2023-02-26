<?php
session_start();
require_once '../classes/posts.php';
require_once '../classes/auth.php';
$message = '';
$id = $_GET['id'];
$readType = 'forum';

$type = Auth::checkLogin();

if (isset($_POST['submit-comment'])) {
    $comment = $conn->real_escape_string($_POST['description']);
    if (Posts::createComment($id, $comment)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Message added.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
}

if (isset($_POST['id'])) {
    $postid = $_POST['id'];
    $conn->query("UPDATE posts SET status = 2 WHERE id = $postid");
    $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Forum closed.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
}

if (isset($_POST['del-id'])) {
    $postid = $_POST['del-id'];
    $conn->query("DELETE FROM posts WHERE id = $postid");
    header('Location: forum');
}

if (isset($_POST['del-com-id'])) {
    $comid = $_POST['del-com-id'];
    $conn->query("DELETE FROM comments WHERE id = $comid");
    $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Message deleted.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
if (isset($_POST['submit-edit-comment'])) {
    $comid = $_POST['com-id'];
    $content = $conn->real_escape_string($_POST['com-content']);
    $conn->query("UPDATE comments SET description = '$content'WHERE id = $comid");
    $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Message updated.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}

$postRes = Posts::getPost($id);
if ($postRes->num_rows > 0) {
    $row = $postRes->fetch_assoc();

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
} else {
    header('Location: forum');
}


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
                <div class="d-flex justify-content-between">
                    <a href="forum" class="btn text-primary"><i class="fas fa-arrow-circle-left"></i> Back</a>
                    <?php

                    if ($row['user_id'] == $_SESSION['id'] && $row['status'] == 1) {
                    ?>
                        <div class="dropdown">
                            <button class="btn py-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><button type="submit" form="delete-form" class="dropdown-item"><i class="fas fa-times-circle me-2"></i> Close Forum</button></li>
                            </ul>
                            <form action="" id="delete-form" method="post">
                                <input type="text" name="id" value="<?= $id ?>" hidden>

                            </form>
                        </div>
                    <?php
                    }
                    ?>

                    <?php
                    if ($type == 2 && $row['user_id'] == $_SESSION['id'] && ($row['status'] == 0 || $row['status'] == 4)) {
                    ?>
                        <div class="dropdown">
                            <button class="btn py-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><button type="submit" form="delete-post" class="dropdown-item">Delete discusssion</button></li>
                            </ul>
                            <form action="" id="delete-post" method="post">
                                <input type="text" name="del-id" value="<?= $id ?>" hidden>

                            </form>
                        </div>
                    <?php
                    }
                    ?>

                </div>

                <?= $message ?>
                <div id="news" class="p-3 mb-3">
                    <div class="h2 fw-bold "><?= $row['title'] ?></div>
                    <div class="smalltxt">by <?= $row['first_name'] . ' ' . $row['last_name'] ?></div>
                    <div class="smalltxt mb-5 "><i class="fas fa-calendar-day me-2"></i> <?= date('M d, Y', strtotime($row['date_posted'])) ?> | <?= $str ?></div>
                    <div class="h6 lh-base"><?= $row['description'] ?></div>
                </div>
                <?php
                if ($row['status'] == 1 || $row['status'] == 2) {
                ?>
                    <hr>

                    <div class="p-2">
                        <div class="h6 fw-bold"><i class="fas fa-comment-alt me-2"></i> Messages</div>
                        <?php
                        $comments = Posts::getComments($row['post_id']);

                        if ($comments->num_rows > 0) {
                            while ($row1 = $comments->fetch_assoc()) {
                        ?>
                                <div class="card mb-2 border" style="border-radius: 10px;">
                                    <div class="card-body p-3">
                                        <div class="d-flex">
                                            <img src="../uploads/profile/<?= $row1['picture'] ?>" alt="" style="width: 40px; height: 40px; border-radius: 100px; object-fit: cover" class="me-2">
                                            <div>
                                                <div class="smalltxt text-primary"><?= $row1['first_name'] . ' ' . $row1['last_name'] ?> <span class="text-secondary"> • <?= date('M d, Y', strtotime($row1['date_commented'])) ?></span></div>
                                                <div class="h6 mb-0" style="white-space: pre-wrap"><?= $row1['description'] ?></div>
                                            </div>
                                            <?php
                                            if ($row1['user_id'] == $_SESSION['id']) {
                                            ?>
                                                <div class="dropdown ms-auto">
                                                    <button class="btn py-0 ps-3 px-0  border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><button type="button" class="dropdown-item" data-id="<?= $row1['comid'] ?>" data-content="<?= htmlspecialchars($row1['description']) ?>" onclick="loadEdit($(this))">Edit message</button></li>
                                                        <li><button type="submit" form="delete-comment-<?= $row1['comid'] ?>" class="dropdown-item">Delete message</button></li>
                                                    </ul>
                                                    <form action="" id="delete-comment-<?= $row1['comid'] ?>" method="post">
                                                        <input type="text" name="del-com-id" value="<?= $row1['comid'] ?>" hidden>

                                                    </form>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else  if ($row['status'] == 1) {
                            echo '<div class="smalltxt fst-italic text-muted">No messages, create one.</div>';
                        } else {
                            echo '<div class="smalltxt fst-italic text-muted">Forum closed.</div>';
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
                <?php
                }
                ?>
            </div>


        </div>
        <?php include_once '../templates/foot.php' ?>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Message</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="editForm">
                        <input type="hidden" name="com-id" id="com-id">
                        <textarea name="com-content" id="com-content" cols="30" rows="5" class="form-control" placeholder="Write here . . ." required></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white border-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editForm" name="submit-edit-comment" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>

    <script>
        function loadEdit(e) {
            var id = e.attr('data-id');
            var content = e.attr('data-content');

            $("#com-id").val(id);
            $("#com-content").val(content);

            $("#editModal").modal("show")
        }
    </script>
</body>

</html>