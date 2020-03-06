<?php
require_once "PHPMailer-5.2-stable/PHPMailerAutoload.php";
$email = "yanislav.vejdarski@gmail.com";
function sendemail($email , $product){

    $mail = new PHPMailer;
//$mail->SMTPDebug = 3;                               // Enable verbose debug output
    $mail->isSMTP();
    $mail->SMTPDebug = 1;// Set mailer to use SMTP
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Host = 'smtp.sendgrid.net';  // Specify main and backup SMTP servers
    $mail->Username = 'yanislav';                 // SMTP username
    $mail->Password = 'Animan1y1!';                           // SMTP password
    $mail->SMTPSecure = 'tsl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('yanislav.vejdarski@gmail.com');
    $mail->addAddress($email);     // Add a recipient
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'Your Product is on Sale !!!';
    $mail->Body    = "$product Product is in Sale Now !!! Go Check it out before the sale expires <b>in bold!</b>";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
}
sendemail($email);
//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