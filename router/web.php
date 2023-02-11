<?php
session_start();
require_once '../db/db_config.php';
require_once '../classes/news.php';
require_once '../classes/events.php';
require_once '../classes/jobs.php';
require_once '../sendmail.php';
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

if ($type == 'getCode') {
    $email = $_POST['email'];
    if (!isValidEmail($email)) {
        echo 'Failed';
        exit();
    }

    if ($conn->query("SELECT * FROM users where email = '$email'")->num_rows > 0) {
        echo 'Exist';
        exit();
    }


    $code = rand(100000, 999999);
    $_SESSION['email_code'] = $code;

    if (setData('Email Verification', 'Hello ' . $email . ', this is your registration code.<br><br><b>Code: </b>' . $code, $email, $email)) {
        echo 'Success';
        exit();
    }
    echo 'Error';
    exit();
}


function isValidEmail($email)
{
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($pattern, $email) === 1;
}
