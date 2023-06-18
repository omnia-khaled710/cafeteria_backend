<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);




function connectToMailer()
{
    $mail = new PHPMailer(true);
    try {
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'ssl://smtp.gmail.com:465';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'mostafasaeed2311@gmail.com';                     //SMTP username
        $mail->Password   = 'trwjlugzmjbfqnod';                               //SMTP password
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        // $mail->SMTPDebug = 2;



        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        ); //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        // $mail->addReplyTo('gergesvictor512@gmail.com', 'Information');
        // $mail->addCC('gergesvictor512@gmail.com');
        // $mail->addBCC('gergesvictor512@gmail.com');
        $mail->addReplyTo('coffee@gmail.com', 'Information');
        $mail->addCC('coffee@gmail.com');
        $mail->addBCC('coffee@gmail.com');


        return $mail;
    } catch (Exception $e) {
        echo "{$mail->ErrorInfo}";
    }
}
