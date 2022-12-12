<?php
session_start();
require_once '../classes/events.php';
require_once '../classes/auth.php';

if ($type = Auth::checkLogin()) {
  if ($type != 1) {
    header('Location: ../authentication/login');
  }
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

if (isset($_POST['submit-event'])) {
  $title = $conn->real_escape_string($_POST['title']);
  $description = $_POST['description'];
  $image = $_FILES['image'];

  if (Events::create($title, $description, $image)) {
    $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Event is now added.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
  }
}

if (isset($_POST['submit-edit-event'])) {
  $id = $_POST['id'];
  $title = $conn->real_escape_string($_POST['title']);
  $description = $_POST['description'];
  $image = $_FILES['image'];

  if (Events::update($id, $title, $description, $image)) {
    $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Event is updated.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
  }
}

if (isset($_POST['delete-event'])) {
  $id = $_POST['delete-id'];
  if (Events::destroy($id)) {
    $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Event is now deleted.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
}

if (isset($_POST['delete-bulk'])) {
  if (Events::destroyBulk($_POST['events'])) {
    $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Events are deleted.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
}
$eventsResult = Events::getAllEvents();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once '../templates/header.php' ?>
  <title>PCLU - Admin | Events</title>
  <link rel="stylesheet" href="admin.css?">
</head>

<body>
  <div id="a-wrapper">
    <?php include_once '../templates/admin_sidebar.php' ?>
    <div id="a-main">
      <?php include_once '../templates/admin_nav.php' ?>
      <div id="content">
        <div class="d-flex justify-content-between align-items-center">
          <div class="h5 fw-bold mb-0">Events</div>
          <div>
            <button class="btn btn-danger d-none me-1" id="bulk-btn" onclick="deleteBulk()">Delete Bulk <i class="far fa-trash-alt ms-2"></i></button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Event <i class="fas fa-plus ms-2"></i></button>
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
                  <th></th>
                  <th>ID</th>
                  <th>Picture</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th class="text-nowrap">Date Posted</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

                <?php

                if ($eventsResult->num_rows > 0) {
                  $count = 1;
                  while ($row = $eventsResult->fetch_assoc()) {
                ?>
                    <tr valign="middle">
                      <td>
                        <input class="form-check-input checks" type="checkbox" form="delete-bulk" name="events[]" value="<?= $row['id'] ?>" id="flexCheckDefault">
                      </td>
                      <td>
                        <?= $count++; ?>
                      </td>
                      <td><img class="img-list" src="../uploads/events/<?= $row['picture'] ?>" alt=""> </td>
                      <td><?= $row['title'] ?></td>
                      <td class="smalltxt">
                        <div style="max-height: 70px; overflow: hidden;"><?= $row['description'] ?></div>
                      </td>
                      <td><?= date('M d, Y', strtotime($row['date_posted'])) ?></td>
                      <td>
                        <div class="dropdown">
                          <button class="btn py-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" target="_blank" href="../client/read?type=events&id=<?= $row['id'] ?>"><i class="fas fa-eye me-2"></i> View live</a></li>
                            <li><a class="dropdown-item" onclick="loadEdit(<?= $row['id'] ?>)"><i class="fas fa-edit me-2"></i> Edit</a></li>
                            <li><a class="dropdown-item" onclick="deleteEvent(<?= $row['id'] ?>)"><i class="fas fa-trash-alt me-2"></i> Delete</a></li>
                          </ul>
                        </div>
                        <form action="" method="post">
                          <input type="hidden" name="delete-id" value="<?= $row['id'] ?>">
                          <input type="submit" name="delete-event" id="delete-event-<?= $row['id'] ?>" class="d-none">
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

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Event</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="post" id="newsForm" enctype="multipart/form-data">

            <div class="row">
              <div class="col-md-6">
                <div class="h6">Title</div>
                <input type="text" name="title" class="form-control mb-3" required>
              </div>
              <div class="col-md-6">
                <div class="h6">Image</div>
                <input type="file" accept="image/*" name="image" class="form-control mb-3">
              </div>
            </div>

            <textarea name="description" id="event-description" cols="30" rows="10" hidden></textarea>
            <div class="h6">Description</div>
            <div style="position: relative">
              <stylo-editor></stylo-editor>
              <article contenteditable="true" class="border p-3" style="min-height: 100px;" onmouseout="$('#event-description').html($(this).html())" onkeyup="$('#event-description').html($(this).html())">
              </article>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
          <button type="submit" form="newsForm" name="submit-event" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Event</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <img src="" alt="" style="width: 300px; aspect-ratio: 16/10; object-fit:cover;" id="e-image-preview" class="mb-3">
          <form action="" method="post" id="newsEditForm" enctype="multipart/form-data">
            <input type="hidden" name="id" id="e-id">
            <div class="row">
              <div class="col-md-6">
                <div class="h6">Title</div>
                <input type="text" name="title" id="e-title" class="form-control mb-3" required>
              </div>
              <div class="col-md-6">
                <div class="h6">New Image (optional)</div>
                <input type="file" accept="image/*" name="image" class="form-control mb-3">
              </div>
            </div>

            <textarea name="description" id="event-e-description" cols="30" rows="10" hidden></textarea>
            <div class="h6">Desription</div>
            <div style="position: relative">
              <stylo-editor></stylo-editor>
              <article contenteditable="true" class="border p-3" style="min-height: 100px;" onmouseout="$('#event-e-description').html($(this).html())" onkeyup="$('#event-e-description').html($(this).html())" id='edit-mark-up'>
              </article>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
          <button type="submit" form="newsEditForm" name="submit-edit-event" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
  <?php include_once '../templates/footer.php' ?>
  <script>
    $(document).ready(function() {
      $('#example').DataTable();
    });

    // Your editable element
    const article = document.querySelector('article[contenteditable="true"]');

    // Stylo
    const stylo = document.querySelector('stylo-editor');

    // Set the `containerRef` property
    stylo.containerRef = article;


    function deleteEvent(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $("#delete-event-" + id).click();
        }
      })

    }

    function deleteBulk() {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $("#delete-bulk-btn").click();
        }
      })
    }

    $(document).on('change', '.checks', function() {
      if ($(":checkbox:checked").length > 0) {
        $("#bulk-btn").removeClass('d-none');
      } else {
        $("#bulk-btn").addClass('d-none');
      }
    })

    function loadEdit(id) {
      $.ajax({
        url: '../router/web.php',
        type: 'post',
        dataType: 'json',
        data: {
          type: 'getEvent',
          id: id
        },
        success: function(response) {
          $("#editModal").modal('show');
          $("#e-id").val(response.id);
          $("#e-title").val(response.title);
          $("#event-e-description").text(response.description);
          $("#edit-mark-up").html(response.description);
          $("#e-image-preview").attr('src', '../uploads/events/' + response.picture);
        }
      });
    }
  </script>
</body>

</html>