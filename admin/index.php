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
    $from = $_GET['batch-from'];
    $to =  $_GET['batch-to'];
} else {
    $from = (int)(date('Y')) - 5;
    $to = date('Y');
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
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT * FROM users WHERE is_verified = 0 AND birth_date IS NOT NULL")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3 emp">
                            <div class="card-body">
                                <div class="h6 fw-bold">Employed <i class="fas fa-sync text-primary ms-2" onclick="toggleCard(2)"></i></div>
                                <div class="display-5 text-end fw-bolder" onclick="window.location.href='alumni?type=employed'"><?= $conn->query("SELECT * FROM users WHERE employment_status != 2 AND is_verified = 1")->num_rows ?></div>
                            </div>
                        </div>
                        <div class="card dash-card border rounded-3 shadow-sm mb-3 d-none unemp">
                            <div class="card-body">
                                <div class="h6 fw-bold">Unemployed <i class="fas fa-sync text-primary ms-2" onclick="toggleCard(1)"></i></div>
                                <div class="display-5 text-end fw-bolder" onclick="window.location.href='alumni?type=unemployed'"><?= $conn->query("SELECT * FROM users WHERE employment_status = 2 AND is_verified = 1")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3" onclick="window.location.href='alumni?type=batch'">
                            <div class="card-body">
                                <div class="h6 fw-bold">Batches</div>
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT DISTINCT(batch) FROM users WHERE is_verified = 1")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3 male">
                            <div class="card-body">
                                <div class="h6 fw-bold">Gender (Male) <i class="fas fa-sync text-primary ms-2" onclick="toggleCardGender(2)"></i></div>
                                <div class="display-5 text-end fw-bolder" onclick="window.location.href='alumni?type=gender'"><?= $conn->query("SELECT * FROM users WHERE gender = 1 AND is_verified = 1")->num_rows ?></div>
                            </div>
                        </div>
                        <div class="card dash-card border rounded-3 shadow-sm mb-3 d-none female">
                            <div class="card-body">
                                <div class="h6 fw-bold">Gender (Female) <i class="fas fa-sync text-primary ms-2" onclick="toggleCardGender(1)"></i></div>
                                <div class="display-5 text-end fw-bolder" onclick="window.location.href='alumni?type=gender'"><?= $conn->query("SELECT * FROM users WHERE gender = 2 AND is_verified = 1")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3" onclick="window.location.href='news'">
                            <div class="card-body">
                                <div class="h6 fw-bold">News</div>
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT * FROM news")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card dash-card border rounded-3 shadow-sm mb-3" onclick="window.location.href='events'">
                            <div class="card-body">
                                <div class="h6 fw-bold">Events</div>
                                <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT * FROM events")->num_rows ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="px-2">
                            <hr>
                            <div class="h6 fw-bold">Alumni Demographics</div>
                            <?php

                            if ($from > $to) echo '<div class="smalltxt text-danger fw-bold">Batch range is invalid.</div>';

                            ?>

                            <form action="">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="smalltxt mb-1">From</div>
                                        <select name="batch-from" id="batch-from" class="form-select border mb-3">
                                            <?php
                                            for ($current = date('Y'); $current >= 1980; $current--) {
                                                echo '<option value="' . $current . '" ' . (($current == $from) ? 'selected' : '') . '>' . $current . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="smalltxt mb-1">To</div>
                                        <select name="batch-to" id="batch-to" class="form-select border mb-3">
                                            <?php
                                            for ($current = date('Y'); $current >= 1980; $current--) {
                                                echo '<option value="' . $current . '" ' . (($current == $to) ? 'selected' : '') . '>' . $current . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="smalltxt mb-1">
                                            .
                                        </div>
                                        <button type="submit" name="filter" class="btn text-white w-100 bg-primary">Fetch</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="col-md-4 my-chart">
                        <div class="card border-0 mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold mb-4">Department</div>
                                <div class="mx-auto my-auto text-muted d-none" id="d-no">No data available</div>
                                <canvas id="myDepartmentPie" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-chart">
                        <div class="card border-0 mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold mb-4">Course</div>
                                <div class="mx-auto my-auto text-muted d-none" id="co-no">No data available</div>
                                <canvas id="myPieChart" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-chart">
                        <div class="card border-0 mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold mb-4">Employment Status</div>
                                <div class="mx-auto my-auto text-muted d-none" id="em-no">No data available</div>
                                <canvas id="myBarChart" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-chart">
                        <div class="card border-0 mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold mb-4">Gender</div>
                                <div class="mx-auto my-auto text-muted d-none" id="ge-no">No data available</div><canvas id="myGenderPie" width="400" height="400"></canvas>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-chart">
                        <div class="card border-0 mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold mb-4">Civil Status</div>
                                <div class="mx-auto my-auto text-muted d-none" id="ci-no">No data available</div> <canvas id="myCivilPie" width="400" height="400"></canvas>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-chart">
                        <div class="card border-0 mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold mb-4">Batch Graduates</div>
                                <div class="mx-auto my-auto text-muted d-none" id="ba-no">No data available</div>
                                <canvas id="myHorizontalBarChart" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-chart">
                        <div class="card border-0 mb-3">
                            <div class="card-body">

                                <div class="h6 fw-bold mb-4">Location</div>
                                <div class="mx-auto my-auto text-muted d-none" id="lo-no">No data available</div>
                                <canvas id="myLocationBar" width="400" height="800"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-chart">
                        <div class="card border-0 mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold mb-4">Nature of Work</div>
                                <div class="mx-auto my-auto text-muted d-none" id="wo-no">No data available</div> <canvas id="myWorkBar" width="400" height=""></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-chart">
                        <div class="card border-0 mb-3">
                            <div class="card-body">
                                <div class="h6 fw-bold ">Forum</div>
                                <div class="smalltxt text-secondary mb-4">Based from dates Jan 1, <?= $from ?> to Dec 31, <?= $to ?></div>
                                <div class="card dash-card border rounded-3 shadow-sm mb-3" onclick="window.location.href='discussions'">
                                    <div class="card-body">
                                        <div class="h6 fw-bold">Discussion Creator/s</div>
                                        <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT DISTINCT(user_id) FROM posts WHERE date_posted >= '$from-01-01' AND date_posted <= '$to-12-31'")->num_rows ?></div>
                                    </div>
                                </div>
                                <div class="card dash-card border rounded-3 shadow-sm mb-3" onclick="window.location.href='discussions'">
                                    <div class="card-body">
                                        <div class="h6 fw-bold">Commentator/s</div>
                                        <div class="display-5 text-end fw-bolder"><?= $conn->query("SELECT DISTINCT(user_id) FROM comments WHERE date_commented >= '$from-01-01' AND date_commented <= '$to-12-31'")->num_rows ?></div>
                                    </div>
                                </div>
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

        // console.log(colors);




        $.get('charts/demographics', {
            from: '<?= $from ?>',
            to: '<?= $to ?>',
        }, function(response) {
            // console.log(response);
            var data = JSON.parse(response);


            if (data[0].length > 0) {
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
                            xAxes: [{
                                display: false
                            }],
                            yAxes: [{
                                display: false
                            }]
                        }
                    }
                });
            } else {
                $("#co-no").removeClass('d-none')
            }

            var total = data[2][0] + data[2][1];
            var maleP = (data[2][0] / total) * 100;
            var femaleP = (data[2][1] / total) * 100;
            if (total > 0) {
                var genderPie = document.getElementById("myGenderPie").getContext('2d');
                var myGenderPie = new Chart(genderPie, {
                    type: 'pie',
                    data: {
                        labels: [`Male (${maleP.toFixed(1)}%)`, `Female (${femaleP.toFixed(1)}%)`],
                        datasets: [{
                            label: 'Gender',
                            data: data[2],
                            backgroundColor: ['rgba(132,132,216,0.7)', 'rgba(213,111,111,0.7)'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                display: false
                            }],
                            yAxes: [{
                                display: false
                            }]
                        }
                    }
                });
            } else {
                $("#ge-no").removeClass('d-none')
            }
            var total = data[5][0] + data[5][1] + data[5][2];
            var c1 = (data[5][0] / total) * 100;
            var c2 = (data[5][1] / total) * 100;
            var c3 = (data[5][2] / total) * 100;
            if (total > 0) {
                var civilPie = document.getElementById("myCivilPie").getContext('2d');
                var myCivilPie = new Chart(civilPie, {
                    type: 'pie',
                    data: {
                        labels: [`Single (${c1.toFixed(1)}%)`, `Married (${c2.toFixed(1)}%)`, `Annuled (${c3.toFixed(1)}%)`],
                        datasets: [{
                            label: 'Civil Status',
                            data: data[5],
                            backgroundColor: ['rgba(210,175,76,0.7)', 'rgba(184,210,76,0.7)', 'rgba(76,210,148,0.7)'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                display: false
                            }],
                            yAxes: [{
                                display: false
                            }]
                        }
                    }
                });
            } else {
                $("#ci-no").removeClass('d-none')
            }


            if (data[3].length > 0) {
                var depPie = document.getElementById("myDepartmentPie").getContext('2d');


                var myDepartmentPie = new Chart(depPie, {
                    type: 'pie',
                    data: {
                        labels: data[3],
                        datasets: [{
                            label: 'Gender',
                            data: data[4],
                            backgroundColor: colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                display: false
                            }],
                            yAxes: [{
                                display: false
                            }]
                        }
                    }
                });
            } else {
                $("#d-no").removeClass('d-none')
            }
        });
        $.get('charts/employment_rate', {
            from: '<?= $from ?>',
            to: '<?= $to ?>',
        }, function(response) {
            // console.log(response);
            var data = JSON.parse(response);

            if (data[0].length > 0) {
                var bar = document.getElementById("myBarChart").getContext('2d');
                var myBarChart = new Chart(bar, {
                    type: 'bar',
                    data: {
                        labels: data[0],
                        datasets: [{
                            label: 'Employed %',
                            data: data[1],
                            backgroundColor: 'rgba(255, 99, 71, 0.7) ',
                            borderWidth: 1
                        }, {
                            label: 'Unemployed %',
                            data: data[2],
                            backgroundColor: 'rgba(0, 128, 0, 0.7)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                display: true
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 10
                                }
                            }]
                        },
                        // legend: true
                    }
                });
            } else {
                $("#em-no").removeClass('d-none')
            }
        });


        $.get('charts/batch_graduates', {
            from: '<?= $from ?>',
            to: '<?= $to ?>',
        }, function(response) {
            // console.log(response);
            var data = JSON.parse(response);

            if (data[1].length > 0) {
                const hbar = document.getElementById("myHorizontalBarChart").getContext("2d");
                const myHorizontalBarChart = new Chart(hbar, {
                    type: "horizontalBar",
                    data: {
                        labels: data[0],
                        datasets: [{
                            label: "Graduated",
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
                                display: true,
                                ticks: {
                                    beginAtZero: true
                                }

                            }],
                            yAxes: [{
                                display: true,
                            }]
                        },
                    }
                });
            } else {
                $("#ba-no").removeClass('d-none')
            }

        });

        $.get('charts/location', {
            from: '<?= $from ?>',
            to: '<?= $to ?>',
        }, function(response) {
            console.log(response);
            var data = JSON.parse(response);
            var size = data[0].length + 3;
            console.log(size);

            $("#myLocationBar").attr('height', size * 30);
            if (data[1].length > 0) {
                const locationBar = document.getElementById("myLocationBar").getContext("2d");
                const myLocationBar = new Chart(locationBar, {
                    type: "horizontalBar",
                    data: {
                        labels: data[0],
                        datasets: [{
                            label: "Location",
                            data: data[1],
                            backgroundColor: "rgba(43, 229, 240, 0.7)",
                            borderWidth: 1,
                            borderRadius: 10,
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    max: data[4]
                                },
                                display: true
                            }]
                        }
                    }
                });
            } else {
                $("#lo-no").removeClass('d-none')
            }
            $("#myWorkBar").attr('height', size * 30);

            if (data[3].length > 0) {
                const workBar = document.getElementById("myWorkBar").getContext("2d");
                const myWorkBar = new Chart(workBar, {
                    type: "horizontalBar",
                    data: {
                        labels: data[2],
                        datasets: [{
                            label: "Nature of Work",
                            data: data[3],
                            backgroundColor: "rgba(43, 110, 110, 0.7)",
                            borderWidth: 1,
                            borderRadius: 10,
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    max: data[4]
                                },
                                display: true
                            }]
                        }
                    }
                });
            } else {
                $("#wo-no").removeClass('d-none')
            }
        });

        <?php

        if ($from > $to) {

        ?> $(".my-chart").addClass("d-none");
        <?php } ?>

        function toggleCard() {
            $('.emp').toggleClass('d-none');
            $('.unemp').toggleClass('d-none');
        }


        function toggleCardGender() {
            $('.male').toggleClass('d-none');
            $('.female').toggleClass('d-none');
        }
    </script>

</body>

</html>