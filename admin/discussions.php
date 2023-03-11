<?php
session_start();
require_once '../classes/posts.php';
require_once '../classes/auth.php';

if ($type = Auth::checkLogin()) {
  if ($type != 1) {
    header('Location: ../authentication/login');
  }
} else {
  header('Location: ../authentication/login');
}


if (isset($_GET['category'])) {
  switch ($_GET['category']) {
    case 'all':
      $category = 'all';
      break;
    case 'approval':
      $category = 0;
      break;
    case 'active':
      $category = 1;
      break;
    case 'closed':
      $category = 2;
      break;
    case 'disabled':
      $category = 3;
      break;
    case 'declined':
      $category = 4;
      break;
  }
} else {
  $category = 'all';
}

$message = "";
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

if (isset($_POST['approve-post'])) {
  $postid = $_POST['approve-id'];
  $conn->query("UPDATE posts SET status = 1 WHERE id = $postid");
  $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Discussion is now approved & active.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if (isset($_POST['decline-post'])) {
  $postid = $_POST['decline-id'];
  $conn->query("UPDATE posts SET status = 4 WHERE id = $postid");
  $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Discussion is declined.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if (isset($_POST['disable-post'])) {
  $postid = $_POST['disable-id'];
  $conn->query("UPDATE posts SET status = 3 WHERE id = $postid");
  $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Discussion is now disabled.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
$postsResult = Posts::getAllPostsAdmin($category);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once '../templates/header.php' ?>
  <title>PCLU - Admin | Discussions</title>
  <link rel="stylesheet" href="admin.css?">
  <style>
    .img-list {
      border-radius: 5px;
      border: none;
    }
  </style>
</head>

<body>
  <div id="a-wrapper">
    <?php include_once '../templates/admin_sidebar.php' ?>
    <div id="a-main">
      <?php include_once '../templates/admin_nav.php' ?>
      <div id="content">
        <div class="d-flex justify-content-between align-items-center">
          <div class="h5 fw-bold mb-0"><i class="fas fa-comments me-2"></i>Discussions</div>
          <div>
            <select name="" id="" class="form-select" onchange="window.location.href='?category=' + $(this).val()">
              <option value="all" <?= $category == 'all' ? 'selected' : '' ?>>All</option>
              <option value="approval" <?= $category == '0' ? 'selected' : '' ?>>For Approval</option>
              <option value="declined" <?= $category == '4' ? 'selected' : '' ?>>Declined</option>
              <li>
                <hr class="dropdown-divider">
              </li>
              <option value="active" <?= $category == '1' ? 'selected' : '' ?>>Active</option>
              <option value="closed" <?= $category == '2' ? 'selected' : '' ?>>Closed</option>
              <option value="disabled" <?= $category == '3' ? 'selected' : '' ?>>Disabled</option>
            </select>
          </div>
        </div>

        <form action="" method="post" id="delete-bulk">
          <input type="submit" class="d-none" id="delete-bulk-btn" name="delete-bulk">
        </form>
        <hr>
        <?= $message ?>
        <div class="card">
          <div class="card-body table-responsive">

            <table id="example" class="table table-sm " style="width:100%">
              <thead>
                <tr>
                  <!-- <th>ID</th> -->
                  <!-- <th>Picture</th> -->
                  <th>Title</th>
                  <th>Author</th>
                  <th>Content</th>
                  <th class="text-nowrap">Date Posted</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

                <?php

                if ($postsResult->num_rows > 0) {
                  $count = 1;
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
                    <tr valign="middle">
                      <!-- <td>
                        <?= $count++; ?>
                      </td> -->
                      <!-- <td><?= ($row['postpic'] == null) ? '<img class="img-list" src="../uploads/posts/' . $row['postpic'] . '" alt="">' : 'N/A' ?></td> -->
                      <td><?= $row['title'] ?></td>
                      <td><a href="alumni-view?id=<?= $row['id'] ?>"><?= $row['first_name'] . ' ' . $row['last_name'] ?></a></td>
                      <td class="smalltxt">
                        <div style="max-height: 70px; overflow: hidden;"><?= $row['description'] ?></div>
                      </td>
                      <td><?= date('M d, Y', strtotime($row['date_posted'])) ?></td>
                      <td><?= $str ?></td>
                      <td>
                        <div class="dropdown">
                          <button class="btn py-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" target="_blank" href="../client/view-post?id=<?= $row['post_id'] ?>"><i class="fas fa-eye me-2"></i> View live</a></li>
                            <li><a class="dropdown-item <?= (in_array($row['status'], [0, 4])) ? '' : 'd-none' ?>" onclick="approve(<?= $row['post_id'] ?>)"><i class="fas fa-check-circle me-2"></i> Approve</a></li>
                            <li><a class="dropdown-item <?= (in_array($row['status'], [0])) ? '' : 'd-none' ?>" onclick="decline(<?= $row['post_id'] ?>)"><i class="fas fa-times-circle me-2"></i> Decline</a></li>
                            <li><a class="dropdown-item <?= (in_array($row['status'], [1])) ? '' : 'd-none' ?>" onclick="disable(<?= $row['post_id'] ?>)"><i class="fas fa-ban me-2"></i> Disable</a></li>
                          </ul>
                        </div>
                        <form action="" method="post">
                          <input type="hidden" name="approve-id" value="<?= $row['post_id'] ?>">
                          <input type="submit" name="approve-post" id="approve-post-<?= $row['post_id'] ?>" class="d-none">
                        </form>
                        <form action="" method="post">
                          <input type="hidden" name="decline-id" value="<?= $row['post_id'] ?>">
                          <input type="submit" name="decline-post" id="decline-post-<?= $row['post_id'] ?>" class="d-none">
                        </form>
                        <form action="" method="post">
                          <input type="hidden" name="disable-id" value="<?= $row['post_id'] ?>">
                          <input type="submit" name="disable-post" id="disable-post-<?= $row['post_id'] ?>" class="d-none">
                        </form>
                      </td>

                    </tr>
                <?php
                  }
                }

                ?>


              </tbody>

            </table>

          </div>
        </div>
      </div>
    </div>
  </div>


  <?php include_once '../templates/footer.php' ?>
  <script>
    $(document).ready(function() {
      $('#example').DataTable();
    });


    function approve(id) {
      Swal.fire({
        title: 'Are you sure?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.isConfirmed) {
          $("#approve-post-" + id).click();
        }
      })

    }

    function decline(id) {
      Swal.fire({
        title: 'Are you sure?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.isConfirmed) {
          $("#decline-post-" + id).click();
        }
      })

    }

    function disable(id) {
      Swal.fire({
        title: 'Are you sure?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.isConfirmed) {
          $("#disable-post-" + id).click();
        }
      })

    }
  </script>
</body>

</html>