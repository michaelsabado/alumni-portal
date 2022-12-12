<?php
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
