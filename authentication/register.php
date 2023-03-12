<?php
session_start();
require_once '../classes/auth.php';
$first_name = '';
$middle_name = '';
$last_name = '';
$suffix = '';
$message = '';
$email = "";

if (Auth::checkLogin()) {
    if ($_SESSION['admin'] == false) {
        header("Location: ../client/index");
    }
}

if (isset($_POST['submit'])) {
    $message = '<i class="fas fa-exclamation-circle"></i> ';
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $suffix = mysqli_real_escape_string($conn, $_POST['extension_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $secret = "6LecUlMkAAAAAGl_cCLhyyLbKdhIVS3yF7l82nNh";
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];

    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
    $response = file_get_contents($url);
    $response = json_decode($response, true);

    if ($response["success"] == true) {

        if (Auth::findEmail($email, 'users')) {
            $message .= "Email already in used.";
        } else {
            if ($password == $confirm_password) {
                if (isset($_SESSION['email_code']) && $code == $_SESSION['email_code']) {
                    // insert to DB
                    $password = md5($password);
                    $sql = "INSERT INTO `users`( `first_name`, `middle_name`, `last_name`, `extension_name`, `email`, `password`, `picture`, `email_verified_at`, `is_verified`) VALUES ('$first_name', '$middle_name' ,'$last_name', '$suffix', '$email','$password', 'default.webp', '" . date('Y-m-d') . "','0')";
                    if ($conn->query($sql)) {
                        $id = $conn->insert_id;
                        header('Location: aboutme?id=' . $id);
                    }
                } else {
                    $message .= "Invalid code.";
                }
            } else {
                $message .= "Password did not matched.";
            }
        }
    } else {
        // handle verification failure
        $message .= "reCAPTCHA verification failed";
    }
} else {
    $_SESSION['email_code'] = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>Registration</title>
    <link rel="stylesheet" href="auth.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
        <div class=" w-100 d-flex align-items-center justify-content-center px-md-5">
            <div class="p-3 login-form">
                <div id="login-title">
                    <div class="h2 fw-bold">Alumni<br>Registration</div>
                    <?= $_SESSION['email_code'] ?>
                </div>
                <hr>
                <div class="h6 text-danger ">
                    <?= $message; ?>
                </div>
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control mb-3 border" id="first_name" name="first_name" placeholder="First Name" value="<?= $first_name ?>" required>
                                <label for="name">First Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control mb-3 border" id="middle_name" name="middle_name" placeholder="Middle Name" value="<?= $middle_name ?>">
                                <label for="name">Midde Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control mb-3 border" id="last_name" name="last_name" placeholder="Last Name" value="<?= $last_name ?>" required>
                                <label for="last_name">Last Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control mb-3 border" id="last_name" name="extension_name" placeholder="Suffix" value="<?= $suffix ?>">
                                <label for="last_name">Suffix <span class="smalltxt">(Optional)</span></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control mb-3 border" id="email" placeholder="name@example.com" name="email" value="<?= $email ?>" required>
                                <label for="email">Email address</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" onclick="getCode()" class="btn btn-primary">Get Code</button>
                                    <div class="smalltxt text-muted mt-1">Code will be sent to your email for verification.</div>
                                </div>
                                <div class="spinner-border text-primary d-none" role="status" id="codeSpinner">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control mb-3 border" id="code" name="code" placeholder="Code" value="<?= $last_name ?>" required>
                                <label for="code">6 Digit Code</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control border pass-prev" id="password" placeholder="Password" name="password" pattern=".{8,}" required>
                                <label for="password">Password</label>
                            </div>
                            <div class="smalltxt mb-2 mt-1 text-muted">8 characters above</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control  mb-3 border pass-prev" id="confirm_password" placeholder="Confirm Password" name="confirm_password" required>
                                <label for="confirm_password">Confirm Password</label>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-eye-slash  preview-pass cursor text-primary float-end"></i>

                        </div>
                    </div>


                    <div class="g-recaptcha my-3" data-sitekey="6LecUlMkAAAAAGZe8uSpiMjbWXsk0oENDiiHIhpX"></div>
                    <button type="submit" name="submit" class="btn btn-lg w-100 text-white btn shadow">Register <i class="fas fa-arrow-right float-end mt-1"></i></button>
                    <div class="text-center mt-3">
                        <a href="login" class="text-decoration-none">Go back to login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function getCode() {
            $("#codeSpinner").removeClass('d-none');
            var email = $("#email").val();

            $.post('../router/web', {
                type: 'getCode',
                email: email
            }, function(response) {
                console.log(response);
                $("#codeSpinner").addClass('d-none');
                if (response == 'Success') {
                    $("#email").attr('readonly', true);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Code has been sent.',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else if (response == 'Failed') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Email is invalid',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else if (response == 'Exist') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'info',
                        title: 'Email already in used.',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else if (response == 'Error') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'info',
                        title: 'Sending failed.',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            });
        }
    </script>

</body>

</html>