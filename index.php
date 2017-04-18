<?php
session_start(); // starts a PHP session


// include the classes file and create a new object of the class
require __DIR__ . '/php/classLib.php';
$func = new userLogin();

// define an empty variable to be used for error messages
$errorMessage = '';

// check to see if the login button has been pressed
if(!empty($_POST['submit'])) {
	if(isset($_POST['username'])) {
		// isset is used to check for a value in the $_POST array['username']
		// trim used to remove empty chars
		// setting the $username variable to the value of the username entered in the form
		$username = trim($_POST['username']);
	}
	if(isset($_POST['password'])) {
		$password = trim($_POST['password']);
	}
	
	// validation for checking if a username or password has been entered in the form
	// if not, an error is displayed
	if ($username == "") {
		$errorMessage = "Please enter a username";
	}
	elseif ($password == "") {
		$errorMessage = "Please enter a password";
	}
	else { // if both a username or password is entered:
		$userID = $func->processLogin($username, $password); // userID is an object of the processLogin class
		if ($userID > 0) {
			$_SESSION['user_id'] = $userID; // set the $_SESSION value 'user_id' equal to the user's ID to show the login is valid
			header("Location: php/welcome.php");  // redirects to the member pages after successful login
			exit; // terminates current page if login is successful
		}
		else {
			// if login is unsuccessful (invalid credentials provided), return an error
			$errorMessage = "Invalid username or password.  Please try again."; 
		}
	}
}

// if the user is already logged in, redirect them to the welcome page instead of asking them to log in again
if(isset($_SESSION['user_id'])) {
	header("Location: php/welcome.php");
	exit;
}

?>



<!DOCTYPE html>
<!-- Main login page HTML -->
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System Login</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<!-- Title for login page -->
<h1 align="center">Purchase Order System
<small class="text-muted">Login Page</small>
</h1>

<!-- Form for login -->
<div id="loginForm" class="container mx-auto center-block" style="width: 500px;">
<form action="index.php" method="post" class="px-2"> <!-- action is where to send the form, post is the method of sending -->
	<div class="form-group" id="loginFormUsername">
	<label>Enter Username</label><br>
	<!-- autofocus is used to highlight the first input, required is used to not allow the form to be submitted until there is a value in the field -->
	<input type="text" class="form-control" name="username" placeholder="Enter username here" autofocus required><br>
	</div>
	<div class="form-group" id="loginFormPassword">
	<label>Enter Password</label><br>
	<input type="password" class="form-control" name="password" placeholder="Enter password here" required><br>
	</div>
	<input type="submit" name="submit" value="Login">
	<?php 
	if(!empty($errorMessage)) {
	?>
		<div class="alert alert-danger rounded mt-1"><?php echo $errorMessage; ?>
	<?php
	}
	?>
</form>

</div>

<address class="footer navbar-fixed-bottom"> <!-- Address used for mailto: email address in footer -->
<footer class="footer navbar-fixed-bottom px-1">
<p>Developed by Jordan Bolton for Accordial Manufacturing Ltd.</p>
<p>Contact: <a href="mailto:jordanbolton1999@gmail.com">jordanbolton1999@gmail.com</a></p>
</footer>
</address>
</body>
<!-- bootstrap scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>