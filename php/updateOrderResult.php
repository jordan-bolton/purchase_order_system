<?php
session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}

require __DIR__ . '/classLib.php';

$order = new Order();

// declare variables for use
$errorMessages = array();
$successMessages = array();
$order_id = $_SESSION['order_update_id'];
$updateOrderDetails = null;

$array_count = 0;
$stock_codes = array();
$quantities = array();


$orderDetails = $order->getOrderDetails($order_id);
$orderConfirmation = new OrderConfirmation($order_id);
$orderSupplier = $orderConfirmation->getSupplierFromStockCode($orderDetails[0]['stock_code']);
$productDetails = new ProductDetails();




if (isset($_POST['update_submit'])) {

	if (isset($_POST['creator_name'])) {
		$creator_name = trim($_POST['creator_name']);
	}

	if (isset($_POST['order_date'])) {
		$order_date = trim($_POST['order_date']);
	}

	if (isset($_POST['order_stock_code0'])) {
		$order_stock_code_1 = trim($_POST['order_stock_code0']);
	}

	if (isset($_POST['quantity0'])) {
		$quantity_1 = trim($_POST['quantity0']);
	}

	if (isset($_POST['order_stock_code1'])) {
		$order_stock_code_2 = trim($_POST['order_stock_code1']);
	}

	if (isset($_POST['quantity1'])) {
		$quantity_2 = trim($_POST['quantity1']);
	}

	if (isset($_POST['order_stock_code2'])) {
		$order_stock_code_3 = trim($_POST['order_stock_code2']);
	}

	if (isset($_POST['quantity2'])) {
		$quantity_3 = trim($_POST['quantity2']);
	}

	if (isset($_POST['order_stock_code3'])) {
		$order_stock_code_4 = trim($_POST['order_stock_code3']);
	}

	if (isset($_POST['quantity3'])) {
		$quantity_4 = trim($_POST['quantity3']);
	}

	if (isset($_POST['order_stock_code4'])) {
		$order_stock_code_5 = trim($_POST['order_stock_code4']);
	}

	if (isset($_POST['quantity4'])) {
		$quantity_5 = trim($_POST['quantity4']);
	}

	if (isset($_POST['order_stock_code5'])) {
		$order_stock_code_6 = trim($_POST['order_stock_code5']);
	}

	if (isset($_POST['quantity5'])) {
		$quantity_6 = trim($_POST['quantity5']);
	}	

	if(isset($_POST['date_required'])) {
		$date_required = trim($_POST['date_required']);
		// convert date to the correct format for SQL (y-m-d as opposed to d-m-y)
		$date_required = date('Y-m-d', strtotime(str_replace('-', '/', $date_required)));
	}

	if (isset($_POST['special_comments'])) {
		$special_comments = trim($_POST['special_comments']);
	}

	if (isset($_POST['received'])) {
		$received = trim($_POST['received']);
	}

	if (isset($_POST['date_received'])) {
		$date_received = trim($_POST['date_received']);
	}

// code used to see if there is a change to the creator name or order date, and if so, update them. if not, don't update
if ($creator_name != $orderDetails[0]['creator_name'] OR $order_date != $orderDetails[0]['order_date']) {
	$updateOrderDetails = $order->updateOrderDetails($order_id, $creator_name, $order_date);
}

if ($updateOrderDetails == 1) {
	array_push($successMessages, "Creator name or order date successfully updated\n");
}

// adds all stock codes and quantities in an order to an array, which is used for updating them
if (isset($order_stock_code_1)) {
	array_push($stock_codes, $order_stock_code_1);
	array_push($quantities, $quantity_1);
}
if (isset($order_stock_code_2)) {
	array_push($stock_codes, $order_stock_code_2);
	array_push($quantities, $quantity_2);
}
if (isset($order_stock_code_3)) {
	array_push($stock_codes, $order_stock_code_3);
	array_push($quantities, $quantity_3);
}
if (isset($order_stock_code_4)) {
	array_push($stock_codes, $order_stock_code_4);
	array_push($quantities, $quantity_4);
}
if (isset($order_stock_code_5)) {
	array_push($stock_codes, $order_stock_code_5);
	array_push($quantities, $quantity_5);
}
if (isset($order_stock_code_6)) {
	array_push($stock_codes, $order_stock_code_6);
	array_push($quantities, $quantity_6);
}

// code used for checking if stock codes or quantities have been changed. if so, update them
// iterates through each stock code in the order
foreach ($stock_codes as $stockCode) {
	// if the stock code has changed from the original order, run the following:
	if ($stockCode != $orderDetails[$array_count]['stock_code'] OR $quantities[$array_count] != $orderDetails[$array_count]['quantity']) {
		// update the stock code, passing in the new stock code, new quantity, the order id and the original stock code that is being replaced
		$updateStockCodes = $order->updateStockCodes($stockCode, $quantities[$array_count], $orderDetails[0]['order_id'], $orderDetails[$array_count]['stock_code']);
		// if the stock code is the same as before, only the quantity for the stock code has been changed and a relevant success message is displayed
		if ($updateStockCodes == 1 AND $stockCode == $orderDetails[$array_count]['stock_code']) {
			array_push($successMessages, "Quantity for stock code  ". $orderDetails[$array_count]['stock_code'] ." successfully updated to " . $quantities[$array_count] . "\n");
		}
		// if both the stock code and the quantity have changed, display a relevant success message
		elseif ($updateStockCodes == 1 AND $stockCode != $orderDetails[$array_count]['stock_code'] AND $quantities[$array_count] != $orderDetails[$array_count]['quantity']) {
			array_push($successMessages, "Stock code  ". $orderDetails[$array_count]['stock_code'] ." successfully updated to " .  $stockCode . " and quantity updated to " . $quantities[$array_count] . "\n");
		}
		// if 1 is returned (the no. of affected rows), only the stock code has been successfully updated and a success message is displayed
		elseif ($updateStockCodes == 1) {
			array_push($successMessages, "Stock code ". $orderDetails[$array_count]['stock_code'] ." successfully updated to " . $stockCode . "\n");
		}
		// if there are no changed made to the stock codes or quantities, let the user know through a relevant message
		else {
			array_push($successMessages, "No changes to stock codes made");
		}

	}
	// each time the loop runs (i.e. the number of stock codes in an order), add one to the count to represent the stock code being changed in the array
	$array_count ++;
}

// code used for updating the order requirements, including the date required, received, date received and special comments
if ($date_required != $orderDetails[0]['date_required']) {
	$updateDateRequired = $order->updateOrderRequirements("date_required", $date_required, $orderDetails[0]['order_id']);
	if ($updateDateRequired == 1) {
		// formats the date from SQL format back to UK format (y-m-d back to d-m-y)
		$oldDate = date('d-m-Y', strtotime($orderDetails[0]['date_required']));
		$newDate = date('d-m-Y', strtotime($date_required));
		array_push($successMessages, "Order date required updated from " . $oldDate . " to " . $newDate . "\n");
	}
}


// code used for checking to see if the order's special comments have been added/changed
if ($special_comments != $orderDetails[0]['special_comments']) {
	$updateSpecialComments = $order->updateOrderRequirements("special_comments", $special_comments, $orderDetails[0]['order_id']);
	if ($updateSpecialComments == 1) {
		array_push($successMessages, "Special comments changed successfully \n");
	}
}

// code used for checking to see if the order's received status has changed
if ($received != $orderDetails[0]['received']) {
	$updateReceived = $order->updateOrderRequirements("received", $received, $orderDetails[0]['order_id']);
	if ($updateReceived == 1 AND $received == 1) {
		array_push($successMessages, "Purchase order marked as received \n");
	}
	else {
		array_push($successMessages, "Purchase order marked as not being received \n");
	}
}

// code used for checking to see if the order's date received has been added/changed
if ($date_received != $orderDetails[0]['date_received']) {
	$updateDateReceived = $order->updateOrderRequirements("date_received", $date_received, $orderDetails[0]['order_id']);
	$old_date_received = date('d-m-Y', strtotime($orderDetails[0]['date_received']));
	$new_date_received = date('d-m-Y', strtotime($date_received));
	if ($updateDateReceived == 1 AND $orderDetails[0]['date_received'] == NULL) {
		array_push($successMessages, "Date received changed from no date to " . $new_date_received . "\n");
	}
	else {
		array_push($successMessages, "Date received changed from " . $old_date_received . " to " . $new_date_received . "\n");
	}
}

// adds all success messages to the $_SESSION variable, so that they can be displayed on another page
$_SESSION['update_success_messages'] = $successMessages;
header("Location: updateOrderSuccess.php");
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
<h2>Update Purchase Order</h2>

<div class="container alert alert-info">
<p>Enter the purchase order number below and make the necessary changes.  When finished, press the submit button at the bottom.</p>
<p class>The purchase order ID and supplier <strong>can't</strong> be changed. If they need to be changed, create a new purchasr order.</p>
<p>When changing the order date, leave the format as it is displayed.</p>
</div>

<div class="container" id="messages">
<?php 
// display each error that has occured before being allowed to submit form
if (!empty($errorMessages)) {
?>
	<div class="alert alert-danger rounded mt-1 font-weight-bold"><?php foreach ($errorMessages as $errorMessage) {echo nl2br($errorMessage);}; ?>
<?php
}
?>

<?php
if (!empty($successMessages)) {
?>
	<div class="alert alert-success rounded mt-1 font-weight-bold"><?php foreach ($successMessages as $successMessage) {echo nl2br($successMessage);}; ?>
<?php
}
?>


</div>

<!-- Scripts for getting item description price using AJAX to dynamically update table -->
<script type="text/javascript">
function showDescription(str, element) {
	if (str == "") {
		document.getElementById(element).innerHTML = "";
		return;
	}
	else {
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById(element).innerHTML = this.responseText;
			}
		};
	xmlhttp.open("GET", "getItemDescription.php?code="+str,true);
	xmlhttp.send();
	}
}

