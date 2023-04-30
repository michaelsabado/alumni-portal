<?php

require_once '../../db/db_config.php';


$from = $_GET['from'];
$to = $_GET['to'];

$sql = "SELECT u.batch, COUNT(u.batch) as num from users u inner join courses c on u.course = c.id WHERE u.batch >= '$from' AND u.batch <= '$to' GROUP BY batch";


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
