<?php
require_once '../classes/auth.php';

if (isset($_GET['type'])) {
    $readType = $_GET['type'];
}
?>

<div id="client-nav">
    <div class="">
        <div class="d-flex align-items-center ">
            <div class="me-3">
                <img src="../assets/images/logo.png" alt="" id="client-nav-logo">
            </div>
            <div>
                <div class="h3 mb-0 fw-bold" id="uni-text">POLYTECHNIC COLLEGE <span style="white-space:nowrap !important">OF LA UNION</span>
                </div>
                <div class="h6 badge bg-white text-dark mb-0 fw-normal border"><i class="fas fa-info-circle"></i> Alma-Tech | Alumni Portal</div>
            </div>
            <?php

            if (!isset($_SESSION['user_login']) || Auth::checkLogin() == 1) {
            ?>
                <div class="ms-auto d-md-block d-sm-none d-none">
                    <a href="../authentication/login" class="btn " id="log-btn">Alumni Login <i class="fas fa-sign-in-alt ms-1"></i></a>
                </div>
            <?php
            } else if (Auth::checkLogin() && Auth::checkLogin() == 2) {
            ?>
                <div class="ms-auto d-md-block d-sm-none d-none">
                    <div class="dropdown">
                        <button class="btn d-flex align-items-center dropdown-toggle shadow-sm" id="acct-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../uploads/profile/<?= $_SESSION['user_info']['picture'] ?>" class="me-2" width="40" height="40" alt="">
                            <div class="text-start me-2">
                                <div class="h6 mb-0"><?= $_SESSION['user_info']['full_name'] ?></div>
                                <div class="smalltxt text-muted">Batch 2022</div>
                            </div>
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg">
                            <li><a class="dropdown-item" href="account">Account</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="../logout.php">Logout <i class="fas fa-sign-out-alt ms-1"></i></a></li>
                        </ul>
                    </div>

                </div>
            <?php
            }

            ?>


            <div class="ms-auto d-md-none d-sm-block d-block">
                <button class="btn" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar"><i class="fas fa-bars"></i></button>
            </div>
        </div>
    </div>

</div>
<div id="menu-bar" class="d-md-flex d-sm-none d-none">
    <a href="index" class="menu-links <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'index') ? 'menu-active' : '' ?>">Home</a>
    <a href="news" class="menu-links <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'news'  || $readType == 'news') ? 'menu-active' : '' ?>">News</a>
    <a href="events" class="menu-links <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'events'  || $readType == 'events') ? 'menu-active' : '' ?>">Events</a>
    <a href="jobs" class="menu-links  <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'jobs' || $readType == 'jobs') ? 'menu-active' : '' ?>">Job Posts</a>
    <?php

    if (isset($_SESSION['user_login']) && $_SESSION['admin'] == false) {
    ?>
        <a href="forum" class="menu-links <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'forum' || $readType == 'forum') ? 'menu-active' : '' ?>">Forum</a>
    <?php
    }

    ?>

</div>
<div class="offcanvas offcanvas-end d-md-none d-sm-block d-block" tabindex="-1" id="sidebar" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <div></div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" id="side-menu">
        <?php

        if (!isset($_SESSION['user_login'])) {
        ?>
            <div class="ms-auto ">
                <a href="../authentication/login" class="btn w-100" id="log-btn">Login <i class="fas fa-sign-in-alt ms-1"></i></a>
            </div>
        <?php
        } else {
        ?>
            <div class="ms-auto ">
                <div class="dropdown">
                    <button class="btn d-flex align-items-center shadow-sm w-100" id="acct-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../uploads/profile/<?= $_SESSION['user_info']['picture'] ?>" class="me-2" width="40" height="40" alt="">
                        <div class="text-start me-2">
                            <div class="h6 mb-0"><?= $_SESSION['user_info']['full_name'] ?></div>
                            <div class="smalltxt text-muted">Batch 2022</div>
                        </div>
                        <div class="ms-auto"><i class="fas fa-caret-down"></i></div>
                    </button>
                    <ul class="dropdown-menu border-0 shadow-lg">
                        <li><a class="dropdown-item" href="account">Account</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="../logout.php">Logout <i class="fas fa-sign-out-alt ms-1"></i></a></li>
                    </ul>
                </div>

            </div>
        <?php
        }

        ?>

        <hr>

        <a href="index" class="side-links  <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'index') ? 'side-active' : '' ?>">Home</a>
        <a href="news" class="side-links <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'news' || $readType == 'news') ? 'side-active' : '' ?>">News</a>
        <a href="events" class="side-links <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'events'  || $readType == 'events') ? 'side-active' : '' ?>">Events</a>
        <a href="jobs" class="side-links <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'jobs'  || $readType == 'jobs') ? 'side-active' : '' ?>">Job Posts</a>
        <a href=" forum" class="side-links <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'forum'  || $readType == 'forum') ? 'side-active' : '' ?>">Forum</a>
    </div>
</div>