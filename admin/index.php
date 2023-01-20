<?php
session_start();
require_once '../classes/auth.php';

if ($type = Auth::checkLogin()) {
    if ($type != 1) {
        header('Location: ../authentication/login');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../templates/header.php' ?>
    <title>PCLU - Admin | Dashboard</title>
    <link rel="stylesheet" href="admin.css?">
</head>

<body>
    <div id="a-wrapper">
        <?php include_once '../templates/admin_sidebar.php' ?>
        <div id="a-main">
            <?php include_once '../templates/admin_nav.php' ?>
            <div id="content">
                <div class="h5 fw-bold"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</div>

            </div>
        </div>
    </div>

    <?php include_once '../templates/footer.php' ?>
</body>

</html>