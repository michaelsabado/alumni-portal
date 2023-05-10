<?php
session_start();
require_once '../classes/user.php';
require_once '../classes/course.php';
$message = '';
$id = $_SESSION['id'];

$usersResult = User::getUser($id);
if ($usersResult->num_rows > 0) {
    $user = $usersResult->fetch_assoc();
} else {
    header('Location: index');
}

if (isset($_POST['user-submit'])) {
    $fname = $conn->real_escape_string($_POST['first_name']);
    $mname = $conn->real_escape_string($_POST['middle_name']);
    $lname = $conn->real_escape_string($_POST['last_name']);
    $ename = $conn->real_escape_string($_POST['extension_name']);
    $civil = $conn->real_escape_string($_POST['civil_status']);
    $birth_date = $_POST['birth_date'];
    $gender =  $_POST['gender'];
    if (User::updateUserInformation($fname, $mname, $lname, $ename, $civil, $birth_date, $gender)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Success!</strong> User information updated.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        echo 'Failed';
    }
}

if (isset($_POST['alumni-submit'])) {
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $course = $conn->real_escape_string($_POST['course']);
    $batch = $conn->real_escape_string($_POST['batch']);
    $graduation_date = $conn->real_escape_string($_POST['graduation_date']);
    if (User::updateAlumniInformation($student_id, $course, $batch, $graduation_date)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Success!</strong> Alumni information updated.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        echo 'Failed';
    }
}

if (isset($_POST['contact-submit'])) {
    $address = $conn->real_escape_string($_POST['address_line']);
    $muncity = $conn->real_escape_string($_POST['muncity']);
    $province = $conn->real_escape_string($_POST['province']);
    $zip = $conn->real_escape_string($_POST['zip_code']);
    $contact = $conn->real_escape_string($_POST['contact']);

    if (User::updateContactInformation($address, $muncity, $province, $zip, $contact)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Contact information updated.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    } else {
        echo 'Failed';
    }
}

if (isset($_POST['employment-submit'])) {
    $status = $conn->real_escape_string($_POST['employment_status']);
    $date_first = $conn->real_escape_string($_POST['employment_date_first']);
    $date = $conn->real_escape_string($_POST['employment_date_current']);
    $employer = $conn->real_escape_string($_POST['employer']);
    $nature = $conn->real_escape_string($_POST['nature_of_work']);
    $position = $conn->real_escape_string($_POST['current_position']);

    if (User::updateEmploymentInformation($status, $date_first, $date, $nature, $position, $employer)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Employment information updated.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    } else {
        echo 'Failed';
    }
}

