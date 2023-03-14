<?php
session_start();
require_once '../classes/user.php';
require_once '../classes/auth.php';

if ($type = Auth::checkLogin()) {
    if ($type != 1) {
        header('Location: ../authentication/login');
    }
} else {
    header('Location: ../authentication/login');
}

$message = '';

if (isset($_POST['submit-verify'])) {
    if (User::verifyUser($_POST['id'])) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Success!</strong> User is now verified.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
    }
}


$id = $_GET['id'];
$usersResult = User::getUser($id);
if ($usersResult->num_rows > 0) {
    $user = $usersResult->fetch_assoc();
} else {
    header('Location: alumni');
}
if (isset($_POST['decline-account'])) {
    User::declineUser($_POST['id']);
    header('Location: alumni?status=account-declined');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>PCLU - Admin | Alumni</title>
    <link rel="stylesheet" href="admin.css?">
    <style>
        #user-profile {
            height: 150px;
            width: 150px;
            border-radius: 100%;
            object-fit: cover;
            object-position: center;
        }
    </style>
    <?php include_once '../templates/footer.php' ?>
</head>

<body>
    <div id="a-wrapper">
        <?php include_once '../templates/admin_sidebar.php' ?>
        <div id="a-main">
            <?php include_once '../templates/admin_nav.php' ?>
            <div id="content">
                <div class="h5 fw-bold"><a href="alumni" class="text-decoration-none">Alumni</a> <i class="fas fa-angle-right mx-2"></i> <?= $user['first_name'] . ' ' . $user['last_name'] ?></div>
                <hr>
                <?= $message ?>
                <div class="row">
                    <div class="col-md-7">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold"><i class="fas fa-user-circle me-2"></i> User Information</div>
                                <hr>

                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        Profile Picture
                                    </div>
                                    <div class="col-md-6">
                                        <img src="../uploads/profile/<?= $user['picture'] ?>" alt="" id="user-profile" class="mb-3">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">First Name</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="first_name" type="text" class="form-control mb-3" value="<?= $user['first_name'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Middle Name</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="middle_name" type="text" class="form-control mb-3" value="<?= $user['middle_name'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Last Name</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="last_name" type="text" class="form-control mb-3" value="<?= $user['last_name'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Suffix</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="extension_name" type="text" class="form-control mb-3" value="<?= $user['extension_name'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Birth Date</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="birth_date" type="date" class="form-control mb-3" value="<?= $user['birth_date'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Civil Status</div>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="civil_status" id="civil_status" class="form-select mb-3" value="" readonly disabled>
                                            <option value="1">Single</option>
                                            <option value="2">Married</option>
                                            <option value="3">Annuled</option>
                                        </select>
                                        <script>
                                            $("#civil_status").val(<?= $user['civil_status'] ?>);
                                        </script>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Gender</div>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="gender" id="gender" value="" class="form-select mb-3" readonly disabled>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                        </select>
                                        <script>
                                            $("#gender").val(<?= $user['gender'] ?>);
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold"><i class="fas fa-voicemail me-2"></i> Contact Information</div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="h6">Barangay</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="address_line" type="text" class="form-control mb-3" value="<?= $user['address_line'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Municipality/City</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="muncity" type="text" class="form-control mb-3" value="<?= $user['muncity'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Province</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="province" type="text" class="form-control mb-3" value="<?= $user['province'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Zip Code</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="zip_code" type="text" class="form-control mb-3" value="<?= $user['zip_code'] ?>" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="h6">Contact #</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="contact" type="text" class="form-control mb-3" value="<?= $user['contact'] ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold"><i class="fas fa-user-graduate me-2"></i> Alumni Information</div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="h6">Student ID</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="student_id" type="text" class="form-control mb-3" value="<?= $user['student_id'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Course</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="course" type="text" class="form-control mb-3" value="<?= $user['course'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Batch</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="batch" type="text" class="form-control mb-3" value="<?= $user['batch'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Graduation Date</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="graduation_date" type="date" class="form-control mb-3" value="<?= $user['graduation_date'] ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold"><i class="fas fa-briefcase me-2"></i> Employment Information</div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="h6">Employment Status</div>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="employment_status" id="employment_status" value="" class="form-select mb-3" readonly disabled>
                                            <!-- <option value="3">Self Employed</option> -->
                                            <option value="1">Employed</option>
                                            <option value="2">Unmployed</option>

                                        </select>
                                        <script>
                                            $("#employment_status").val(<?= $user['employment_status'] ?>);
                                        </script>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Employment Date</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="employment_date_current" type="date" class="form-control mb-3" value="<?= $user['employment_date_current'] ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="h6">Current Position</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="current_position" type="text" class="form-control mb-3" value="<?= $user['current_position'] ?>" readonly>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold"><i class="fas fa-unlock-alt me-2"></i> Account Information</div>
                                <hr>

                                <div class="h6">Email</div>
                                <input name="email" type="text" class="form-control mb-3" value="<?= $user['email'] ?>" readonly>

                                <div class="h6">Status</div>
                                <select name="" id="" class="form-select mb-3" disabled>
                                    <option value="1" <?= ($user['is_verified'] == 1) ? 'selected' : '' ?>>Verified</option>
                                    <option value="0" <?= ($user['is_verified'] == 0) ? 'selected' : '' ?>>Not Verified</option>
                                </select>
                                <?php

                                $a = $user['is_verified'];

                                if ($a == 0) {
                                ?>
                                    <div class="d-flex justify-content-end">

                                        <form action="" method="post" id="decline-form">
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                            <div class="text-end"><button class="btn btn-danger px-3 me-2" name="decline-account" onclick="declineUser()">Decline</button></div>
                                        </form>
                                        <form action="" method="post" id="verify-form">
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                            <div class="text-end"><button class="btn btn-primary px-3" name="submit-verify" onclick="verifyUser()()">Verify Account</button></div>
                                        </form>

                                    </div>
                                <?php
                                }

                                ?>


                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="depModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">New Department</h1>
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
    <div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">New Course</h1>
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

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });



        function verifyUser() {
            Swal.fire({
                title: 'Verify this user',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#verify-form').submit();
                } else {
                    return false;
                }
            })
        }

        function declineUser() {
            Swal.fire({
                title: 'Decline this user',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#decline-form').submit();
                } else {
                    return false;
                }
            })
        }
    </script>
</body>

</html>