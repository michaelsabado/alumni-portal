<div id="a-sidebar">
    <div id="side-head" class="text-center">
        <img src="../assets/images/logo.png" id="logo">
        <div class="h6 text-white mt-3 fw-bolder mb-0">POLYTECHNIC COLLEGE<br>OF LA UNION</div>
        <!-- <div class="h6 badge bg-white text-dark  fw-normal">Alumni Information System</div> -->
        <hr>
        <div class="px-3">
            <div class="card border-0 bg-dark cursor" onclick="window.location.href='account.php'">
                <div class="card-body d-flex p-2 align-items-center">
                    <img src="../uploads/profile/default.webp" alt="" id="admin-profile" class="me-md-2">
                    <div class="text-start text-white">
                        <div class="h6 mb-0 fw-bold"><?= $_SESSION['user_info']['full_name'] ?></div>
                        <div class="smalltxt">Administrator</div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div id="menus" class="px-3">
            <a href="index" class="menu-link <?= (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'index') ? 'menu-active' : '' ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard <i class="fas fa-angle-right float-end mt-1"></i></a>
            <a href="alumni" class="menu-link <?= (in_array(basename($_SERVER["SCRIPT_FILENAME"], '.php'), ['alumni', 'alumni-view'])) ? 'menu-active' : '' ?>"><i class="fas fa-user-graduate me-2"></i>Alumni <i class="fas fa-angle-right float-end mt-1"></i></a>
            <a href="news" class="menu-link <?= (in_array(basename($_SERVER["SCRIPT_FILENAME"], '.php'), ['news', ''])) ? 'menu-active' : '' ?>"><i class="far fa-newspaper me-2"></i>News <i class="fas fa-angle-right float-end mt-1"></i></a>
            <a href="events" class="menu-link <?= (in_array(basename($_SERVER["SCRIPT_FILENAME"], '.php'), ['events', ''])) ? 'menu-active' : '' ?>"><i class="far fa-calendar-check me-2"></i>Events <i class="fas fa-angle-right float-end mt-1"></i></a>
            <a href="jobs" class="menu-link <?= (in_array(basename($_SERVER["SCRIPT_FILENAME"], '.php'), ['jobs', ''])) ? 'menu-active' : '' ?>"><i class="fas fa-briefcase me-2"></i>Job Posts <i class="fas fa-angle-right float-end mt-1"></i></a>
        </div>

    </div>
</div>