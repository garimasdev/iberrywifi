<?php
require('routeros_api.class.php');

$API = new RouterosAPI();

$routerIP = "139.59.74.160"; // Change to your MikroTik router IP
$username = "email"; // MikroTik username
$password = "Email@898"; // MikroTik password


// Check if form data exists
$email = isset($_POST['email']) ? $_POST['email'] : null;
$phone = isset($_POST['phone']) ? $_POST['phone'] : null;
$message = isset($_POST['message']) ? $_POST['message'] : null;


$recipient = "support@iberrywifi.in"; // Change to recipient's email
$subject = "Test Email from MikroTik API";
$body = "You have received a new message from the contact form:\n\n".
        "Email: $email\n".
        "Phone: $phone\n\n".
        "Message:\n$message";

if ($API->connect($routerIP, $username, $password, 8736)) {
    
    // Send email command to MikroTik
    $API->write('/tool/e-mail/send', false);
    $API->write("=to={$recipient}", false);
    $API->write("=subject={$subject}", false);
    $API->write("=body={$body}", true);
    
    $API->read();
    
    echo "Email sent successfully!";
    header("Location: /index.html?status=success");
    
    $API->disconnect();
} else {
    echo "Failed to connect to MikroTik API!";
    header("Location: /index.html?status=error");

}
?>
