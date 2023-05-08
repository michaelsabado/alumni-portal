<?php

require_once '../../db/db_config.php';


$from = $_GET['from'];
$to = $_GET['to'];

$total = $conn->query("SELECT * FROM users u RIGHT JOIN courses c ON u.course = c.id  WHERE u.batch >= '$from' AND u.batch <= '$to' AND u.is_verified = 1")->num_rows;

$sql = "SELECT u.batch, COUNT(u.batch) as num from users u inner join courses c on u.course = c.id WHERE u.batch >= '$from' AND u.batch <= '$to' AND u.is_verified = 1 GROUP BY batch";


$result = $conn->query($sql);

$labels = [];
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($labels, $row['batch']);
        array_push($data, $row['num']);
        //  . ' (' . number_format(($row['num'] / $total) * 100, 1) . '%)'
    }
}


echo json_encode([$labels, $data]);
