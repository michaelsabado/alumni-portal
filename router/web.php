<?php
require_once '../db/db_config.php';
require_once '../classes/news.php';
require_once '../classes/events.php';
require_once '../classes/jobs.php';
// get request
$type = $_POST['type'];


if ($type == 'getNews') {
    $id = $_POST['id'];
    $res = News::getNews($id);
    echo json_encode($res->fetch_assoc());
}

if ($type == 'getJob') {
    $id = $_POST['id'];
    $res = Jobs::getJob($id);
    echo json_encode($res->fetch_assoc());
}

if ($type == 'getEvent') {
    $id = $_POST['id'];
    $res = Events::getEvent($id);
    echo json_encode($res->fetch_assoc());
}

if ($type == 'toggleStatus') {
    $id = $_POST['id'];
    $val = $_POST['val'];
    $res = Jobs::setStatus($id, $val);
    echo json_encode($res);
}

if ($type == 'searchAlumni') {
    $key = mysqli_real_escape_string($conn, $_POST['key']);
    $sql = "SELECT * FROM users inner join courses on users.course = courses.id WHERE first_name LIKE '%$key%' OR last_name LIKE '%$key%' OR (CONCAT(first_name, ' ', last_name) LIKE '%$key%') LIMIT 5";
    $res = mysqli_query($conn, $sql);
    echo json_encode($res->fetch_all());
}
