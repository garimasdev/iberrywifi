<?php
require('routeros_api.class.php');

$API = new RouterosAPI();

// MikroTik connection details
$routerIP = "139.59.74.160"; // Change to your MikroTik router IP
$username = "email"; // MikroTik username
$password = "Email@898"; // MikroTik password


// Get form data
$email = isset($_POST['email']) ? $_POST['email'] : null;
$phone = isset($_POST['phone']) ? $_POST['phone'] : null;
$message = isset($_POST['message']) ? $_POST['message'] : null;
$verify = isset($_POST['verify']) ? $_POST['verify'] : null;

// Validate form data
if ($email == null || $phone == null || $message == null) {
    echo '<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Attention! All fields are required.</div>';
    header("Location: /contact-01.html?status=error");
    exit();
}
if ($_POST['verify'] != '2')
		{
		echo '<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please give the right answer to the question.</div>';
		exit();
		}

$recipient = "treeohotels25@gmail.com"; // Change to recipient's email
$subject = "Contact Form Submission";
$body = "You have received a new message from the contact form:\n\n".
        "Email: $email\n".
        "Phone: $phone\n\n".
        "Message:\n$message";

// Connect to MikroTik API and send email
if ($API->connect($routerIP, $username, $password, 8736)) {
    
    // Send email command to MikroTik
    $API->write('/tool/e-mail/send', false);
    $API->write("=to={$recipient}", false);
    $API->write("=subject={$subject}", false);
    $API->write("=body={$body}", true);
    
    // Read the response (to ensure the email is sent)
    $API->read();
    
    // Email sent successfully
    header("Location: /contact-01.html?status=success");
    
    // Disconnect from MikroTik
    $API->disconnect();
} else {
    // Failed to connect to MikroTik API
    header("Location: /contact-01.html?status=error");
}
?>
