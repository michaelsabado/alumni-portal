<?php
session_start();
require_once '../classes/auth.php';

$message = '';
$email = "";
if (Auth::checkLogin()) {
    if ($_SESSION['admin'] == false) {
        header("Location: ../client/index");
    }
}

if (isset($_POST['submit'])) {
    $message = '<i class="fas fa-exclamation-circle"></i> ';
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pswd = $_POST['password'];
    $secret = "6LecUlMkAAAAAGl_cCLhyyLbKdhIVS3yF7l82nNh";
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];

    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
    $response = file_get_contents($url);
    $response = json_decode($response, true);

    if ($response["success"] == true) {


        $res = Auth::authenticate($email, $pswd, 2);
        if ($res['is_authenticated']) {
            header("Location: ../client/index");
        } else
            $message .= $res['message'];
    } else {
        // handle verification failure
        $message .= "reCAPTCHA verification failed";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>Login</title>
    <link rel="stylesheet" href="auth.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <div class="vh-100 d-flex flex-md-row flex-sm-column flex-column align-items-stretch justify-content-stretch">
        <div class=" w-100 login-left d-flex align-items-center justify-content-center">
            <div class="text-center">
                <img src="../assets/images/logo.png" height="200" alt="" id="logo" class="mb-md-4 md-sm-2 md-2">
                <div style="text-shadow: 2px 3px 9px rgba(0,0,0,0.45)">
                    <div class="display-2 fw-bolder text-white">ALUMNI</div>
                    <div class="display-2 text-white">PORTAL</div>
                </div>

            </div>
        </div>
        <div class=" w-100 d-flex align-items-center justify-content-center">
            <div class="p-3 login-form">
                <a href="admin-login" class="h6 text-decoration-none float-end smalltxt">Admin <i class="fas fa-toggle-off ms-1"></i></a>
                <div id="login-title">
                    <div class="h2 fw-bold">Alumni<br>Login</div>
                </div>
                <hr>
                <div class="h6 text-danger ">
                    <?= $message; ?>
                </div>
                <form action="" method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control mb-3 border" id="emailFLoat" placeholder="name@example.com" name="email" value="<?= $email ?>" required>
                        <label for="emailFLoat">Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control  mb-5 border" id="passFloat" placeholder="Password" name="password" required>
                        <label for="passFloat">Password</label>
                    </div>
                    <div class="g-recaptcha mb-3" data-sitekey="6LecUlMkAAAAAGZe8uSpiMjbWXsk0oENDiiHIhpX"></div>
                    <button type="submit" name="submit" class="btn btn-lg w-100 text-white btn shadow">Log In <i class="fas fa-arrow-right float-end mt-1"></i></button>
                    <div class="text-center mt-3">
                        <a href="forgot-password" class="text-decoration-none">Forgot password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>