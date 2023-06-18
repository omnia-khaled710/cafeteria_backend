<?php

require('mailer.php');
require('../../handle.php');
require('../../cors.php');

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $data = json_decode(file_get_contents('php://input'));


    $email = $data->email;

    $db = new Database();


    $stmts = $db->getrow('', "select email,password from users where email = ?", [$email]);


    $row = $stmts->fetch();


    if ($row) {


        $mail = connectToMailer();
        $mail->setFrom('mostafasaeed2311@gmail.com', 'Mostafa');
        $mail->addAddress($row['email'], 'user');

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->CharSet = "UTF-8";
        $mail->Subject = 'reset password';
        $mail->Body    = "<h1> your password is {$row['password']}  <h1>";

        try {


            $mail->send();

            echo json_encode('sent successfully');
        } catch (Exception $e) {
            echo $e;
        }
    }
}
