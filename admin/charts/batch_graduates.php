<?php

require_once '../../db/db_config.php';


$sql = "SELECT batch, COUNT(batch) as num from users GROUP BY batch";

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
