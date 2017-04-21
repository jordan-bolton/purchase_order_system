<?php
session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}



require __DIR__ . '/classLib.php';
$func = new userLogin($username, $password);
$userDetails = $func->getUserDetails($_SESSION['user_id']);

$order = new Order();
// stores values for orders in variables to be referenced later
$undeliveredOrders = $order->getUndeliveredOrders();
$unconfirmedOrders = $order->getUnconfirmedOrders();

// code for redirecting the user when they click view next to an order
// passes the order ID to the view existing order page, that page then runs and gets order details
if (isset($_POST['view_submit'])) {
	if (!empty($_POST['view_submit'])) {
		$order_id_transfer = trim($_POST['view_submit']);
		$_SESSION['order_transfer'] = $order_id_transfer;
		header("Location: viewExistingOrderDynamic.php");
		exit;
	}
}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System - Welcome</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="header">
<?php include("../html/header.html"); ?>
</div>
<div class="pl-1" id="welcome">
<h2>
<!-- OOP call to the forename of the user, retreived from the users table -->
<?php echo "Welcome, $userDetails->forename!"; ?>
</h2>
</div>

<div class="row ml-1" id="quick_links">
<div class="container alert alert-info" align="center">
<p class="font-weight-bold">Dashboard</p>
<p>Below you can find links to various parts of the purchase order system. Information such as orders not marked as received, or those that haven't yet had a confirmation generated can be seen below.</p>
<p>To return to this page at any time, click the logo in the top left corner.</p>

</div>
<div class="container-fluid">

<div class="col-md-6">
<h4 class="mt-2">Quick Links</h4>
<ul style="list-style-type: none">
	<li><a href="createOrder.php">Create a new purchase order</a></li>
	<li><a href="generateConfirmation.php">Generate a purchase order confirmation</a></li>
	<li><a href="addPurchaseItem.php">Add new purchase items</a></li>
	<li><a href="viewExistingItems.php">View all purchase items</a></li>
	<li><a href="addSupplier.php">Add a new supplier</a></li>
	<li><a href="viewExistingOrder.php">View an existing order</a></li>
	<li><a href="updateOrder.php">Update an existing order</a></li>
	<li><a href="deleteOrder.php">Delete an existing order</a></li>
</ul>
</div>
<div class="col-md-6 mt-2">
	<h3>Orders with confirmation not generated</h3>
	<blockquote class="blockquote">To mark a purchase order as confirmed, use the <a href="generateConfirmation.php">generate confirmation page</a></blockquote>
	<table class="table">
		<thead class="thead-inverse">
			<th>Purchase Order ID</th>
			<th>Order Date</th>
			<th>Date Required</th>
			<th>Links</th>
		</thead>

		<tbody>
			<?php
				// iterates through each order that hasn't had a purchase order confirmation generated yet
				if (!empty($unconfirmedOrders)) {
				foreach ($unconfirmedOrders as $unconfirmedOrder) {
				?>
					<?php echo "<tr>";
					echo "<td>"; echo $unconfirmedOrder['order_id']; echo "</td>";
					echo "<td>"; echo date('d-m-Y', strtotime(str_replace('-', '/', $unconfirmedOrder['order_date']))); echo "</td>";
					echo "<td>"; echo date('d-m-Y', strtotime(str_replace('-', '/', $unconfirmedOrder['date_required'])));
					?>
					<?php echo "<td>"; ?> <form action="welcome.php" method="post"> <button class="btn btn-outline-primary" type="submit" name="view_submit" value="<?php echo $unconfirmedOrder['order_id']; ?>">View</button> </form> <?php echo "</td>";
					echo "</tr>";
				}
				}
			?>
		</tbody>

	</table>
</div>

</div>

<div class="row  mt-3">
	<div class="col-md-6">
	<h3 align="center">Orders not yet delivered/marked as received</h3>
	<table class="table">
		<thead class="thead-inverse">
			<th>Purchase Order ID</th>
			<th>Order Date</th>
			<th>Date Required</th>
			<th>Links</th>
		</thead>

		<tbody>
			<?php
				// iterates through each order that hasn't been marked as received
				if (!empty($undeliveredOrders)) {
				foreach ($undeliveredOrders as $undeliveredOrder) {
			?>
					<?php echo "<tr>";
					echo "<td>"; echo $undeliveredOrder['order_id']; echo "</td>";
					echo "<td>"; echo date('d-m-Y', strtotime(str_replace('-', '/', $undeliveredOrder['order_date']))); echo "</td>";
					echo "<td>"; echo date('d-m-Y', strtotime(str_replace('-', '/', $undeliveredOrder['date_required'])));
					?>
					<!-- adds a button for each purchase order that allows for quick viewing -->
					<?php echo "<td>"; ?> 
					<div class="row">
					<div class="col-md-3">
					<form action="welcome.php" method="post"> 
					<button class="btn btn-outline-primary" type="submit" name="view_submit" value="<?php echo $undeliveredOrder['order_id']; ?>">View</button> 
					</form> 
					</div>
					<!-- adds a button to mark a purchase order as delivered on the current date by executing a script on markAsDelivered.php -->
					<div class="col-md-3">
					<form action="markAsDelivered.php" method="post"> 
					<button class="btn btn-outline-primary" type="submit" name="order_id_delivered" value="<?php echo $undeliveredOrder['order_id']; ?>">Mark as delivered</button> 
					</form>
					</div>
					</div>
					<?php echo "</td>";
					echo "</tr>";
				}}
			?>
		</tbody>
	</table>
	</div>
</div>

</div>

<div id="footer">
<?php //include("../html/footer.html"); ?>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</html>