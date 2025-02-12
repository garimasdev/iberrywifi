<?php
require('routeros_api.class.php');

$API = new RouterosAPI();

// MikroTik connection details
$routerIP = "139.59.74.160"; // Change to your MikroTik router IP
$username = "email"; // MikroTik username
$password = "Email@898"; // MikroTik password


// Get form data
$name = isset($_POST['name']) ? $_POST['name'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$message = isset($_POST['message']) ? $_POST['message'] : null;
$verify = isset($_POST['verify']) ? $_POST['verify'] : null;


// Debugging: Check what is being received from the form
error_log("Name: " . print_r($name, true));
error_log("Email: " . print_r($email, true));
error_log("Message: " . print_r($message, true));
error_log("Verify: " . print_r($verify, true));


// Validate form data
// if ($name == null || $email == null || $message == null) {
//     echo '<div class="alert alert-danger alert-dismissable">
//             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
//             Attention! All fields are required.</div>';
//     header("Location: /contact-01.html?status=error");
//     exit();
// }
// if ($_POST['verify'] != '2')
// 		{
// 		echo '<div class="alert alert-danger alert-dismissable">
//   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please give the right answer to the question.</div>';
// 		exit();
// 		}

// $recipient = "support@iberrywifi.in"; // Change to recipient's email
$recipient = "treeohotels25@gmail.com";
$subject = "Contact Form Submission";
$body = "You have received a new message from the contact form:\n\n".
		"Name: $name\n\n".
        "Email: $email\n".
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
	$response = $API->read();
	error_log("MikroTik API Response: " . print_r($response, true));

    
    // Email sent successfully
    header("Location: /contact-01.html?status=success");
    
    // Disconnect from MikroTik
    $API->disconnect();
} else {
    // Failed to connect to MikroTik API
    header("Location: /contact-01.html?status=error");
}
?>
