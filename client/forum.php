<?php
session_start();
require_once '../classes/posts.php';
require_once '../classes/auth.php';
$readType = '';
if ($type = Auth::checkLogin()) {
    if ($type != 2) {
        header('Location: ../authentication/login');
    }
} else {
    header('Location: ../authentication/login');
}


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
if (isset($_POST['submit-post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $_POST['description'];
    $image = $_FILES['image'];

    if (Posts::create($title, $description, $image)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Success!</strong> Discussion is now created.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}

$postsResult = Posts::getAllPosts();
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
        <div id="content" style="z-index:9; position: relative">
            <div class="h4 fw-light text-center bg-white shadow-sm mb-0 text-dark" id="page-head"><i class="fas fa-comments me-2"></i> Forum</div>
            <div class="container pt-5">

                <div class="row mb-5">

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Create Discussion <i class="fas fa-plus-circle ms-2"></i></button>

                        <div class="smalltxt fw-bold mb-2"><i class="fas fa-search me-1"></i> Search Alumni</div>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-9">
                        <div class="text-end">
                            <input type="search" class="form-control d-inline w-auto" name="" id="" placeholder="Find discussion" onkeyup="search($(this).val())">
                        </div>
                        <div class="h6 fw-bold">Discussions</div>

                        <?php

                        if ($postsResult->num_rows > 0) {
                            while ($row = $postsResult->fetch_assoc()) {
                        ?>
                                <div class="card mb-2 posts-res" data-title="<?= $row['title'] ?>" data-author="<?= $row['first_name'] . ' ' . $row['last_name'] ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <a href="view-post?id=<?= $row['post_id'] ?>" class="h6 fw-bold"><?= $row['title'] ?></a>
                                                <div class="smalltxt">by <?= $row['first_name'] . ' ' . $row['last_name'] ?></div>
                                            </div>

                                            <div class="smalltxt"><?= date('M d, Y', strtotime($row['date_posted'])) ?></div>
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
        <?php include_once '../templates/foot.php' ?>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Crete New Discussion</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="newsForm" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="h6">Topic or Title</div>
                                <input type="text" name="title" class="form-control mb-3" required>
                            </div>
                            <div class="col-md-6">
                                <div class="h6">Image</div>
                                <input type="file" accept="image/*" name="image" class="form-control mb-3">
                            </div>
                        </div>

                        <textarea name="description" id="news-description" cols="30" rows="10" hidden></textarea>
                        <div class="h6">Description or Statement</div>
                        <div style="position: relative">
                            <stylo-editor></stylo-editor>
                            <article contenteditable="true" class="border p-3" style="min-height: 100px;" onmouseout="$('#news-description').html($(this).html())" onkeyup="$('#news-description').html($(this).html())">
                            </article>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="newsForm" name="submit-post" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
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