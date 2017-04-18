<?php
session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}

require __DIR__ . '/classLib.php';

// store order details in variable from the session variable, passed from the previous page
$orderDetails = $_SESSION['order_details'];

// create object of order confirmation class, passing order id from the array of order details
$orderConfirmation = new OrderConfirmation($orderDetails[0]['order_id']);

// pass the first stock code to the function to return the supplier name
// first stock code is used as there is a min. of 1 item per order, meaning there must be a value
$companyName = $orderConfirmation->getSupplierFromStockCode($orderDetails[0]['stock_code']);

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System - Generate confirmation</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
</head>
<body>


<div class="container" id="body">

	<div class="row">
		<div class="col-xs-6">
			<h1>Purchase Order Confirmation</h1>
		</div>
		<div class="col-xs-6">
			<!-- Logo for form -->
			<a href="welcome.php"><img src="../assets/img/confirmationLogo.jpg" alt="Confirmation Logo" height="90" width="450"></a>
		</div>
	</div>

	<div class="row">
	<div class="col-xs-6">
		<div class="col-xs-2">
			<p>From:</p>
			<p>Company:</p>
		</div>
		<div class="col-xs-8">
			<!-- display the order creator by indexing the 2d array, to get the name -->
			<?php echo $orderDetails[0]['creator_name']; ?>
		</div>
		<div class="col-xs-6 pt-1 pl-2">
			<?php echo $companyName; ?>
		</div>
	</div>


	<div class="col-xs-6">
	<span>Accordial Manufacturing Ltd<br>Units 27-30 Kernan Drive<br>Loughborough<br>Leicestershire<br>LE11 5JF<br></span>
	<span><strong>Tel: </strong>01509 611234</span><br>
	<span><strong>Fax: </strong>01509 211226</span>
	</div>
	</div>

	<div class="row">
		<div class="col-xs-3">
			<p>Order Generated:</p>
		</div>
		<div class="col-xs-5">
		<!-- format date to UK format, as opposed to US (used my mySQL) -->
		<?php echo date('d-m-Y', strtotime(str_replace('/', '-', $orderDetails[0]['order_date']))); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-3">
		<p class="font-weight-bold">Del Req By:</p>
		</div>

		<div class="col-xs-5">
		<!-- format date to UK format, as opposed to US (used my mySQL) -->
		<?php echo date('d-m-Y', strtotime(str_replace('/', '-', $orderDetails[0]['date_required']))); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-3">
			<p>Purchase Order Number:</p>
		</div>
		<div class="col-xs-2">
			<?php echo $orderDetails[0]['order_id']; ?>
		</div>
	</div>

	<div class="row">
		<div>
		<span>Please process this as an order confirmation for the Pre Purchase Enquiry. This acknowledges the price, delivery date, and any extra charges or discounts.</span>
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
				}
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
						foreach ($orderDetails as $array => $second_array['quantity']) {
							foreach ($second_array as $OrderDetails => $Details) {
								// takes the price * quantity for each item and adds result to the total order value
								$orderTotal = $orderTotal + $orderConfirmation->calculateTotalValue($Details['stock_code']);
							}
						}
						// returns final order value of all items
						echo  "£". $orderTotal;
					?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="row">
	<p><strong>Special Comments:</strong></p>
	<?php if(empty($orderDetails[0]['special_comments'])) {echo "N/A";} else { echo $orderDetails[0]['special_comments']; } ?>
	</div>

	<div class="row">
		<div class="col-xs-6 mt-2">
			<p style="background-color: black; color: white; text-align: center;">Order Acknowledgment</p>
			<table border="1" style="width: 100%;">
				<tr>
					<td>Received By:</td>
				</tr>
				<tr>
					<td>Date Returned:</td>
				</tr>
				<tr>
					<td>Delivery Date:</td>
				</tr>
			</table>
			<p class="font-weight-bold mt-1" style="background-color: black; color: white; text-align: center;">TO BE RETURNED</p>
		</div>

		<div class="col-xs-6 mt-2">
		<div style="border: solid;">
		<p class="ml-1">For Accordial Manufacturing Ltd</p>
		<br>
		<br>
		<p class="ml-1">Managing Director</p>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<p class="font-weight-bold" align="center">Should you have any problems processing this order, please call as a matter of urgency</p>
		</div>
	</div>
</div> <!-- end of main container -->
</body>
</html>