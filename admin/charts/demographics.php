<?php

require_once '../../db/db_config.php';

$from = $_GET['from'];
$to = $_GET['to'];

$sql = "SELECT c.description, COUNT(u.id) as num from users u RIGHT JOIN courses c ON u.course = c.id  WHERE u.batch >= '$from' AND u.batch <= '$to' AND u.is_verified = 1 GROUP BY c.description";

// echo $sql;

$result = $conn->query($sql);

$labels = [];
$data = [];

$deps = [];
$depsCount = [];
$sqldep = $conn->query("SELECT DISTINCT(d.`description`), COUNT(u.`id`) as total FROM users u INNER JOIN courses c ON u.course = c.id INNER JOIN departments d ON c.department_id = d.id  WHERE u.batch >= '$from' AND u.batch <= '$to' AND u.is_verified = 1 GROUP BY d.description");

if ($sqldep->num_rows > 0) {
    while ($row1 = $sqldep->fetch_assoc()) {
        array_push($deps, $row1['description']);
        array_push($depsCount, $row1['total']);
    }
}


$total = $conn->query("SELECT * FROM users u RIGHT JOIN courses c ON u.course = c.id  WHERE u.batch >= '$from' AND u.batch <= '$to' AND u.is_verified = 1")->num_rows;

if ($result->num_rows > 0) {
  
    while ($row = $result->fetch_assoc()) {
        array_push($labels, $row['description'] . ' (' . number_format(($row['num'] / $total) * 100, 1) . '%)');
        array_push($data, $row['num']);
    }
}



$totalMale = $conn->query("SELECT * FROM users u WHERE gender = 1 AND u.batch >= '$from' AND u.batch <= '$to' AND u.is_verified = 1")->num_rows;
$totalFemale = $conn->query("SELECT * FROM users u WHERE gender = 1 AND u.batch >= '$from' AND u.batch <= '$to' AND u.is_verified = 1")->num_rows;

$civilStatus = [];

$c1 = $conn->query("SELECT * FROM users u WHERE civil_status = 1 AND u.batch >= '$from' AND u.batch <= '$to' AND u.is_verified = 1")->num_rows;
$c2 = $conn->query("SELECT * FROM users u WHERE civil_status = 2 AND u.batch >= '$from' AND u.batch <= '$to' AND u.is_verified = 1")->num_rows;
$c3 = $conn->query("SELECT * FROM users u WHERE civil_status = 3 AND u.batch >= '$from' AND u.batch <= '$to' AND u.is_verified = 1")->num_rows;


echo json_encode([$labels, $data, [$totalMale, $totalFemale], $deps, $depsCount, [$c1, $c2, $c3]]);
