<?php

require_once '../../db/db_config.php';

$dep = $_GET['dep'];
$batch = $_GET['batch'];

$where = 'WHERE 1 = 1 ';

if ($dep != 0) $where .= "AND c.department_id = '$dep'";
if ($batch != 'all') $where .= "AND u.batch = '$batch'";


$sql = "SELECT u.batch, COUNT(u.batch) as num from users u inner join courses c on u.course = c.id $where GROUP BY batch";


$result = $conn->query($sql);

$labels = [];
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($labels, $row['batch']);
        array_push($data, $row['num']);
    }
}


echo json_encode([$labels, $data]);
