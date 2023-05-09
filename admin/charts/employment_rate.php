<?php

require_once '../../db/db_config.php';

$from = $_GET['from'];
$to = $_GET['to'];


$sql = "SELECT c.description, 
ROUND((SUM(CASE WHEN u.employment_status = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as employed_users,
ROUND((SUM(CASE WHEN u.employment_status = 2 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as unemployed_users
FROM users u 
RIGHT JOIN courses c ON u.course = c.id WHERE u.batch >= '$from' AND u.batch <= '$to' AND u.is_verified = 1
GROUP BY c.description";

// 


$result = $conn->query($sql);

$labels = [];
$emp = [];
$unemp = [];
$ave = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($labels, $row['description']);
        array_push($emp, $row['employed_users']);
        array_push($unemp, $row['unemployed_users']);
        // . ' (' . number_format(($row['employed_users'] / $row['total_users']) * 100, 1) . '%)'
    }
}


echo json_encode([$labels, $emp, $unemp]);
