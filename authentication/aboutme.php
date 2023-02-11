<?php
session_start();
require_once '../classes/auth.php';
require_once '../classes/user.php';
require_once '../classes/course.php';

$id = $_GET['id'];
$message = '';
$usersResult = $conn->query("SELECT * FROM users WHERE id = $id");
if ($usersResult->num_rows > 0) {
    $user = $usersResult->fetch_assoc();

    extract($user);
    if ($birth_date !== null) {
        header('Location: ../authentication/login');
    }
} else {
    header('Location: ../authentication/login');
}

if (isset($_POST['submit'])) {
    extract($_POST);
    $sql = "UPDATE `users` SET `birth_date`='$birth_date',`civil_status`='$civil_status',`gender`='$gender',`address_line`='$address_line',`muncity`='$muncity',`province`='$province',`zip_code`='$zip_code',`contact`='$contact',`course`='$course',`batch`='$batch',`student_id`='$student_id',`graduation_date`='$graduation_date',`employment_status`='$employment_status',`employment_date_first`='$employment_date_first',`employment_date_current`='$employment_date_current',`current_position`='$current_position' WHERE id = $id";
    // echo $sql;
    if ($conn->query($sql)) {
        header('Location: ../authentication/login');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>Teell us more about you</title>
    <link rel="stylesheet" href="auth.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        #right-side {
            height: 100vh;
            overflow: auto;
        }

        /* 
        @media only screen and (max-width: 768px) {
            #right-side {
                height: auto !important;
            }
        } */
    </style>
</head>

<body>
    <div class="vh-100 d-flex flex-md-row flex-sm-column flex-column align-items-stretch justify-content-stretch">
        <div class=" w-100 login-left d-flex align-items-center justify-content-center">
            <div class="text-center">
                <img src="../assets/images/logo.png" height="200" alt="" id="logo" class="mb-md-4 md-sm-2 md-2">
                <div style="text-shadow: 2px 3px 9px rgba(0,0,0,0.45)">
                    <div class="display-2 fw-bolder text-white">ALMA-TECH</div>
                    <div class="display-6 text-white">Alumni Portal</div>
                </div>

            </div>
        </div>
        <div class=" w-100  px-md-5" id="right-side">
            <div class="p-3 login-form">
                <div id="login-title" class=" mt-md-5 mt-sm-0 mt-0 mb-5">
                    <div class="h5 fw-">Tell us more about you,</div>
                    <div class="h2 fw-bold"><?= $first_name . ' ' . $last_name ?></div>
                </div>
                <div class="h6 text-danger ">
                    <?= $message; ?>
                </div>
                <form action="" method="POST">
                    <div class="h6 mb-0">Personal Information</div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="smalltxt mb-1 fw-bold">Birthdate</div>
                            <input type="date" class="form-control mb-3 border" id="birth_date" name="birth_date" value="<?= $birth_date ?>" required>
                        </div>
                        <div class="col-md-4">
                            <div class="smalltxt mb-1 fw-bold">Civil Status</div>
                            <select name="civil_status" id="" class="form-select mb-3">
                                <option value="1" <?= ($civil_status == 1) ? 'selected' : '' ?>>Single</option>
                                <option value="2" <?= ($civil_status == 2) ? 'selected' : '' ?>>Married</option>
                                <option value="3" <?= ($civil_status == 3) ? 'selected' : '' ?>>Annuled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="smalltxt mb-1 fw-bold">Gender</div>
                            <select name="gender" id="" class="form-select mb-3">
                                <option value="1" <?= ($gender == 1) ? 'selected' : '' ?>>Male</option>
                                <option value="2" <?= ($gender == 2) ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="h6 mb-0">Contact Information</div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="smalltxt mb-1 fw-bold">Contact #</div>
                            <input type="text" class="form-control mb-3 border" id="contact" name="contact" value="<?= $contact ?>" required>
                        </div>
                        <div class="col-md-8">
                            <div class="smalltxt mb-1 fw-bold">Address Line</div>
                            <input type="text" class="form-control mb-3 border" id="address_line" name="address_line" value="<?= $address_line ?>" required>
                        </div>
                        <div class="col-md-5">
                            <div class="smalltxt mb-1 fw-bold">Municipality</div>
                            <input type="text" class="form-control mb-3 border" id="muncity" name="muncity" value="<?= $muncity ?>" required>
                        </div>
                        <div class="col-md-5">
                            <div class="smalltxt mb-1 fw-bold">Province</div>
                            <input type="text" class="form-control mb-3 border" id="province" name="province" value="<?= $province ?>" required>
                        </div>
                        <div class="col-md-2">
                            <div class="smalltxt mb-1 fw-bold">Zip Code</div>
                            <input type="text" class="form-control mb-3 border" id="zip_code" pattern="[0-9]{4}" name="zip_code" value="<?= $zip_code ?>" placeholder="0000" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">

                                    <div class="h6 mb-0">Alumni Information</div>
                                    <hr>

                                    <div class="smalltxt mb-1 fw-bold">Student ID</div>
                                    <input type="text" class="form-control mb-3 border" id="student_id" name="student_id" value="<?= $student_id ?>" required>

                                    <div class="smalltxt mb-1 fw-bold">Course</div>
                                    <select name="course" id="" class="form-select mb-3" required>
                                        <option value="">- - -</option>
                                        <?php
                                        $coursesResult = Course::getAllCourses();
                                        if ($coursesResult->num_rows > 0) {
                                            while ($row = $coursesResult->fetch_assoc()) {
                                        ?>
                                                <option value="<?= $row['id'] ?>" <?= ($course == $row['id']) ? 'selected' : '' ?>><?= $row['description'] ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>

                                    <div class="smalltxt mb-1 fw-bold">Batch</div>
                                    <input type="text" name="batch" class="form-control mb-3 border" pattern="[0-9]{4}" value="<?= $batch ?>" placeholder="ex. 2019">

                                    <div class="smalltxt mb-1 fw-bold">Graduation Date</div>
                                    <input type="date" class="form-control mb-3 border" id="graduation_date" name="graduation_date" value="<?= $graduation_date ?>" required>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body">

                                    <div class="h6 mb-0">Employment Information</div>
                                    <hr>
                                    <div class="smalltxt fw-bold mb-1">Status</div>
                                    <select name="employment_status" id="employment_status" value="" class="form-select mb-3" onchange="checkEmp($(this))">
                                        <option value="2" <?= ($employment_status == 2) ? 'selected' : '' ?>>Unemployed</option>
                                        <option value="1" <?= ($employment_status == 1) ? 'selected' : '' ?>>Employed</option>
                                        <option value="3" <?= ($employment_status == 3) ? 'selected' : '' ?>>Self Employed</option>
                                    </select>


                                    <div class="smalltxt fw-bold mb-1">Employment Date (1st) <span class="fw-light">Optional</span></div>
                                    <input type="date" class="form-control mb-3" value="<?= $employment_date_first ?>">


                                    <div class="smalltxt fw-bold mb-1">Employment Date (Current) <span class="fw-light">Optional</span></div>
                                    <input name="employment_date_current" type="date" class="form-control mb-3" value="<?= $employment_date_current ?>">

                                    <div class="smalltxt fw-bold mb-1">Current Position <span class="fw-light">Optional</span></div>
                                    <input name="current_position" type="text" class="form-control mb-3" value="<?= $current_position ?>" placeholder="ex. Business Manager">


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-2">
                        <button type="submit" name="submit" class="btn btn-lg text-white btn shadow">Submit <i class="fas fa-arrow-right ms-3 mt-1"></i></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    </script>

</body>

</html>