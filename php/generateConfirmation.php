<?php

session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}

require __DIR__ . '/classLib.php';

// declare variables for use
$orderDetails = "";
$errorMessages = array();
$orderID = null;

if (isset($_POST['confirmation_submit'])) {
	if (!empty($_POST['order_id'])) {
		$orderID = trim($_POST['order_id']);
		$orderConfirmation = new OrderConfirmation($orderID);
		$orderDetails = $orderConfirmation->generateConfirmation();
		if (!empty($orderDetails)) {
		// pass order details in to a session variable so it can be accessed on other pages
		$_SESSION['order_details'] = $orderDetails;
		header("Location: generateConfirmationResult.php");
		exit;
	}
		else {
			array_push($errorMessages, "Purchase order ID not found.");
		}
	}
	else {
		array_push($errorMessages, "An order ID is required.");
	}


}




?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System - Generate confirmation</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="header">
<?php include "../html/header.html"; ?>
</div>


<div class="container">
<h2>Generate Purchase Order Confirmation</h2>

<div class="alert alert-info mx-auto center-block" role="alert" style="width: 600px;">
<p>Enter the order ID for the purchase order below.  To return to the homepage from the confirmation, click the logo in the top right corner.</p>
<p class="font-weight-bold">The order will be marked as confirmed after a purchase order has been submitted.</p>
</div>


<div id="form">
<form action="generateConfirmation.php" method="post" autocomplete="off">
<div class="form-group" id="order_id">
<input type="text" class="form-control" name="order_id" placeholder="Enter order ID here"><br>
</div>
<div class="form-group" id="confirmation_submit">
<input type="submit" class="form-control btn btn-outline-info font-weight-bold" name="confirmation_submit" value="Go">
<?php
if(!empty($errorMessages)) {
?>
	<div class="alert alert-danger rounded mt-1"><?php foreach ($errorMessages as $errorMessage) {echo $errorMessage; echo "<br>";} ?>
<?php
}
?>
</div>

</form>
</div>


</div>



</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</html>