<?php

require_once '../../db/db_config.php';


$sql = "SELECT c.description,SUM(CASE WHEN u.employment_status IN (1, 3) THEN 1 ELSE 0 END) as employed_users,
(SUM(CASE WHEN u.employment_status IN (1, 3) THEN 1 ELSE 0 END) / COUNT(*)) * 100 as employment_rate from users u RIGHT JOIN courses c ON u.course = c.id GROUP BY c.description";

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
