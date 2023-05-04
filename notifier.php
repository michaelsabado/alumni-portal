<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';
require_once 'db/db_config.php';

// passing true in constructor enables exceptions in PHPMailer

//variables
function notify($type, $id)
{
    global $conn;

    $mail = new PHPMailer(true);
    $localhost = '/alumni-portal';
    switch ($type) {
        case 1:
            $mySubject = 'Latest News';
            $link = $_SERVER['SERVER_NAME'] . $localhost . '/client/read?type=news&id=' . $id;

            $sql = "SELECT * FROM news WHERE id = $id";
            $data = $conn->query($sql)->fetch_assoc();

            $myBody = 'Hello,<br>There is a posted news today with a title ' . $data['title'] . ' <br>Check it out <a href="' . $link . '">here</a>.';
            break;
        case 2:
            $mySubject = 'New Event';
            $link = $_SERVER['SERVER_NAME'] . $localhost . '/client/read?type=events&id=' . $id;

            $sql = "SELECT * FROM events WHERE id = $id";
            $data = $conn->query($sql)->fetch_assoc();

            $myBody = 'Hello,<br>There is a new event posted today with a title ' . $data['title'] . ' <br>Check it out <a href="' . $link . '">here</a>.';
            break;
        case 3:
            $mySubject = "New Post";
            $link = $_SERVER['SERVER_NAME'] . $localhost . '/client/view-post?id=' . $id;

            $sql = "SELECT * FROM posts WHERE id = $id";
            $data = $conn->query($sql)->fetch_assoc();

            $myBody = 'Hello,<br>There is a new post today with a title ' . $data['title'] . ' <br>Check it out <a href="' . $link . '">here</a>.';
            break;
        case 4:
            $mySubject = "New Job Post";
            $link = $_SERVER['SERVER_NAME'] . $localhost . '/client/view-job?id=' . $id;

            $sql = "SELECT * FROM jobs WHERE id = $id";
            $data = $conn->query($sql)->fetch_assoc();

            $myBody = 'Hello,<br>There is a new job posted today with a title ' . $data['title'] . ' <br>Check it out <a href="' . $link . '">here</a>.';
            break;
    }

    $sql = "SELECT * FROM users WHERE is_verified = 1";
    $users = $conn->query($sql);

    if ($users->num_rows > 0) {

        try {

            //FOR GMAIL ACCOUNT

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->Username = 'almatechpclu.2023@gmail.com'; // YOUR gmail email
            $mail->Password = 'hhcbwmpverbvvail'; // YOUR gmail password

            // Sender and recipient settings
            $mail->setFrom('almatechpclu.2023@gmail.com', 'PCLU Alumni Portal');


            while ($row = $users->fetch_assoc()) {
                $mail->addAddress($row['email'], $row['first_name'] . ' ' . $row['last_name']);
            }





            // Setting the email content
            $mail->IsHTML(true);
            $mail->Subject = $mySubject;
            $mail->Body = $myBody;

            $mail->send();
            return true;
        } catch (Exception $e) {
            // var_dump($mail->ErrorInfo);
            return false;
        }
    }

    return false;
}
