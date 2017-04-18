<?php

session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}

require __DIR__ . "/classLib.php";

$successMessages = $_SESSION['update_success_messages'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System - Update Success</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="footer">
<?php include "../html/header.html"; ?>
</div>

<div class="container">
<h2>Update Purchase Order - Success</h2>

<div class="container alert alert-success">
<p>The purchase order has been updated successfully.  Below are a list of the changes made.</p>
<p>To return back to the welcome page, click the logo in the header or  <strong><a href="welcome.php">click here</a></strong></p>
</div>

<?php
if (!empty($successMessages)) {
?>
	<div class="alert alert-info rounded mt-1 font-weight-bold"><?php foreach ($successMessages as $successMessage) {echo "<li>"; echo  nl2br($successMessage); echo "</li>";}; ?>
<?php
}
?>



</div>

</body>
</html>
