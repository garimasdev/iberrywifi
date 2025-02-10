<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];

require('routeros_api.class.php');
$API = new RouterosAPI();

$routerIP = "139.59.74.160"; // Change to your MikroTik router IP
$username = "Email"; // MikroTik username
$password = "email@898"; // MikroTik password

$recipient = "support@iberrywifi.in"; // Change to recipient's email
$subject = "Contact Form Submission";
$body = "You have received a new message from the contact form:\n\n".
            "Email: $email\n".
            "Phone: $phone\n\n".
            "Message:\n$message";


if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    if ($API->connect($routerIP, $username, $password, 8736)) {
        
        // Send email command to MikroTik
        $API->write('/tool/e-mail/send', false);
        $API->write("=to={$recipient}", false);
        $API->write("=subject={$subject}", false);
        $API->write("=body={$body}", true);
        
        $API->read();
        $API->disconnect();
        
        echo "Email sent successfully!";
        header("Location: /index.html?status=success");
        
    } else {
        header("Location: /index.html?status=error");
        exit;
    }
}
?>
