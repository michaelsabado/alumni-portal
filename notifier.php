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

            $myBody = 'Dear Alumnus,<br>We hope this email finds you well. We are excited to inform you that there is a new update on the Alumni Portal that we think you will find interesting. The latest news has been posted on the portal. We encourage you to log in to the portal to read the full article and stay updated on the latest news related to our alumni community.<br><br>Thank you for being a part of our alumni network, and we hope you find this information helpful.<br><br>Best regards,<br>PCLU ';
            break;
        case 2:
            $mySubject = 'New Event';
            $link = $_SERVER['SERVER_NAME'] . $localhost . '/client/read?type=events&id=' . $id;

            $sql = "SELECT * FROM events WHERE id = $id";
            $data = $conn->query($sql)->fetch_assoc();

            $myBody = 'Dear Alumnus,<br>We hope this email finds you well. We are excited to inform you that a new alumni event has been posted on the Alumni Portal.<br>We value your participation in our alumni community and hope that you will be able to join us for this exciting event.<br><br>Thank you for your continued support, and we look forward to seeing you at the event.<br><br>Best regards,<br>PCLU ';
            break;
        case 3:
            $mySubject = "New Post";
            $link = $_SERVER['SERVER_NAME'] . $localhost . '/client/view-post?id=' . $id;

            $sql = "SELECT * FROM posts WHERE id = $id";
            $data = $conn->query($sql)->fetch_assoc();

            $myBody = 'Dear Alumnus,<br>We hope this email finds you well. We are excited to inform you that a new discussion forum has been added to the Alumni Portal. We encourage you to log in to the portal to participate in the discussion and share your insights with fellow alumni.<br><br>We value your membership in our alumni community and believe that the discussion forum will provide a valuable platform for exchanging ideas and networking. <br><br>Thank you for being a part of our alumni network, and we look forward to seeing you in the discussion forum.<br><br>Best regards,<br>PCLU';
            break;
        case 4:
            $mySubject = "New Job Post";
            $link = $_SERVER['SERVER_NAME'] . $localhost . '/client/view-job?id=' . $id;

            $sql = "SELECT * FROM jobs WHERE id = $id";
            $data = $conn->query($sql)->fetch_assoc();

            $myBody = 'Dear Alumnus,<br>We hope this email finds you well. We are excited to inform you that a new job posting has been added to the Alumni Portal. We encourage you to log in to the portal to see the full details and apply if you are interested.<br><br>We value your membership in our alumni community and hope that this job posting is useful for your career development.<br><br>Thank you for being a part of our alumni network, and we wish you the best of luck in your job search.<br><br>Best regards,<br>PCLU ';
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
