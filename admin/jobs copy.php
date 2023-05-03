<?php
session_start();
require_once '../classes/jobs.php';
require_once '../classes/auth.php';

if ($type = Auth::checkLogin()) {
    if ($type != 1) {
        header('Location: ../authentication/login');
    }
}
$jobsResult = Jobs::getAllJobs();

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
    <title>PCLU - Admin | Job Posts</title>
    <link rel="stylesheet" href="admin.css?">
</head>

<body>
    <div id="a-wrapper">
        <?php include_once '../templates/admin_sidebar.php' ?>
        <div id="a-main">
            <?php include_once '../templates/admin_nav.php' ?>
            <div id="content">
                <div class="h5 fw-bold">Job Posts</div>
                <hr>

                <table id="example" class="table table-sm table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Company</th>
                            <th class="text-nowrap">Date Posted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php

                        if ($jobsResult->num_rows > 0) {
                            while ($row = $jobsResult->fetch_assoc()) {

                                if ($row['status'] == 1) $status = '<span class="badge bg-primary">Active</span>';
                                else  if ($row['status'] == 2) $status = '<span class="badge bg-info">Archived</span>';

                                if ($row['type'] == 1) $type = '<span class="badge bg-warning">Full Time</span>';
                                else  if ($row['type'] == 2) $type = '<span class="badge bg-success">Contractual</span>';

                        ?>
                                <tr valign="middle">
                                    <td>
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    </td>
                                    <td><?= $status ?></td>
                                    <td><?= $type ?></td>
                                    <td><?= $row['title'] ?></td>
                                    <td class="smalltxt">
                                        <div style="max-height: 70px; overflow: hidden;"><?= $row['description'] ?></div>
                                    </td>
                                    <td><?= $row['company'] ?></td>
                                    <td><?= date('M d, Y', strtotime($row['date_posted'])) ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn py-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" target="_blank" href="../client/read?type=news&id=<?= $row['id'] ?>">View as visitor</a></li>
                                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                                <li><a class="dropdown-item" href="#">Delete</a></li>
                                            </ul>
                                        </div>
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


    <!-- Modal -->
    <div class="modal fade" id="depModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">New Department</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="depForm"></form>
                    <div class="h6">Department name</div>
                    <input type="text" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="depForm" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">New Course</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="depForm"></form>
                    <div class="h6">Department</div>
                    <input type="text" class="form-control mb-3" required>
                    <div class="h6">Course Name</div>
                    <input type="text" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="depForm" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</body>

</html>