<?php

require __DIR__ . '/php/classLib.php';

if (isset($_POST['register_submit'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$forename = trim($_POST['forename']);
	$surname = trim($_POST['surname']);
	$user = new registerUser($username, $password, $forename, $surname);
	if ($user->registerUser() > 0) {
		echo "User added successfully";
	}
}




?>

<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
</head>
<body>
<h2>Register Page</h2>
<form action="register.php" method="post">
<input type="text" name="username" placeholder="Enter username here">
<input type="password" name="password" placeholder="Enter password here">
<input type="text" name="forename" placeholder="Enter forename here">
<input type="text" name="surname" placeholder="Enter surname here">
<input type="submit" name="register_submit">
</form>

<p>Verify login here:</p>
<a href="index.php">Verify Login</a>

</body>
</html>