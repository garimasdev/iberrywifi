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

if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
	// Error messages
	$email = $_POST['email'];
	$phone = $_POST['phone'];

	$checkin = $_POST['checkin'];
	$checkout = $_POST['checkout'];
	$room = $_POST['room'];
	$adults = $_POST['adults'];
	$children = $_POST['children'];
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
		echo '<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please enter a valid email address.</div>';
		exit();
		}
	else if (trim($phone) == '') 
	{
		echo '<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please enter your phone number.</div>';
		exit();
	}
	  else
	if (trim($room) == '')
		{
		echo '<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please enter a your room.</div>';
		exit();
		}
	  else
	if (trim($checkin) == '')
		{
		echo '<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please enter your check-in date.</div>';
		exit();
		}
	  else
	if (trim($checkout) == '')
		{
		echo '<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please enter your check-out date.</div>';
		exit();
		}

	// Your e-mailadress.
	// $recipient = "treeohotels25@gmail.com";
	$recipient = "support@iberrywifi.in";
	// $recipient = "hoteltheyellow@gmail.com";

	// Mail subject
	$subject = "Good news! A reservation has been requested by $email";

	// Mail content
	$email_content = "
		Good news! A reservation has been requested by $email
		The customer can be contacted at: $phone
		The customer wants to check-in at: $checkin
		and check-out at: $checkout
		The customer requested a $room room for $adults adult(s) and $children child(ren).
		You can contact the customer via email, $email or hit 'reply' in your email browser to make the reservation complete.
		";

	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: {$email}" . "\r\n";
	// $wrapped_message = wordwrap($email_content, 70, "\n", true);

	
	if ($API->connect($routerIP, $username, $password, 8736)) {
    
		// Send email command to MikroTik
		$API->write('/tool/e-mail/send', false);
		$API->write("=to={$recipient}", false);
		$API->write("=subject={$subject}", false);
		$API->write("=body={$email_content}", true);
		// $API->write("=headers={$headers}", true);
		$API->read();
		
		echo "<h1>Reservation sent successfully!</h1>";
		echo "<p>Thank you, your reservation has been submitted to us and we'll contact you as quickly as possible to complete your booking.</p>";
		$API->disconnect();
	} else {
		echo "<p>Oops! Something went wrong and we couldn't send your reservation.</p>";
	}
	// Main messages
	// if (mail($to,$subject,$message,$headers))
	// 	{
	// 	echo "<h1>Reservation sent successfully!</h1>";
	// 	echo "<p>Thank you, your reservation has been submitted to us and we'll contact you as quickly as possible to complete your booking.</p>";
	// 	}
	//   else
	// 	{
	// 	echo "<p>Oops! Something went wrong and we couldn't send your reservation.</p>";
	// 	}
	}
  else
	{
	echo "<p>There was a problem with your submission, please try again.</p>";
	}

?>