<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'owellvennekimseguban58@gmail.com'; // User will need to update this
        $mail->Password   = 'zhmb baeo jluz stiz'; // User will need to update this
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('owellvennekimseguban58@gmail.com', 'ExpiryGuard');
        $mail->addAddress($to);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
