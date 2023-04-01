<?php
session_start();
require_once '../classes/auth.php';

$codeSent = false;
$done = false;
if (Auth::checkLogin()) {
    if ($_SESSION['admin'] == false) {
        header("Location: ../client/index");
    }
}

$email = $_GET['email'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>Incorrect Password</title>
    <link rel="stylesheet" href="auth.css">
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
        <div class=" w-75 d-flex align-items-center justify-content-center span-all">
            <div class="p-3 login-form">
                <div id="login-title">
                    <div class="h2 fw-bold">Incorrect Password</div>
                </div>
                <hr>
                <div class="smalltxt text-muted mb-3">You have entered 3 incorrect passwords. You can try to reset.<br></div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control mb-3 border" id="emailFLoat" placeholder="name@example.com" name="email" value="<?= $email ?>" readonly>
                    <label for="emailFLoat">Email address</label>
                </div>
                <?php
                
                if(isset($_GET['type'])){
                   if($_GET['type'] == 1){
                    $link = 'admin-forgot-password';
                   }else{
                    $link = 'forgot-password';
                   }
                }else{
                    $link = 'forgot-password';
                   }
                
                ?>
                <a href="<?=$link?>?email=<?=$email?>" class="btn btn-lg w-100 text-white btn-primary shadow">Reset Password <i class="fas fa-arrow-right float-end mt-1"></i></a>
                <div class="text-center mt-3">
                    <a href="login" class="text-decoration-none">Try again</a>
                </div>


            </div>
        </div>
    </div>
    <?php include_once '../templates/footer.php' ?>
</body>

</html>