<?php
session_start();
require_once '../classes/auth.php';

if ($type = Auth::checkLogin()) {
    if ($type != 1) {
        header('Location: ../authentication/login');
    }
}
$message = '';
$message1 = '';
$user = $_SESSION['auth_user'];
extract($user);

if (isset($_POST['update-information'])) {
    if (Auth::updateAdminInformation($_POST)) {
        $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Information updated.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
        $user = $_SESSION['auth_user'];
        extract($user);
    } else {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Oops!</strong> Email already in used.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
}
if (isset($_POST['update-password'])) {
    extract($_POST);
    if (md5($old_pass) == $user['password']) {
        if ($new_pass == $confirm_pass) {
            if (Auth::updateAdminPassword(md5($new_pass))) {
                $message1 = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Password updated.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
                $user = $_SESSION['auth_user'];
                extract($user);
            } else {
                $message1 = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Oops!</strong> Failed to change password.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }
        } else {
            $message1 = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Oops!</strong> Password did not match.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
    } else {
        $message1 = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Oops!</strong> Old password is incorrect.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
    // if (Auth::updateAdminInformation($_POST)) {
    //     $message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    //     <strong>Success!</strong> Information updated.
    //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //   </div>';
    //     $user = $_SESSION['auth_user'];
    //     extract($user);
    // } else {
    //     $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    //     <strong>Oops!</strong> Email already in used.
    //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //   </div>';
    // }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>PCLU - Admin | Account</title>
    <link rel="stylesheet" href="admin.css?">
</head>

<body>
    <div id="a-wrapper">
        <?php include_once '../templates/admin_sidebar.php' ?>
        <div id="a-main">
            <?php include_once '../templates/admin_nav.php' ?>
            <div id="content">
                <div class="h5 fw-bold"><i class="fas fa-user-cog me-2"></i>Account</div>
                <hr>
                <div class="card mb-3 bg-light border">
                    <div class="card-body">
                        <?= $message ?>
                        <form action="" method="POST">
                            <div class="h6 fw-bold">Admin Information</div>
                            <div class="row mt-4">
                                <div class="col-md-2">
                                    <div class="h6">Firstname</div>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control mb-3" name="first_name" value="<?= $first_name ?>" required>
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-2">
                                    <div class="h6">Middlename</div>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control mb-3" name="middle_name" value="<?= $middle_name ?>">
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-2">
                                    <div class="h6">Lastname</div>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control mb-3" name="last_name" value="<?= $last_name ?>" required>
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-2">
                                    <div class="h6">Extension</div>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control mb-3" name="extension_name" value="<?= $extension_name ?>">
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-2">
                                    <div class="h6">Email</div>
                                </div>
                                <div class="col-md-4">
                                    <input type="email" class="form-control mb-3" name="email" value="<?= $email ?>" required>
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-2">
                                    <div class="h6">Username</div>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control mb-3" name="username" value="<?= $username ?>" required>
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-2">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="update-information" class="btn btn-info float-end text-white px-3">Update</button>
                                </div>
                                <div class="col-md-6"></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card bg-light border">
                    <div class="card-body">
                        <?= $message1 ?>
                        <form action="" method="post">
                            <div class="h6 fw-bold">Change Password</div>

                            <div class="row mt-4">
                                <div class="col-md-2">
                                    <div class="h6">Old password</div>
                                </div>
                                <div class="col-md-4">
                                    <input type="password" name="old_pass" class="form-control mb-3" required>
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-2">
                                    <div class="h6">New Password</div>
                                </div>
                                <div class="col-md-4">
                                    <input type="password" name="new_pass" class="form-control mb-3" required>
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-2">
                                    <div class="h6">Confirm Password</div>
                                </div>
                                <div class="col-md-4">
                                    <input type="password" name="confirm_pass" class="form-control mb-3" required>
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-2">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="update-password" class="btn btn-info float-end text-white px-3">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../templates/footer.php' ?>
</body>

</html>