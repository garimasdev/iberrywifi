<?php
// Include PHPMailer files
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';
require 'PHPMailer/PHPMailer-master/src/Exception.php';

// Create instance of PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();

// Set mailer to use SMTP
$mail->isSMTP();

// SMTP server configuration
$mail->Host = 'smtp.gmail.com'; // Use Gmail SMTP server
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->Username = 'sachevagarima25@gmail.com'; // Your Gmail email address
$mail->Password = '9999801288@Hp'; // Your Gmail password (use app-specific password if you have 2FA enabled)
$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
$mail->Port = 587; // SMTP port for Gmail

// Sender and recipient information
$mail->setFrom('sachevagarima25@gmail.com', 'Demo'); // Your email and name
$mail->addAddress('garimasacheva25@gmail.com'); // Recipient's email address

// Email subject and body content
$mail->Subject = 'Test Email from PHPMailer';
$mail->Body    = 'Hi, this is a test email from PHPMailer!';

// Send the email and check for success
if($mail->send()) {
    echo 'Message has been sent';
} else {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
?>
