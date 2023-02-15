<?php
require_once '../../db/db_config.php';

$type = $_POST['type'];
$department = $_POST['department'];
$course = $_POST['course'];
$batch = $_POST['batch'];
$employment = $_POST['employment'];
$where = '';

$where = 'WHERE 1 = 1';
if ($department != '') $where .= " AND c.department_id = '$department'";
if ($course != '') $where .= " AND u.course = '$course'";
if ($batch != '') $where .= " AND u.batch = '$batch'";
if ($employment != '') {
    if ($employment != 2) $where .= " AND u.employment_status !=2";
    else  $where .= " AND u.employment_status = 2";
}

if ($type == 'registered') {
    $where .= ' AND is_verified = 1';
} elseif ($type == 'unverified') {
    $where .= ' AND is_verified = 0';
}

$sql = "SELECT u.id, u.student_id, u.first_name, u.middle_name, u.last_name, u.extension_name, c.description as course, u.batch, u.employment_status, u.is_verified FROM users u INNER JOIN courses c ON u.course = c.id $where";
$usersResult = $conn->query($sql);
?>

<table id="example" class="table table-sm table-striped" style="width:100%">
    <thead>
        <tr valign="top">
            <th>ID</th>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Batch</th>
            <th>Course</th>
            <th>Employment Status</th>
            <th>Account Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>

        <?php

        if ($usersResult->num_rows > 0) {
            $count = 1;
            while ($row = $usersResult->fetch_assoc()) {

                switch ($row['employment_status']) {
                    case '1':
                        $status = '<span class="badge rounded-pill text-bg-success fw-normal">Employed</span>';
                        break;
                    case '2':
                        $status = '<span class="badge rounded-pill text-bg-secondary fw-normal">Unemployed</span>';
                        break;
                    case '3':
                        $status = '<span class="badge rounded-pill text-bg-info fw-normal">Self Employed</span>';
                        break;
                }

                switch ($row['is_verified']) {
                    case '1':
                        $acct = '<span class="badge rounded-pill text-bg-primary fw-normal">Verified</span>';
                        break;
                    case '0':
                        $acct = '<span class="badge rounded-pill text-bg-danger fw-normal">Not Verified</span>';
                        break;
                }
        ?>
                <tr valign="middle">
                    <td>
                        <?= $count++; ?>
                    </td>
                    <td><?= $row['student_id'] ?></td>
                    <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                    <td><?= $row['batch'] ?></td>
                    <td><?= $row['course'] ?></td>
                    <td><?= $status ?></td>
                    <td><?= $acct ?></td>
                    <td>
                        <a href="alumni-view?id=<?= $row['id'] ?>" class="btn text-primary smalltxt fw-bolder">
                            View <i class="fas fa-arrow-right ms-3"></i>
                        </a>
                        <!-- <div class="dropdown">
                          <button class="btn py-0 " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="alumni-view?id=<?= $row['id'] ?>">View</a></li>
                          </ul>
                        </div> -->
                    </td>
                </tr>
        <?php
            }
        }

        ?>


    </tbody>

</table>

<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>