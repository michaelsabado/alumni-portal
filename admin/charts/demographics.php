<?php

require_once '../../db/db_config.php';


$sql = "SELECT c.description, COUNT(u.id) as num from users u RIGHT JOIN courses c ON u.course = c.id GROUP BY c.description";

$result = $conn->query($sql);

$labels = [];
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($labels, $row['description']);
        array_push($data, $row['num']);
    }
}


echo json_encode([$labels, $data]);
