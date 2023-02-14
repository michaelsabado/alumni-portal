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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <style>
        .dash-card {
            border-left: 7px solid #1a86bc !important;
        }

        .dash-card:hover {
            cursor: pointer;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
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
                        <div class="card dash-card border rounded-3 shadow-sm mb-3" onclick="window.location.href='alumni?type=employed'">
                            <div class="card-body">
                                <div class="h6 fw-bold">Employed</div>
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT * FROM users WHERE employment_status != 2")->num_rows ?></div>
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
        $.get('charts/demographics', {}, function(response) {
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
        $.get('charts/employment_rate', {}, function(response) {
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
        $.get('charts/batch_graduates', {}, function(response) {
            var data = JSON.parse(response);
            const hbar = document.getElementById("myHorizontalBarChart").getContext("2d");
            const myHorizontalBarChart = new Chart(hbar, {
                type: "horizontalBar",
                data: {
                    labels: data[0],
                    datasets: [{
                        label: "# of Votes",
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
    </script>

</body>

</html>