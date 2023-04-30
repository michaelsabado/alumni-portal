<?php

require_once '../../db/db_config.php';

$from = $_GET['from'];
$to = $_GET['to'];

$sql = "SELECT c.description, 
SUM(CASE WHEN u.employment_status IN (1, 3) THEN 1 ELSE 0 END) as employed_users,
ROUND((SUM(CASE WHEN u.employment_status IN (1, 3) THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as employment_rate 
FROM users u 
RIGHT JOIN courses c ON u.course = c.id WHERE u.batch >= '$from' AND u.batch <= '$to'
GROUP BY c.description";


// 


$result = $conn->query($sql);

$labels = [];
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($labels, $row['description']);
        array_push($data, $row['employment_rate']);
    }
}


echo json_encode([$labels, $data]);
