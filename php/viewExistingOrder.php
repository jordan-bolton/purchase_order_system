<?php
session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}

require __DIR__ . '/classLib.php';
$orderDetails = "";
$errorMessage = "";
$orderID = null;

if (isset($_POST['confirmation_submit'])) {
	if (!empty($_POST['order_id'])) {
		$orderID = trim($_POST['order_id']);
		$order = new Order();
		$orderDetails = $order->getExistingItemData($orderID);
	}
	if(empty($orderDetails)) {
		$errorMessage = "A valid order ID is required.";
	}
}
$orderConfirmation = new OrderConfirmation($orderID);

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System - View Existing Order</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div id="header">
<?php include "../html/header.html"; ?>
</div>


<div class="container">
<h3>View Existing Purchase Order</h3>

<div class="alert alert-info mx-auto center-block mt-1" role="alert" style="width: 600px;">
<p>Enter the order ID for the purchase order below.  The page will then update with the relevant order details.</p>
</div>

<div id="form">
<form action="viewExistingOrder.php" method="post" autocomplete="off">
<div class="form-group" id="order_id">
<input type="text" class="form-control" name="order_id" placeholder="Enter order ID here"><br>
</div>
<div class="form-group" id="confirmation_submit">
<input type="submit" class="form-control btn btn-outline-info font-weight-bold" name="confirmation_submit" value="Go">
<?php
if(!empty($errorMessage)) {
?>
	<div class="alert alert-danger rounded mt-1"><?php echo $errorMessage; ?>
<?php
}
?>
</div>

</form>
</div>

<h3>Order Details</h3>

<div class="row">
		<div class="col-xs-3">
			<p>Purchase Order Number:</p>
		</div>
		<div class="col-xs-2">
			<?php if (!empty($orderDetails)) { echo $orderDetails[0]['order_id'];} ?>
		</div>
</div>

<div class="row">
		<div class="col-xs-3">
			<p>Order Creator:</p>
		</div>
		<div class="col-xs-2">
			<?php if (!empty($orderDetails)) {echo $orderDetails[0]['creator_name'];} ?>
		</div>
</div>

<div class="row">
		<div class="col-xs-3">
			<p>Order Generated:</p>
		</div>
		<div class="col-xs-2">
			<?php if (!empty($orderDetails)) { echo $orderDetails[0]['order_date'];} ?>
		</div>
</div>

<div class="row">
	<table class="table">
		<thead class="thead-inverse">
			<th>Quantity</th>
			<th>Stock Code</th>
			<th>Description</th>
			<th>Value</th>
			<th>Unit</th>
			<th>Total Value</th>
		</thead>
		<tbody>
			<?php
			if (!empty($orderDetails)) {
			// iterate through array of order details using a nested for loop
			foreach ($orderDetails as $array => $second_array['quantity']) {
				foreach ($second_array as $OrderDetails => $Details) {
					echo "<tr>";
					echo "<td>"; echo $Details['quantity']; echo "</td>";
					echo "<td>"; echo $Details['stock_code']; echo "</td>";
					echo "<td>"; echo $Details['description']; echo "</td>";
					echo "<td>"; echo "£". $Details['price']; echo "</td>";
					echo "<td>"; echo $Details['unit']; echo "</td>";
					echo "<td>"; echo "£". $orderConfirmation->calculateTotalValue($Details['stock_code']); echo "</td>";
				}
			}}
			?>
			<tr>
				<!-- empty so total is pushed to the end of the table -->
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="font-weight-bold">Total:</td>
				<td>
				<?php
					// sets the order total to 0
					$orderTotal = 0;
					if (!empty($orderDetails)) {
					foreach ($orderDetails as $array => $second_array['quantity']) {
						foreach ($second_array as $OrderDetails => $Details) {
							// takes the price * quantity for each item and adds result to the total order value
							$orderTotal = $orderTotal + $orderConfirmation->calculateTotalValue($Details['stock_code']);
						}
					}
					// returns final order value of all items
					echo  "£". $orderTotal;
				}
				?>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div class="row">
	<div class="col-xs-3">
	<p class="font-weight-bold">Del Req By:</p>
	</div>
	<div class="col-xs-5">
	<!-- format date to UK format, as opposed to US (used my mySQL) -->
	<?php if (!empty($orderDetails)) { echo date('d-m-Y', strtotime(str_replace('/', '-', $orderDetails[0]['date_required'])));} ?>
	</div>
</div>

<div class="row">
	<div class="col-xs-3">
	<p><strong>Special Comments:</strong></p>
	</div>
	<div class="col-xs-6">
	<?php if (!empty($orderDetails)) { if(empty($orderDetails[0]['special_comments'])) {echo "N/A";} else { echo $orderDetails[0]['special_comments']; }} ?>
	</div>
</div>

<div class="row">
	<div class="col-xs-3">
		<p><strong>Confirmation Generated:</strong></p>
	</div>

	<div class="col-xs-6">
		<?php if(!empty($orderDetails)) { if ($orderDetails[0]['confirmation_generated'] == 0) {echo "No";} else {echo "Yes";}} ?>
	</div>
</div>

<div class="row">
	<div class="col-xs-3">
		<p><strong>Received:</strong></p>
	</div>
	<div class="col-xs-5">
		<?php if(!empty($orderDetails)) { if ($orderDetails[0]['received'] == 0) {echo "No";} else {echo "Yes";}} ?>
	</div>
</div>

<div class="row">
	<div class="col-xs-3">
		<p><strong>Date Received:</strong></p>
	</div>
	<div class="col-xs-5">
		<?php if (!empty($orderDetails)) { if ($orderDetails[0]['date_received'] == null) {echo "N/A";} else {echo date('d-m-Y', strtotime(str_replace('-', '/', $orderDetails[0]['date_received'])));}} ?>
	</div>

</div>


</div>

</body>
</html>