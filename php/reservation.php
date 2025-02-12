<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
	// Error messages
	$email = $_POST['email'];

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
	// $to = "support@iberrywifi.in";
	$to = "treeohotels25@gmail.com";

	// Mail subject
	$subject = "Good news! A reservation has been requested by $email";

	// Mail content
	$email_content = "Good news! A reservation has been requested by $email <br>

The customer wants to check-in at: $checkin <br>
and check-out at: $checkout<br>

The customer requested a $room room for $adults adult(s) and $children child(ren).<br>

You can contact the customer via email, $email or hit 'reply' in your email browser to make the reservation complete.
";
	$message = wordwrap($email_content, 70);
	// Mail headers
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: '.$email. "\r\n";
	
	// Main messages
	if (mail($to,$subject,$message,$headers))
		{
		echo "<h1>Reservation sent successfully!</h1>";
		echo "<p>Thank you, your reservation has been submitted to us and we'll contact you as quickly as possible to complete your booking.</p>";
		}
	  else
		{
		echo "<p>Oops! Something went wrong and we couldn't send your reservation.</p>";
		}
	}
  else
	{
	echo "<p>There was a problem with your submission, please try again.</p>";
	}

?>