<?php
require_once '../../db/db_config.php';

$type = $_POST['type'];
$department = $_POST['department'];
$course = $_POST['course'];
$batch = $_POST['batch'];
$employment = $_POST['employment'];
$gender = $_POST['gender'];
$civil = $_POST['civil'];
$location = $_POST['location'];
$where = '';

$fields = $_POST['fields'];

$fieldsArr = explode(",", $fields);

if ($fieldsArr[0] == "")
    array_shift($fieldsArr);

$where = 'WHERE birth_date IS NOT NULL';

foreach ($fieldsArr as $field) {
    if ($field == "department") {
        if ($department != '') $where .= " AND c.department_id = '$department'";
    } elseif ($field == "course") {
        if ($course != '') $where .= " AND u.course = '$course'";
    } elseif ($field == "batch") {
        if ($batch != '') $where .= " AND u.batch = '$batch'";
    } elseif ($field == "employment status") {
        if ($employment != '') {
            if ($employment != 2) $where .= " AND u.employment_status !=2";
            else  $where .= " AND u.employment_status = 2";
        }
    } elseif ($field == "gender") {
        if ($gender != '') $where .= " AND u.gender = '$gender'";
    } elseif ($field == "civil status") {
        if ($civil != '') $where .= " AND u.civil_status = '$civil'";
    } elseif ($field == "location") {
        if ($location != '') $where .= " AND province LIKE '%$location%'";
    }
}

if ($type == 'registered') {
    $where .= ' AND is_verified = 1';
} elseif ($type == 'unverified') {
    $where .= ' AND is_verified = 0';
}

$sql = 'SELECT * FROM users WHERE id = -1';
if (count($fieldsArr) > 0) {
    $sql = "SELECT u.id, u.student_id, CONCAT(u.first_name, ' ' , u.middle_name, ' ' , u.last_name , ' ' , u.extension_name) as name, u.extension_name,u.birth_date, u.contact, u.email, d.description as department,current_position as position, u.zip_code, c.description as course, u.batch, u.employment_status as `employment`, u.gender, u.civil_status as `civil`, CONCAT(address_line, ', ' , muncity, ', ' , province) as location, u.is_verified FROM users u INNER JOIN courses c ON u.course = c.id INNER JOIN departments d ON c.department_id = d.id $where";
}

// echo $sql;
$usersResult = $conn->query($sql);
?>
<div class="table-responsive">

    <table id="example" class="table table-sm table-striped" style="width:100%">
        <thead>
            <tr valign="top">
                <?php
                if ($usersResult->num_rows > 0) {
                ?> <th>ID</th>
                <?php
                }
                ?>

                <!-- <th>Student ID</th>
                <th>Full Name</th> -->
                <?php
                foreach ($fieldsArr as $field) {
                    echo "<th>" . ucfirst($field) . "</th>";
                }
                ?>
                <th>Account Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            <?php

            if ($usersResult->num_rows > 0) {
                $count = 1;
                while ($row = $usersResult->fetch_assoc()) {


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
                        <!-- <td><?= $row['student_id'] ?></td>
                        <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td> -->

                        <?php
                        foreach ($fieldsArr as $field) {
                            if ($field == 'employment status') {
                                switch ($row['employment']) {
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
                                echo '<td>' . $status . '</td>';
                            } else if ($field == 'gender') {
                                switch ($row['gender']) {
                                    case '1':
                                        $status = 'Male';
                                        break;
                                    case '2':
                                        $status = 'Female';
                                        break;
                                }
                                echo '<td>' . $status . '</td>';
                            } else  if ($field == 'civil status') {
                                switch ($row['civil']) {
                                    case '1':
                                        $status = 'Single';
                                        break;
                                    case '2':
                                        $status = 'Married';
                                        break;
                                    case '3':
                                        $status = 'Annuled';
                                        break;
                                }
                                echo '<td>' . $status . '</td>';
                            } else {
                                echo ' <td class="text-nowrap">' . $row[$field] . '</td>';
                            }
                        }
                        ?>


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

</div>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>