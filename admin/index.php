<?php
session_start();
require_once '../classes/auth.php';
require_once '../classes/department.php';
require_once '../classes/user.php';
require_once '../classes/course.php';
if ($type = Auth::checkLogin()) {
    if ($type != 1) {
        header('Location: ../authentication/login');
    }
} else {
    header('Location: ../authentication/login');
}


if (isset($_GET['filter'])) {
    $department = $_GET['department'];
    $course = $_GET['course'];
    $batch = $_GET['batch'];
    $employment = $_GET['employment'];
} else {
    $department = 0;
    $course = 'all';
    $batch = 'all';
    $employment = 'all';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include_once '../templates/header.php' ?>
    <title>PCLU - Admin | Dashboard</title>
    <link rel="stylesheet" href="admin.css?">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <style>
        .dash-card {
            border-left: 7px solid #1a86bc !important;
            transition: all 0.15s ease;
        }

        .dash-card:hover {
            cursor: pointer;
            box-shadow: 0 0.5rem 5rem rgba(0, 0, 0, .15) !important;
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
                        <div class="card dash-card border rounded-3 shadow-sm mb-3" onclick="window.location.href='alumni?type=registered'">
                            <div class="card-body">
                                <div class="h6 fw-bold">Registered Alumni</div>
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT * FROM users WHERE is_verified = 1")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3" onclick="window.location.href='alumni?type=unverified'">
                            <div class="card-body">
                                <div class="h6 fw-bold">Verification Request</div>
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT * FROM users WHERE is_verified = 0")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3 emp">
                            <div class="card-body">
                                <div class="h6 fw-bold">Employed <i class="fas fa-sync text-primary ms-2" onclick="toggleCard(2)"></i></div>
                                <div class="display-5 text-end fw-bolder" onclick="window.location.href='alumni?type=employed'"><?= $conn->query("SELECT * FROM users WHERE employment_status != 2")->num_rows ?></div>
                            </div>
                        </div>
                        <div class="card dash-card border rounded-3 shadow-sm mb-3 d-none unemp">
                            <div class="card-body">
                                <div class="h6 fw-bold">Unemployed <i class="fas fa-sync text-primary ms-2" onclick="toggleCard(1)"></i></div>
                                <div class="display-5 text-end fw-bolder" onclick="window.location.href='alumni?type=unemployed'"><?= $conn->query("SELECT * FROM users WHERE employment_status = 2")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3" onclick="window.location.href='alumni'">
                            <div class="card-body">
                                <div class="h6 fw-bold">Batches</div>
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT DISTINCT(batch) FROM users WHERE is_verified = 1")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="px-2">
                            <hr>
                            <form action="">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="smalltxt mb-1">Select Department</div>
                                        <select name="department" id="depOpt" class="form-select mb-3" onchange="changeDept($(this).val())">
                                            <option value="0">All</option>
                                            <?php
                                            $departmentsResult = Department::getAllDepartments();
                                            if ($departmentsResult->num_rows > 0) {
                                                while ($row = $departmentsResult->fetch_assoc()) {
                                            ?>
                                                    <option value="<?= $row['id'] ?>" <?= ($department == $row['id']) ? 'selected' : '' ?>><?= $row['description'] ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="smalltxt mb-1">
                                            Course
                                        </div>
                                        <select id="crsOpt" name="course" class="form-select mb-3">
                                            <option value="all">All</option>
                                            <?php
                                            $coursesResult = Course::getAllCourses();
                                            if ($coursesResult->num_rows > 0) {
                                                while ($row = $coursesResult->fetch_assoc()) {
                                            ?>
                                                    <option value="<?= $row['id'] ?>" class="crs crs-dep-<?= $row['department_id'] ?>"><?= $row['description'] ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="smalltxt mb-1">
                                            Batch
                                        </div>
                                        <select name="batch" class="form-select mb-3">
                                            <option value="all">All</option>
                                            <?php
                                            $batchesResult = User::getBatches();
                                            if ($batchesResult->num_rows > 0) {
                                                while ($row = $batchesResult->fetch_assoc()) {
                                            ?>
                                                    <option value="<?= $row['batch'] ?>" <?= ($batch == $row['batch']) ? 'selected' : '' ?>><?= $row['batch'] ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="smalltxt mb-1">
                                            Employment Status
                                        </div>
                                        <select name="employment" class="form-select mb-3">
                                            <option value="all">All</option>

                                            <option value="2" <?= ($employment == 2) ? 'selected' : '' ?>>Unemployed</option>
                                            <option value="1" <?= ($employment == 1) ? 'selected' : '' ?>>Employed</option>
                                            <!-- <option value="3">Self Employed</option> -->
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="smalltxt mb-1">
                                            Filter
                                        </div>
                                        <button type="submit" name="filter" class="btn text-white w-100 bg-info">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold mb-4">Alumni Demographics</div>
                                <canvas id="myPieChart" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold mb-4">Employment Rate</div>
                                <canvas id="myBarChart" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold mb-4">Batch Graduates</div>
                                <canvas id="myHorizontalBarChart" width="400" height=""></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

    <?php include_once '../templates/footer.php' ?>

    <script>
        const colors = [
            "rgba(75, 158, 225, 0.7)",
            "rgba(43, 229, 240, 0.7)",
            "rgba(127, 118, 125, 0.7)",
            "rgba(177, 21, 120, 0.7)",
            "rgba(134, 103, 208, 0.7)",
            "rgba(180, 162, 156, 0.7)",
            "rgba(70, 90, 111, 0.7)",
            "rgba(217, 148, 26, 0.7)",
            "rgba(154, 52, 53, 0.7)",
            "rgba(124, 66, 69, 0.7)",
            "rgba(23, 71, 231, 0.7)",
            "rgba(38, 40, 150, 0.7)",
            "rgba(156, 24, 185, 0.7)",
            "rgba(239, 68, 172, 0.7)",
            "rgba(49, 184, 213, 0.7)",
            "rgba(202, 97, 250, 0.7)",
            "rgba(65, 22, 85, 0.7)",
            "rgba(225, 39, 194, 0.7)",
            "rgba(125, 108, 172, 0.7)",
            "rgba(191, 251, 147, 0.7)"
        ];

        // const colors = [];

        // for (let i = 0; i < 20; i++) {
        //     const red = Math.floor(Math.random() * 256);
        //     const green = Math.floor(Math.random() * 256);
        //     const blue = Math.floor(Math.random() * 256);
        //     const alpha = 0.7;

        //     colors.push(`rgba(${red}, ${green}, ${blue}, ${alpha})`);
        // }

        console.log(colors);
        $.get('charts/demographics', {
            dep: '<?= $department ?>',
            course: '<?= $course ?>',
            batch: '<?= $batch ?>',
            employment: '<?= $employment ?>'
        }, function(response) {
            console.log(response);
            var data = JSON.parse(response);
            var pie = document.getElementById("myPieChart").getContext('2d');
            var myPieChart = new Chart(pie, {
                type: 'doughnut',
                data: {
                    labels: data[0],
                    datasets: [{
                        label: '# of Votes',
                        data: data[1],
                        backgroundColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

        });
        $.get('charts/employment_rate', {
            dep: '<?= $department ?>',
            course: '<?= $course ?>',
            batch: '<?= $batch ?>',
        }, function(response) {
            console.log(response);
            var data = JSON.parse(response);
            var bar = document.getElementById("myBarChart").getContext('2d');
            var myBarChart = new Chart(bar, {
                type: 'bar',
                data: {
                    labels: data[0],
                    datasets: [{
                        label: 'Percentage (%)',
                        data: data[1],
                        backgroundColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                max: 100
                            }
                        }]
                    },
                    legend: false
                }
            });
        });
        $.get('charts/batch_graduates', {
            dep: '<?= $department ?>',
            batch: '<?= $batch ?>',
        }, function(response) {
            console.log(response);
            var data = JSON.parse(response);
            const hbar = document.getElementById("myHorizontalBarChart").getContext("2d");
            const myHorizontalBarChart = new Chart(hbar, {
                type: "horizontalBar",
                data: {
                    labels: data[0],
                    datasets: [{
                        label: "# of Graduates",
                        data: data[1],
                        backgroundColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });

        function toggleCard() {
            $('.emp').toggleClass('d-none');
            $('.unemp').toggleClass('d-none');
        }

        changeDept($("#depOpt").val());

        function changeDept(val) {
            console.log(val);

            $("#crsOpt").val('all');
            if (val != '0') {
                $("#crsOpt").val('all');

                $(".crs").addClass('d-none');
                $(".crs-dep-" + val).removeClass('d-none');
            } else {
                $("#crsOpt").val('<?= $course ?>');
                $(".crs").removeClass('d-none');
            }


        }
    </script>

</body>

</html>