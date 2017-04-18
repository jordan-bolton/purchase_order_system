<?php
session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}

require __DIR__ . '/classLib.php';

// declare variables for use
$errorMessage = "";
$deleteOrder = null;

if (isset($_POST['form_submit'])) {
	if (!empty($_POST['order_id'])) {
		$order_id = trim($_POST['order_id']);
		$order = new Order();
		$deleteOrder = $order->deleteOrder($order_id);
		if ($deleteOrder != 1) {
			$errorMessage = "A valid order ID is required.";
		}
	}
	if ($deleteOrder == 1) {
		$successMessage = "Order successfully deleted.";
	}

}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System - Detele Order</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div id="header">
<?php include "../html/header.html"; ?>
</div>

<div class="container">

<h3>Delete Purchase Order</h3>

<div class="alert alert-info mx-auto center-block mt-1" role="alert" style="width: 600px;">
<p>Enter the order ID for the purchase order below.  Click submit to delete.</p>
</div>

<div id="form">

<form action="deleteOrder.php" method="post" autocomplete="off">
<div class="form-group" id="order_id">
<input type="text" class="form-control" name="order_id" placeholder="Enter order ID here"><br>
</div>
<div class="form-group" id="form_submit">
<input type="submit" class="form-control btn btn-outline-info font-weight-bold" name="form_submit" value="Submit">
<?php
if(!empty($errorMessage)) {
?>
	<div class="alert alert-danger rounded mt-1"><?php echo $errorMessage; ?>
<?php
}
?>

<?php if (!empty($successMessage)) {
?>
	<div class="alert alert-success rounded mt-1"><?php echo $successMessage; ?>
<?php
}
?>
</div>

</form>

</div>

</div>
</body>
</html>