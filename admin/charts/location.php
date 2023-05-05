<?php

require_once '../../db/db_config.php';


$from = $_GET['from'];
$to = $_GET['to'];

$sql = "SELECT DISTINCT(CONCAT(province, ', ', muncity, ', ', address_line)) as location, COUNT(id) as total   FROM `users` WHERE batch >= '$from' AND batch <= '$to' AND is_verified = 1 GROUP BY location";

$result = $conn->query($sql);

$labels = [];
$data = [];
$natures = [];
$naturesCount = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($labels, $row['location']);
        array_push($data, $row['total']);
    }
}

$sql = "SELECT DISTINCT(nature_of_work) as nature, COUNT(id) as total  FROM `users` WHERE batch >= '$from' AND batch <= '$to' AND is_verified = 1 AND (nature_of_work IS NOT NULL OR nature_of_work != '') GROUP BY nature ORDER BY nature";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row1 = $result->fetch_assoc()) {
        if ($row1['nature'] != '') array_push($natures, $row1['nature']);
        else array_push($natures, 'Not set');
        array_push($naturesCount, $row1['total']);
    }
}
echo json_encode([$labels, $data, $natures, $naturesCount]);
