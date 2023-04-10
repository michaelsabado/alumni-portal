<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';

// passing true in constructor enables exceptions in PHPMailer

//variables
function setData($a, $b, $c, $d, $e = null, $app = null)
{

    $mail = new PHPMailer(true);

    $mySubject = $a;
    $myBody = $b;
    $toEmail = $c;
    $toName = $d;

    try {
        // Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;


        //FOR MAIL TRAP

        // $mail->isSMTP();
        // $mail->Host = 'sandbox.smtp.mailtrap.io';
        // $mail->SMTPAuth = true;
        // $mail->Port = 2525;
        // $mail->Username = '7bbcdd4a9cb05c';
        // $mail->Password = '71277b41b355a5';



        //FOR GMAIL ACCOUNT

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->Username = 'almatechpclu.2023@gmail.com'; // YOUR gmail email
        $mail->Password = 'hhcbwmpverbvvail'; // YOUR gmail password







        // Sender and recipient settings
        $mail->setFrom('almatechpclu@gmail.com', 'PCLU Alumni Portal');
        $mail->addAddress($toEmail, $toName);
        if ($e !== null) {
            $mail->addReplyTo($app, $app); // to set the reply to
        } else {
            $mail->addReplyTo('almatechpclu@gmail.com', 'PCLU Alumni Portal'); // to set the reply to
        }


        // Setting the email content
        $mail->IsHTML(true);
        $mail->Subject = $mySubject;
        $mail->Body = $myBody;


        if ($e != null) {
            // Add the PDF attachment       
            $mail->setFrom($_SESSION['user_info']['email'],  $_SESSION['user_info']['full_name']);
            $mail->addAttachment($e, $_SESSION['user_info']['full_name'] . ' Resume.pdf');
        }


        $mail->send();
        return true;
    } catch (Exception $e) {
        // var_dump($mail->ErrorInfo);
        return false;
    }

    return false;
}
