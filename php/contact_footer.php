<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
	{

	// Error messages
	$name = $_POST['name'];
	$email = $_POST['email'];
	$comments = $_POST['comments'];
	
	if (trim($name) == '')
		{
		echo '<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! You must enter your name.</div>';
		exit();
		}
	  else
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
		echo '<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please enter a valid email address.</div>';
		exit();
		}
	  else
	if (trim($comments) == '')
		{
		echo '<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Attention! Please enter your message.</div>';
		exit();
		}
	 
	
	  
	

	// Your e-mailadress.
	$recipient = "treeohotels25@gmail.com";

	// Mail subject
	$subject = "You've been contacted by $name";

	// Mail content
	$email_content = "You've been contacted by $name\. $comments \" 
You can contact $name via email, $email";

	// Mail headers
	$email_headers = "From: $name <$email>";

	// Main messages
	if (mail($recipient, $subject, $email_content, $email_headers))
		{
		echo "<h1>Email Sent Successfully!</h1>";
		echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		echo "<p>Thanks <strong>$name</strong>, your message has been sent to us.</p>";
		echo '</div>';
		}
	  else
		{
		echo "<p>Oops! Something went wrong and we couldn't send your message.</p>";
		}
	}
  else
	{
	echo "<p>There was a problem with your submission, please try again.</p>";
	}

?>