function showPrice(str, element) {
	if (str == "") {
		document.getElementById(element).innerHTML = "";
		return;
	}
	else {
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 & this.status == 200) {
				document.getElementById(element).innerHTML = '£' + this.responseText;
			}
		};
	xmlhttp.open("GET", "getItemPrice.php?code="+str,true);
	xmlhttp.send();
	}
}

function showUnit(str, element) {
	if (str == "") {
		document.getElementById(element).innerHTML = "";
		return;
	}
	else {
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 & this.status == 200) {
				document.getElementById(element).innerHTML = this.responseText;
			}
		};
	xmlhttp.open("GET", "getItemUnit.php?code="+str,true);
	xmlhttp.send();
	}
}

</script>

<!-- used so that stock codes can be replaced in an order,  but from the same supplier -->
<datalist id="stock_codes">
	<?php $stockCodes = $productDetails->getStockCodes($orderSupplier);
	 if (!empty($orderSupplier)) {foreach($stockCodes as $stockCode) { ?>
		<option value="<?php echo $stockCode['stock_code']; ?>"><?php echo $stockCode['stock_code']; ?></option>
	<?php } $stockCodes = "";} ?>
</datalist>

<div id="order_details_form">
<form action="updateOrderResult.php" method="post" autocomplete="off">

