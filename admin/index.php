<?php
session_start();
require_once '../classes/auth.php';
if ($type = Auth::checkLogin()) {
    if ($type != 1) {
        header('Location: ../authentication/login');
    }
} else {
    header('Location: ../authentication/login');
}


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include_once '../templates/header.php' ?>
    <title>PCLU - Admin | Dashboard</title>
    <link rel="stylesheet" href="admin.css?">
    <style>
        .dash-card {
            border-left: 7px solid #1a86bc !important;
        }
    </style>
</head>

<body>
    <div id="a-wrapper">
        <?php include_once '../templates/admin_sidebar.php' ?>
        <div id="a-main">
            <?php include_once '../templates/admin_nav.php' ?>
            <div id="content">
                <div class="h5 fw-bold mb-5"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3 bg-light">
                            <div class="card-body">
                                <div class="h6 fw-bold">Registered Alumni</div>
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT * FROM users WHERE is_verified = 1")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold">Verification Request</div>
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT * FROM users WHERE is_verified = 0")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold">Employed</div>
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT * FROM users WHERE employment_status != 2")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold">Batches</div>
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT DISTINCT(batch) FROM users WHERE is_verified = 1")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../templates/footer.php' ?>
</body>

</html>