if (isset($_POST['password-submit'])) {
    $old = $conn->real_escape_string($_POST['old_password']);
    $new = $conn->real_escape_string($_POST['new_password']);

    if ($user['password'] == md5($old)) {
        if (User::updatePassword($new)) {
            $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Password updated.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
        } else {
            echo 'Failed';
        }
    } else {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Oops!</strong> Old password is incorrect.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
}

if (isset($_POST['profile-submit'])) {
    $image = $_FILES['image'];

    if (User::updateProfile($image)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Profile picture updated.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    } else {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Oops!</strong> Error uploading image.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
}

$usersResult = User::getUser($id);
if ($usersResult->num_rows > 0) {
    $user = $usersResult->fetch_assoc();
} else {
    header('Location: index');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>PCLU | Account</title>
    <style>
        #user-profile {
            aspect-ratio: 1/1;
            object-fit: cover;
            max-width: 140px;
            border-radius: 100%;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <?php include_once '../templates/client_nav.php' ?>
        <div id="content" style="z-index:9; position: relative">
            <div class="h4 fw-light text-center bg-white shadow-sm mb-0 text-dark" id="page-head"><i class="fas fa-user-circle me-2"></i> Account</div>
            <div class="container pt-5" style="max-width: 1000px">
                <div class="h5 fw-bold">My Information</div>
                <div class="smalltxt">Keep your information up to date.</div>
                <hr>
                <?= $message ?>
                <div class="row">
                    <div class="col-md-7">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold"><i class="fas fa-user-circle me-2"></i> User Information</div>
                                <hr>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <input type="file" name="image" accept="image/*" id="pick-img" onchange="$('#submit-btn-profile').click()" hidden>
                                    <input type="submit" name="profile-submit" id="submit-btn-profile" hidden>
                                </form>
                                <form action="" method="post">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            Profile Picture
                                            <br>
                                            <a class="smalltxt text-decoration-none" style="cursor: pointer" onclick="$('#pick-img').click()">Select new profile</a>

                                        </div>
                                        <div class="col-md-6">
                                            <img src="../uploads/profile/<?= $user['picture'] ?>" alt="" id="user-profile" class="mb-3 w-100">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">First Name</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="first_name" type="text" class="form-control mb-3" value="<?= $user['first_name'] ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Middle Name</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="middle_name" type="text" class="form-control mb-3" value="<?= $user['middle_name'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Last Name</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="last_name" type="text" class="form-control mb-3" value="<?= $user['last_name'] ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Suffix</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="extension_name" type="text" class="form-control mb-3" value="<?= $user['extension_name'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Birth Date</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" name="birth_date" class="form-control mb-3" value="<?= $user['birth_date'] ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Civil Status</div>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="civil_status" id="civil_status" class="form-select mb-3" required>
                                                <option value="1" <?= ($user['civil_status'] == 1) ? 'selected' : '' ?>>Single</option>
                                                <option value="2" <?= ($user['civil_status'] == 2) ? 'selected' : '' ?>>Married</option>
                                                <option value="3" <?= ($user['civil_status'] == 3) ? 'selected' : '' ?>>Annuled</option>
                                            </select>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Gender</div>
                                        </div>
                                        <div class="col-md-6">
                                            <select id="gender" name="gender" class="form-select mb-3" required>
                                                <option value="1" <?= ($user['gender'] == 1) ? 'selected' : '' ?>>Male</option>
                                                <option value="2" <?= ($user['gender'] == 2) ? 'selected' : '' ?>>Female</option>
                                            </select>
                                            <script>
                                                $("#gender").val(<?= $user['gender'] ?>);
                                            </script>
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button class="btn btn-info text-white px-4" type="submit" name="user-submit">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold"><i class="fas fa-voicemail me-2"></i> Contact Information</div>
                                <hr>
                                <form action="" method="post">
                                    <div class="row align-items-center">
                                        <div class="col-md-5">
                                            <div class="h6">Barangay</div>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="address_line" type="text" class="form-control mb-3" value="<?= $user['address_line'] ?>" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="h6">Municipality/City</div>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="muncity" type="text" class="form-control mb-3" value="<?= $user['muncity'] ?>" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="h6">Province</div>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="province" type="text" class="form-control mb-3" value="<?= $user['province'] ?>" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="h6">Zip Code</div>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="zip_code" type="text" class="form-control mb-3" value="<?= $user['zip_code'] ?>" pattern="[0-9]{4}" required>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="h6">Contact # <span class="fw-light">(11 digits)</span></div>
                                        </div>
                                        <div class="col-md-7">
                                            <input name="contact" type="text" class="form-control mb-3" value="<?= $user['contact'] ?>" placeholder="09xxxxxxxxx" pattern="09[0-9]{9}" required>
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button class="btn btn-info text-white px-4" type="submit" name="contact-submit">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold"><i class="fas fa-user-graduate me-2"></i> Alumni Information</div>
                                <hr>
                                <form action="" method="post">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="h6">Student ID</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="student_id" type="text" class="form-control mb-3" value="<?= $user['student_id'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Course</div>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="course" class="form-select mb-3" required>
                                                <?php
                                                $coursesResult = Course::getAllCourses();
                                                if ($coursesResult->num_rows > 0) {
                                                    while ($row = $coursesResult->fetch_assoc()) {
                                                ?>
                                                        <option value="<?= $row['id'] ?>" <?= ($row['id'] == $user['cid']) ? 'selected' : '' ?>><?= $row['description'] ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Batch</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="batch" type="text" class="form-control mb-3" value="<?= $user['batch'] ?>" pattern="[0-9]{4}" placeholder="ex. 2019" required>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Graduation Date</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="graduation_date" type="date" class="form-control mb-3" value="<?= $user['graduation_date'] ?>" required>
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button class="btn btn-info text-white px-4" type="submit" name="alumni-submit">Save</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold"><i class="fas fa-briefcase me-2"></i> Employment Information</div>
                                <hr>
                                <form action="" method="post">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="h6">Status</div>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="employment_status" id="employment_status" value="" class="form-select mb-3">
                                                <option value="2" <?= ($user['employment_status'] == 2) ? 'selected' : '' ?>>Unmployed</option>
                                                <option value="1" <?= ($user['employment_status'] == 1) ? 'selected' : '' ?>>Employed</option>
                                                <!-- <option value="3" <?= ($user['employment_status'] == 3) ? 'selected' : '' ?>>Self Employed</option> -->
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Employment Start Date</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" name="employment_date_first" class="form-control mb-3" value="<?= $user['employment_date_first'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Employment End Date</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="employment_date_current" type="date" class="form-control mb-3" value="<?= $user['employment_date_current'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Employer <span class="smalltxt fw-light">Optional</span></div>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="employer" type="text" class="form-control mb-3" value="<?= $user['employer'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Nature of Work</div>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="nature_of_work" id="nature_of_work" class="form-select border mb-3 emp-add" required>
                                                <option value="">- - -</option>
                                                <?php

                                                $natures = array(
                                                    'Car Dealership',
                                                    'Casino/Gambling',
                                                    'Construction and Engineering',
                                                    'Education',
                                                    'Farming, Fishing and Forestry',
                                                    'Fashion and Entertainment',
                                                    'Finance and Accounting',
                                                    'Food and Retail',
                                                    'Forex Trading and Money Changer',
                                                    'General Labor',
                                                    'Government Employee',
                                                    'Healthcare and Medical Services',
                                                    'Hospitality and Tourism',
                                                    'Human Resource',
                                                    'Insurance',
                                                    'IT and Technical Services',
                                                    'Jewelry Trading',
                                                    'Legal Practice',
                                                    'Management and Consultancy',
                                                    'Manpower Services',
                                                    'Manufacturing and Production',
                                                    'Maritime Industry',
                                                    'Media and Journalism',
                                                    'Mining and Quarrying',
                                                    'Multi-Level Marketing',
                                                    'Non-Profits, Charity, and Social Work',
                                                    'Pawnshop',
                                                    'Personal, Wellness, Beautification, Leisure Services',
                                                    'Pharmaceuticals',
                                                    'Public Services and National Defense',
                                                    'Real State',
                                                    'Remittance',
                                                    'Specialized Professionals',
                                                    'Transportation and Logistics',
                                                    'Trust Entities',
                                                    'Utilities and Sanitation',
                                                    'Wholesale and Retail'
                                                );

                                                foreach ($natures as $nature) {
                                                    if ($nature == $user['nature_of_work']) $str = 'selected';
                                                    else $str = "";
                                                    echo '<option value="' . $nature . '" ' . $str . '>' . $nature . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h6">Current Position</div>
                                        </div>
                                        <div class="col-md-6">
                                            <input name="current_position" type="text" class="form-control mb-3" value="<?= $user['current_position'] ?>" required>
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button class="btn btn-info text-white px-4" type="submit" name="employment-submit">Save</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold"><i class="fas fa-unlock-alt me-2"></i> Account Information</div>
                                <hr>

                                <div class="h6">Email</div>
                                <input name="email" type="text" class="form-control mb-3" value="<?= $user['email'] ?>" disabled>

                                <div class="h6">Status</div>
                                <select name="" id="" class="form-select mb-3" disabled>
                                    <option value="1" <?= ($user['is_verified'] == 1) ? 'selected' : '' ?>>Verified</option>
                                    <option value="0" <?= ($user['is_verified'] == 0) ? 'selected' : '' ?>>Not Verified</option>
                                </select>
                                <hr>
                                <div class="smalltxt fw-bold">Change Password</div>
                                <hr>
                                <form action="" method="post">
                                    <div class="h6">Old password</div>
                                    <input name="old_password" type="password" class="form-control mb-3 pass-prev" required>
                                    <div class="h6">New password</div>
                                    <div style="position: relative;">
                                        <i class="fas fa-eye-slash  preview-pass cursor text-primary float-end" style="position: absolute; right: 12px; top: 12px;"></i>
                                        <input name="new_password" type="password" class="form-control pass-prev" pattern="^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$" required>
                                    </div>
                                    <div class="smalltxt mb-3 text-muted">Password must be at least 8 characters long and contain at least one uppercase letter and one digit</div>
                                    <div class=" text-end">
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-info text-white px-4" type="submit" name="password-submit">Save</button>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <?php include_once '../templates/foot.php' ?>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>