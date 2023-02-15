<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';

// passing true in constructor enables exceptions in PHPMailer

//variables
function setData($a, $b, $c, $d)
{

    $mail = new PHPMailer(true);

    $mySubject = $a;
    $myBody = $b;
    $toEmail = $c;
    $toName = $d;

    try {
        // Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->Username = 'pclualumniportal@gmail.com'; // YOUR gmail email
        $mail->Password = 'kibmnahexhpbwczi'; // YOUR gmail password

        // Sender and recipient settings
        $mail->setFrom('pclualumniportal@gmail.com', 'PCLU Alumni Portal');
        $mail->addAddress($toEmail, $toName);
        $mail->addReplyTo('pclualumniportal@gmail.com', 'PCLU Alumni Portal'); // to set the reply to

        // Setting the email content
        $mail->IsHTML(true);
        $mail->Subject = $mySubject;
        $mail->Body = $myBody;


        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Error " . $e;
        return false;
    }

    return false;
}
