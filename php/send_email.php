<?php
require('routeros_api.class.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    
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

    if ($API->connect($routerIP, $username, $password,8736)) {
        
        // Send email command to MikroTik
        $API->write('/tool/e-mail/send', false);
        $API->write("=to={$recipient}", false);
        $API->write("=subject={$subject}", false);
        $API->write("=body={$body}", true);
        
        $API->read();
        
        echo "Email sent successfully!";
        
        $API->disconnect();
    } else {
        echo "Failed to connect to MikroTik API!";
    }
?>
