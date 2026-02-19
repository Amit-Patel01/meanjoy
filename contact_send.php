<?php
include 'includes/session.php';
include 'includes/mail_config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    $_SESSION['error'] = 'Invalid request.';
    header('location: contact.php');
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'Contact Form';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if(empty($name) || empty($email) || empty($message)){
    $_SESSION['error'] = 'Please fill all required fields.';
    header('location: contact.php');
    exit;
}

$mail = new PHPMailer(true);
try{
    //Server settings
    $mail->isSMTP();
    $mail->Host = MAIL_HOST;
    $mail->SMTPAuth = MAIL_SMTP_AUTH;
    $mail->Username = MAIL_USERNAME;
    $mail->Password = MAIL_PASSWORD;
    $mail->SMTPSecure = MAIL_SMTPSECURE;
    $mail->Port = MAIL_PORT;

    //Recipients
    $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
    $mail->addAddress(MAIL_FROM_EMAIL); // send to site admin
    $mail->addReplyTo($email, $name);

    //Content
    $mail->isHTML(true);
    $mail->Subject = '[Contact] ' . $subject;
    $body = "<p>You have received a new message from the contact form on your website.</p>";
    $body .= "<p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>";
    $body .= "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    $body .= "<p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>";

    $mail->Body = $body;

    $mail->send();
    $_SESSION['success'] = 'Message sent successfully. We will get back to you soon.';
    header('location: contact.php');
    exit;
}
catch(Exception $e){
    $_SESSION['error'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    header('location: contact.php');
    exit;
}

?>
