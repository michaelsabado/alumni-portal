<?php

require_once '../../db/db_config.php';

$dep = $_GET['dep'];
$course = $_GET['course'];
$batch = $_GET['batch'];
$employment = $_GET['employment'];

$where = 'WHERE is_verified = 1 ';

if ($dep != 0) $where .= "AND c.department_id = '$dep'";
if ($course != 'all') $where .= "AND u.course = '$course'";
if ($batch != 'all') $where .= "AND u.batch = '$batch'";
switch ($employment) {
    case 1:
        $where .= "AND u.employment_status != 2";
        break;
    case 2:
        $where .= "AND u.employment_status = 2";
        break;
}
$sql = "SELECT c.description, COUNT(u.id) as num from users u RIGHT JOIN courses c ON u.course = c.id  $where GROUP BY c.description";

// echo $sql;

$result = $conn->query($sql);

$labels = [];
$data = [];

$total = $conn->query("SELECT * FROM users u RIGHT JOIN courses c ON u.course = c.id  $where")->num_rows;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($labels, $row['description'] . ' (' . number_format(($row['num'] / $total) * 100, 1) . '%)');
        array_push($data, $row['num']);
    }
}


echo json_encode([$labels, $data]);