<div class="form-group" id="orderFormID">
<label>Order ID</label><br>
<input type="number" class="form-control" name="order_id" value="<?php echo $orderDetails[0]['order_id']; ?>" disabled><br>
</div>

<div class="form-group" id="orderFormCreator">
<label>Order Creator</label><br>
<input type="text" class="form-control" name="creator_name" value="<?php echo $orderDetails[0]['creator_name']; ?>"><br>
</div>

<div class="form-group" id="orderFormSupplier">
<label>Supplier</label><br>
<input type="text" class="form-control" name="order_supplier" value="<?php echo $orderSupplier; ?>" disabled><br>
</div>

<div class="form-group" id="orderFormDate">
<label>Order Date</label><br>
<input type="text" class="form-control" name="order_date" value="<?php echo $orderDetails[0]['order_date']; ?>"><br>
</div>

<?php if (!empty($orderDetails)) { ?>
<table class="table">
<thead class="thead-inverse">
	<tr>
	<th>Stock Code</th>
	<th>Description</th>
	<th>Price</th>
	<th>Unit</th>
	<th>Quantity</th>
	</tr>
</thead>

<tbody>

	<?php
		$i = 0;
		if (!empty($orderDetails)) {
		// iterate through each item in the order details array, using quantity as the key
		foreach ($orderDetails as $array => $second_array['quantity']) {
			foreach ($second_array as $OrderDetails => $Details) {
	?>
				<?php echo "<tr>"; ?>
				<?php echo "<td>"; ?> <input class="form-control" list="stock_codes" name="<?php echo 'order_stock_code'. $i.''; ?>" value="<?php echo $Details["stock_code"]; ?>" onchange="showDescription(this.value, 'description' + <?php echo $i; ?>); showPrice(this.value, 'price' + <?php echo $i; ?>); showUnit(this.value, 'unit' + <?php echo $i; ?>)"> <?php echo "</td>";
				// sets each table cell's id incrementally for each stock code in the purchase order (counter adds 1 each time and sets the id equal to the counter value - e.g. description2, price2)
				echo '<td id="description'.$i.'">'; echo $Details['description']; echo "</td>";
				echo '<td id="price' . $i. '">'; echo "£". $Details['price']; echo "</td>";
				echo '<td id="unit' . $i. '">'; echo $Details['unit']; echo "</td>"; 
				?>
				<?php echo "<td>"; ?> <input class="form-control" type="number" name="<?php echo 'quantity'.$i.''; ?>" value="<?php echo $Details['quantity'] ?>">
				<?php
				echo "</tr>";
				$i++;
			}
		}
		}
}
?>


</tbody>

</table>

<div class="form-group" id="orderFormRequiredBy">
<label>Del Req By</label><br>
<input type="date" class="form-control" name="date_required" value="<?php echo $orderDetails[0]['date_required']; ?>"><br>
</div>

<div class="form-group" id="orderFormSpecialComments">
<label>Special Comments</label><br>
<input type="text" class="form-control" name="special_comments" value="<?php echo $orderDetails[0]['special_comments']; ?>"><br>
</div>

<div class="form-group" id="orderFormReceived">
<label>Received</label><br>
<select name="received"> <?php if (!empty($orderDetails)) {if ($orderDetails[0]['received'] == 1) {echo '<option value="1" selected>Yes</option>'; echo '<option value="0">No</option>';} else {echo '<option value="0" selected>No</option>'; echo '<option value="1">Yes</option>';}} ?> </select><br>
</div>

<div class="form-group" id="orderFormDateReceived">
<label>Date Received</label><br>
<input type="date" class="form-control" name="date_received" value="<?php if (!empty($orderDetails)) { if ($orderDetails[0]['date_received'] == null) {echo "N/A";} else {echo $orderDetails[0]['date_received'];}} ?>"><br>
</div>

<div class="form-group" id="orderFormSubmit">
<input type="submit" class="form-control btn btn-outline-info font-weight-bold" name="update_submit" value="Update">
</div>

</form>

</div>

</div>

</body>
</html>