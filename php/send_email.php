<?php
require('routeros_api.class.php');

$API = new RouterosAPI();

$routerIP = "139.59.74.160"; // Change to your MikroTik router IP
$username = "email"; // MikroTik username
$password = "Email@898"; // MikroTik password


// Check if honeypot field is filled (spam submission)
if (!empty($_POST['password'])) {
    // Spam detected, exit the script without sending the email
    header("Location: /index.html?status=spam");
    exit;
}

// Check if form data exists
$email = isset($_POST['email']) ? $_POST['email'] : null;
$phone = isset($_POST['phone']) ? $_POST['phone'] : null;
$message = isset($_POST['message']) ? $_POST['message'] : null;


// $recipient = "support@iberrywifi.in"; // Change to recipient's email
$recipient = "treeohotels25@gmail.com"; // Change to recipient's email
$subject = "Contact Form Submission";

$wrapped_message = wordwrap($message, 70, "\n", true);

$body = "You have received a new message from the contact form:\n\n".
        "Email: $email\n".
        "Phone: $phone\n\n".
        "Message:\n$wrapped_message";

if ($API->connect($routerIP, $username, $password, 8736)) {
    
    // Send email command to MikroTik
    $API->write('/tool/e-mail/send', false);
    $API->write("=to={$recipient}", false);
    $API->write("=subject={$subject}", false);
    $API->write("=body={$body}", true);
    
    $API->read();
    
    // echo "Email sent successfully!";
    header("Location: /index.html?status=success");
    exit;
    
    $API->disconnect();
} else {
    // echo "Failed to connect to MikroTik API!";
    header("Location: /index.html?status=error");
    exit;

}
?>
