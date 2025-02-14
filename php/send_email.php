<?php
require('routeros_api.class.php');

$API = new RouterosAPI();

$routerIP = "139.59.74.160"; // Change to your MikroTik router IP
$username = "email"; // MikroTik username
$password = "Email@898"; // MikroTik password


$recaptcha_secret = '6Lfw6dUqAAAAAMwaKEwgJ4OVLx5_dqPoGDdO9Vq-';
$recaptcha_response = $_POST['g-recaptcha-response'];

$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
$response_keys = json_decode($response, true);

if(intval($response_keys["success"]) !== 1) {
    // CAPTCHA failed
    header("Location: /index.html?status=error");
    exit;
}



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

$phone = preg_replace('/\D/', '', $phone);

if (strlen($phone) > 10) {
    echo "Phone number cannot exceed 10 digits.";
    exit; // Stop further processing
}


// $recipient = "support@iberrywifi.in"; // Change to recipient's email
$recipient = "treeohotels25@gmail.com"; // Change to recipient's email
$subject = "Contact Form Submission";

// $wrapped_message = wordwrap($message, 70, "\n", true);

$body = "Short: You have received a new message from the short contact form:\n\n".
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
