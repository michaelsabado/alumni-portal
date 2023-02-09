<?php
session_start();
require_once '../classes/auth.php';

$message = '';
$message1 = '';
$email = "";

$codeSent = false;
$done = false;
if (Auth::checkLogin()) {
    if ($_SESSION['admin'] == false) {
        header("Location: ../client/index");
    }
}

if (isset($_POST['submit'])) {

    $email = $_POST['email'];

    if (Auth::findEmail($email, 'users')) {

        if (Auth::sendEmailCode($email, 'users')) {
            $codeSent = true;
            $message1 = '<i class="fas fa-check-circle"></i> ';
            $message1 .= "Email sent. Kindly check for the code.";
        } else {
            $message = '<i class="fas fa-exclamation-circle"></i> ';
            $message .= "Failed. Please try again.";
        }
    } else {
        $message = '<i class="fas fa-exclamation-circle"></i> ';
        $message .= "Account not found.";
    }
}
if (isset($_POST['code-submit'])) {

    $email = $_POST['email'];
    $code = $_POST['code'];

    if ($code == $_SESSION['password_reset_code']) {
        $codeSent = true;

        if (Auth::sendNewPass($email, 'users')) {
            $message1 = '<i class="fas fa-check-circle"></i> ';
            $message1 .= "Success. Kindly check your email for your new password.";
            $done = true;
        }
    } else {
        $codeSent = true;
        $message = '<i class="fas fa-exclamation-circle"></i> ';
        $message .= "Incorrect code. Please try again.";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>Login</title>
    <link rel="stylesheet" href="auth.css">
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
                <div id="login-title">
                    <div class="h2 fw-bold">Alumni<br>Password Reset</div>
                </div>
                <hr>
                <div class="h6 text-danger ">
                    <?= $message; ?>
                </div>
                <div class="h6 text-success ">
                    <?= $message1; ?>
                </div>
                <?php
                if ($done) {
                ?> <div class="text-center mt-3">
                        <a href="login" class="text-decoration-none">Go back to login</a>
                    </div>
                    <?php
                } else {
                    if ($codeSent) {
                    ?>
                        <form action="" method="POST">
                            <div class="smalltxt text-muted mb-3">Please enter code here. Do not leave this page.<br></div>
                            <input type="hidden" name="email" value="<?= $email ?>">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control mb-3 border" id="codeee" name="code" required>
                                <label for="codeee">6 Digit Code</label>
                            </div>
                            <button type="submit" name="code-submit" class="btn btn-lg w-100 text-white btn shadow">Verify Code <i class="fas fa-arrow-right float-end mt-1"></i></button>
                            <div class="text-center mt-3">
                                <a href="login" class="text-decoration-none">Go back to login</a>
                            </div>
                        </form>
                    <?php
                    } else {
                    ?> <form action="" method="POST">
                            <div class="smalltxt text-muted mb-3">Please enter your email connected to your account.<br></div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control mb-3 border" id="emailFLoat" placeholder="name@example.com" name="email" value="<?= $email ?>" required>
                                <label for="emailFLoat">Email address</label>
                            </div>
                            <button type="submit" name="submit" class="btn btn-lg w-100 text-white btn shadow">Send email <i class="fas fa-arrow-right float-end mt-1"></i></button>
                            <div class="text-center mt-3">
                                <a href="login" class="text-decoration-none">Go back to login</a>
                            </div>
                        </form>
                <?php
                    }
                }


                ?>

            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>