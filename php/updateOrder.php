<?php

session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}

require __DIR__ . '/classLib.php';

if(isset($_POST['order_id_submit'])) {
	$_SESSION['order_update_id'] = trim($_POST['order_id']);
	header("Location: updateOrderResult.php");
	exit;
}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System - Update Order</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div id="header">
<?php include "../html/header.html"; ?>
</div>


<div class="container">

<h2 class="mb-2">Update Purchase Order</h2>

<div class="container alert alert-info">
<p>Enter the purchase order number to update below.</p>
</div>

<form action="updateOrder.php" method="post" autocomplete="off">

<div class="form-group" id="order_id">
<input type="text" class="form-control" name="order_id" placeholder="Enter order ID here"><br>
</div>

<div class="form-group" id="order_id_submit">
<input type="submit" class="form-control btn btn-outline-info font-weight-bold" name="order_id_submit" value="Go">
</div>
</form>
</div>

</body>
</html>