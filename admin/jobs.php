<?php
session_start();
require_once '../classes/jobs.php';
require_once '../classes/auth.php';

if ($type = Auth::checkLogin()) {
    if ($type != 1) {
        header('Location: ../authentication/login');
    }
} else {
    header('Location: ../authentication/login');
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

if (isset($_POST['submit-job'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $company = $conn->real_escape_string($_POST['company']);
    $description = $conn->real_escape_string($_POST['description']);
    $email = $conn->real_escape_string($_POST['email']);
    $type = $_POST['type'];
    $image = $_FILES['image'];


    if ($type  == 3) {
        $type = $conn->real_escape_string($_POST['others']);
    }

    if (Jobs::create($title, $description, $company, $type, $email, $image)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Job is now added.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
}

if (isset($_POST['submit-edit-job'])) {
    $id = $_POST['id'];
    $title = $conn->real_escape_string($_POST['title']);
    $company = $conn->real_escape_string($_POST['company']);
    $description = $conn->real_escape_string($_POST['description']);
    $type = $_POST['type'];
    $image = $_FILES['image'];

    if (Jobs::update($id, $title, $description, $company, $type, $image)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Job is updated.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
}

if (isset($_POST['delete-job'])) {
    $id = $_POST['delete-id'];
    if (Jobs::destroy($id)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Job is now deleted.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
    }
}

if (isset($_POST['delete-bulk'])) {
    if (Jobs::destroyBulk($_POST['jobs'])) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Success!</strong> Jobs are deleted.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
    }
}
$newsResult = Jobs::getAllJobsAdmin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>PCLU - Admin | Job</title>
    <link rel="stylesheet" href="admin.css?">
</head>

<body>
    <div id="a-wrapper">
        <?php include_once '../templates/admin_sidebar.php' ?>
        <div id="a-main">
            <?php include_once '../templates/admin_nav.php' ?>
            <div id="content">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="h5 fw-bold mb-0"><i class="fas fa-briefcase me-2"></i>Job</div>
                    <div>
                        <button class="btn btn-danger d-none me-1" id="bulk-btn" onclick="deleteBulk()">Delete Bulk <i class="far fa-trash-alt ms-2"></i></button>
                        <button class="btn btn-warning me-1" data-bs-toggle="modal" data-bs-target="#job_applications">Job Applications</button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Job <i class="fas fa-plus ms-2"></i></button>
                    </div>
                </div>

                <form action="" method="post" id="delete-bulk">
                    <input type="submit" class="d-none" id="delete-bulk-btn" name="delete-bulk">
                </form>
                <hr>
                <?= $message ?>
                <div class="card " style="min-height: 500px">
                    <div class="card-body table-responsive">
                        <table id="example" class="table table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Company</th>
                                    <th>Email</th>
                                    <th>Picture</th>
                                    <th class="text-nowrap">Date Posted</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                if ($newsResult->num_rows > 0) {
                                    $count = 1;
                                    while ($row = $newsResult->fetch_assoc()) {

                                        if ($row['status'] == 1) $status = '<span class="badge bg-primary">Active</span>';
                                        else  if ($row['status'] == 2) $status = '<span class="badge bg-info">Closed</span>';

                                        if ($row['type'] == 1) $type = '<span class="badge bg-warning">Full Time</span>';
                                        else  if ($row['type'] == 2) $type = '<span class="badge bg-success">Contractual</span>';
                                        else  $type = '<span class="badge bg-secondary">' . $row['type'] . '</span>';
                                ?>
                                        <tr valign="middle">
                                            <td>
                                                <input class="form-check-input checks" type="checkbox" form="delete-bulk" name="jobs[]" value="<?= $row['id'] ?>" id="flexCheckDefault">
                                            </td>
                                            <td>
                                                <?= $count++; ?>
                                            </td>
                                            <td id="row-status-<?= $row['id'] ?>"><?= $status ?></td>
                                            <td><?= $type ?></td>
                                            <td><?= $row['title'] ?></td>
                                            <td class="smalltxt">
                                                <div style="max-height: 70px; overflow: hidden;"><?= $row['description'] ?></div>
                                            </td>
                                            <td><?= $row['company'] ?></td>
                                            <td class="smalltxt"><?= $row['email'] ?></td>
                                            <td><img class="img-list" src="../uploads/jobs/<?= $row['picture'] ?>" alt=""> </td>
                                            <td><?= date('M d, Y', strtotime($row['date_posted'])) ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn py-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" target="_blank" href="../client/view-job?id=<?= $row['id'] ?>"><i class="fas fa-eye me-2"></i> View as visitor</a></li>
                                                        <li><a class="dropdown-item" onclick="loadEdit(<?= $row['id'] ?>)"><i class="fas fa-edit me-2"></i> Edit</a></li>
                                                        <li><a class="dropdown-item" onclick="deleteJob(<?= $row['id'] ?>)"><i class="fas fa-trash-alt me-2"></i> Delete</a></li>
                                                        <li>
                                                            <a class="dropdown-item" data-val="<?= $row['status'] ?>" onclick="toggleStatus(<?= $row['id'] ?>, $(this))">
                                                                <?php

                                                                if ($row['status'] == 1) {
                                                                    echo '<i class="fas fa-times-circle me-2"></i> Close Job';
                                                                } else {
                                                                    echo '<i class="fas fa-check-circle me-2"></i> Open Job';
                                                                }

                                                                ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <form action="" method="post">
                                                    <input type="hidden" name="delete-id" value="<?= $row['id'] ?>">
                                                    <input type="submit" name="delete-job" id="delete-job-<?= $row['id'] ?>" class="d-none">
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Job</h1>
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
                                <div class="h6">Company</div>
                                <input type="text" name="company" class="form-control mb-3" required>
                            </div>
                            <div class="col-md-6">
                                <div class="h6">Company Email</div>
                                <input type="email" name="email" class="form-control mb-3" required>
                            </div>
                            <div class="col-md-6">
                                <div class="h6">Type</div>
                                <select name="type" id="" class="form-select mb-3" onchange="checkJob($(this).val())">
                                    <option value="1">Full Time</option>
                                    <option value="2">Contractual</option>
                                    <option value="3">Others</option>
                                </select>
                                <div id="othersPanel" class="d-none">
                                    <div class="smalltxt ">Specify</div>
                                    <input type="text" name="others" id="others" class="form-control mb-3">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="h6">Image</div>
                                <input type="file" accept="image/*" name="image" class="form-control mb-3">
                            </div>
                        </div>
                        <textarea name="description" id="job-description" cols="30" rows="10" hidden></textarea>
                        <div class="h6">Description</div>
                        <div style="position: relative">
                            <stylo-editor></stylo-editor>
                            <article contenteditable="true" class="border p-3" style="min-height: 100px;" onmouseout="$('#job-description').html($(this).html())" onkeyup="$('#job-description').html($(this).html())">
                            </article>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="newsForm" name="submit-job" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Job</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="newsEditForm" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="e-id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="h6">Title</div>
                                <input type="text" name="title" id="e-title" class="form-control mb-3" required>
                            </div>
                            <div class="col-md-6">
                                <div class="h6">Company</div>
                                <input type="text" name="company" id="e-company" class="form-control mb-3" required>
                            </div>
                            <div class="col-md-6">
                                <div class="h6">Company Email</div>
                                <input type="email" name="email" id="e-email" class="form-control mb-3" required>
                            </div>
                            <div class="col-md-6">
                                <div class="h6">Type</div>
                                <select name="type" id="e-type" class="form-select mb-3">
                                    <option value="1">Full Time</option>
                                    <option value="2">Contractual</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="h6">New Image (optional)</div>
                                <input type="file" accept="image/*" name="image" class="form-control mb-3">
                            </div>
                        </div>

                        <textarea name="description" id="job-e-description" cols="30" rows="10" hidden></textarea>
                        <div class="h6">Description</div>
                        <div style="position: relative">
                            <stylo-editor></stylo-editor>
                            <article contenteditable="true" class="border p-3" style="min-height: 100px;" onmouseout="$('#job-e-description').html($(this).html())" onkeyup="$('#job-e-description').html($(this).html())" id='edit-mark-up'>
                            </article>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="newsEditForm" name="submit-edit-job" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="job_applications" tabindex="-1" aria-labelledby="job_applicationsLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="job_applicationsLabel">Job Applications</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="example1" class="table table-sm" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Applicant</th>
                                <th>Company</th>
                                <th>Position</th>
                                <th>Resume</th>
                                <th>Date Applied</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            $job_app = $conn->query("SELECT CONCAT(u.first_name, ' ', u.last_name) as applicant, j.title, j.company, ja.date_created, ja.resume FROM applications ja INNER JOIN users u ON ja.user_id = u.id INNER JOIN jobs j ON ja.job_id = j.id ORDER BY ja.id DESC");
                            if ($job_app->num_rows > 0) {
                                $count = 1;
                                while ($row1 = $job_app->fetch_assoc()) {
                            ?>
                                    <tr>
                                        <td>
                                            <?= $count++ ?>
                                        </td>
                                        <td>
                                            <?= $row1['applicant'] ?>
                                        </td>
                                        <td>
                                            <?= $row1['company'] ?>
                                        </td>
                                        <td>
                                            <?= $row1['title'] ?>
                                        </td>
                                        <td>
                                            <a href="../uploads/resumes/<?= $row1['resume'] ?>" class="smalltxt" target="_blank"> <?= $row1['applicant'] ?> Resume.pdf</a>
                                        </td>
                                        <td>
                                            <?= $row1['date_created'] ?>
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
    <?php include_once '../templates/footer.php' ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $('#example1').DataTable();
        });

        // Your editable element
        const article = document.querySelector('article[contenteditable="true"]');

        // Stylo
        const stylo = document.querySelector('stylo-editor');

        // Set the `containerRef` property
        stylo.containerRef = article;


        function deleteJob(id) {
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
                    $("#delete-job-" + id).click();
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

        function toggleStatus(id, anchor) {

            let e = ['', '<span class="badge bg-primary">Active</span>', '<span class="badge bg-info">Closed</span>'];
            let i = ['', '<i class="fas fa-times-circle me-2"></i> Close Job', '<i class="fas fa-check-circle me-2"></i> Open Job']
            let val = anchor.attr('data-val');
            if (val == 1) val = 2;
            else val = 1;
            $.ajax({
                url: '../router/web.php',
                type: 'post',
                dataType: 'json',
                data: {
                    type: 'toggleStatus',
                    id: id,
                    val: val,
                },
                success: function(response) {
                    console.log(val);
                    if (response) {
                        $("#row-status-" + id).html(e[val]);
                        anchor.attr('data-val', val);
                        anchor.html(i[val]);
                    }
                }
            });
        }

        function loadEdit(id) {
            $.ajax({
                url: '../router/web.php',
                type: 'post',
                dataType: 'json',
                data: {
                    type: 'getJob',
                    id: id
                },
                success: function(response) {
                    console.log(response)
                    $("#editModal").modal('show');
                    $("#e-id").val(response.id);
                    $("#e-title").val(response.title);
                    $("#e-company").val(response.company);
                    $("#e-type").val(response.type);
                    $("#e-email").val(response.email);
                    $("#job-e-description").text(response.description);
                    $("#edit-mark-up").html(response.description);
                }
            });
        }

        function checkJob(val) {
            if (val == 3) {
                $("#othersPanel").removeClass('d-none');
                $("#others").attr('required', true);
            } else {
                $("#othersPanel").addClass('d-none');
                $("#others").attr('required', false);
            }
        }
    </script>
</body>

</html>