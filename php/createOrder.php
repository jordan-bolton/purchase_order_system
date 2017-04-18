<?php
session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}

$errorMessages = array();
$stockCodes = "";
$order_supplier = "";


// instantiate objects
require __DIR__ . "/classLib.php";
$order = new Order();
$nextID = $order->getLastID();

$productDetails = new ProductDetails();

$user = new UserLogin();
$userDetails = $user->getUserDetails($_SESSION['user_id']);

// code for storing form values in variables to be passed to function
if(isset($_POST['order_submit'])) {
if(isset($_POST['order_id'])) {
	$order_id = trim($_POST['order_id']);
}
if(isset($_POST['creator_name'])) {
	$creator_name = trim($_POST['creator_name']);
}
// order creation date will be inputted automatically, so not passed as a variable here

if(isset($_POST['order_stock_code_1'])) {
	$order_stock_code_1 = trim($_POST['order_stock_code_1']);
}
if(isset($_POST['quantity_1'])) {
	$quantity_1 = trim($_POST['quantity_1']);
}
if(isset($_POST['order_stock_code_2'])) {
	$order_stock_code_2 = trim($_POST['order_stock_code_2']);
}
if(isset($_POST['quantity_2'])) {
	$quantity_2 = trim($_POST['quantity_2']);
}
if(isset($_POST['order_stock_code_3'])) {
	$order_stock_code_3 = trim($_POST['order_stock_code_3']);
}
if(isset($_POST['quantity_3'])) {
	$quantity_3 = trim($_POST['quantity_3']);
}
if(isset($_POST['order_stock_code_4'])) {
	$order_stock_code_4 = trim($_POST['order_stock_code_4']);
}
if(isset($_POST['quantity_4'])) {
	$quantity_4 = trim($_POST['quantity_4']);
}
if(isset($_POST['order_stock_code_5'])) {
	$order_stock_code_5 = trim($_POST['order_stock_code_5']);
}
if(isset($_POST['quantity_5'])) {
	$quantity_5 = trim($_POST['quantity_5']);
}
if(isset($_POST['order_stock_code_6'])) {
	$order_stock_code_6 = trim($_POST['order_stock_code_6']);
}
if(isset($_POST['quantity_6'])) {
	$quantity_6 = trim($_POST['quantity_6']);
}
if(isset($_POST['order_date_required'])) {
	$order_date_required = trim($_POST['order_date_required']);
	// convert date to the correct format for SQL (y-m-d as opposed to d-m-y)
	$order_date_required = date('Y-m-d', strtotime(str_replace('-', '/', $order_date_required)));
}
if(isset($_POST['order_special_comments'])) {
	$order_special_comments = trim($_POST['order_special_comments']);
}

// each time an error occurs, 1 is added to the number of errors
$errorCount = 0;
// Validation for inputs
if (empty($order_stock_code_1)) {
	// array_push adds the error to the array of error messages
	array_push($errorMessages, "At least one item is required\n");
	// adds 1 to the number of error messages that occured, meaning the order can't be created
	$errorCount += 1;
}
if (empty($quantity_1)) {
	array_push($errorMessages, "A quantity is required\n");
	$errorCount += 1;
}

// if no errors occured, submit data to function
if ($errorCount == 0) {
// code for passing variables to function to be inserted into the db
$submitOrder = $order->createOrder($order_id, $creator_name, $order_stock_code_1, $quantity_1, $order_stock_code_2, $quantity_2, $order_stock_code_3, $quantity_3, $order_stock_code_4, $quantity_4, $order_stock_code_5, $quantity_5, $order_stock_code_6, $quantity_6, $order_date_required, $order_special_comments);
	// redirect to a success page if the order was created successfully
	if ($submitOrder > 0) {
		header("Location: orderCreationSuccess.php");
	}
}


}
if (isset($_POST['supplier_submit'])) {
	if (isset($_POST['order_supplier'])) {
		$order_supplier = trim($_POST['order_supplier']);
	}

if (empty($order_supplier)) {
	array_push($errorMessages, "The supplier is required\n");
}
}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System - Create Order</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="header"> <!-- Include header in page -->
<?php include("../html/header.html"); ?>
</div>

<div class="container"> <!-- Main div for form -->
<h2>Create a new purchase order</h2>

<div class="container" id="error_message">
<?php 
// display each error that has occured before being allowed to submit form
if (!empty($errorMessages)) {
?>
	<div class="alert alert-danger rounded mt-1 font-weight-bold"><?php foreach ($errorMessages as $errorMessage) {echo nl2br($errorMessage);}; ?>
<?php
}
?>
</div>

<div class="alert alert-info mx-auto center-block" role="alert" style="width: 600px;">
<p>Enter the purchase order information in the form below.  First enter the supplier and click 'Go' to get the appropriate items.  Click submit when complete.</p>
<p class="font-weight-bold">The order ID, creator name and order date are automatically generated.  They should not need changing.  Leave item fields blank if they are not required.</p>
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

<!-- List of stock codes from a supplier that can be added to an order, referenced by an ID so it can be used on each input without having to be repeated -->
<datalist id="stock_codes">
	<?php $stockCodes = $productDetails->getStockCodes($order_supplier);
	 if (!empty($order_supplier)) {foreach($stockCodes as $stockCode) { ?>
		<option value="<?php echo $stockCode['stock_code']; ?>"><?php echo $stockCode['stock_code']; ?></option>
	<?php } $stockCodes = "";} ?>
</datalist>
<!-- List of suppliers available.  Only products from the selected supplier will be shown in the stock code dropdown, as a purchase order can only contain items from one supplier -->
<datalist id="suppliers">
	<?php $suppliers = $productDetails->getSupplierNames(); foreach($suppliers as $supplier) { ?>
		<option value="<?php echo $supplier['supplier_name']; ?>"><?php echo $supplier['supplier_name']; ?></option>
		<?php } ?>
</datalist>


<form action="createOrder.php" method="post" autocomplete="off">
<div class="form-group" id="orderFormID">
<label>Order ID</label><br>
<input type="number" class="form-control" name="order_id" value="<?php echo $nextID; ?>"><br>
</div>
<div class="form-group" id="orderFormCreator">
<label>Order Creator</label><br>
<input type="text" class="form-control" name="creator_name" value="<?php echo $userDetails->forename . " " . $userDetails->surname ?>"><br>
</div>

<div class="container">
<?php if (!empty($_POST['order_supplier'])) {
	?>
	<div class="container alert alert-success"><p style="display: inline;"> Supplier <p class="font-weight-bold" style="display: inline;"><?php echo $order_supplier; ?></p> entered successfully.  Add items below.</p>
<?php } ?>
</div>

<form action="createOrder.php" method="post">
<div class="form-group" id="orderFormSupplier">
<label>Supplier</label><br>
<input type="text" class="form-control" name="order_supplier" list="suppliers" placeholder="Enter supplier"><br>
</div>

<div class="form-group" id="supplierSubmit">
<input type="submit" class="form-control btn btn-outline-info font-weight-bold" name="supplier_submit" value="Go"><br>
</div>
</form>

<div class="form-group" id="orderFormDate">
<label>Order Date</label><br>
<input type="text" class="form-control" name="order_date" value="<?php echo date("d-m-Y"); ?>"><br>
</div>

<table class="table">
<thead class="thead-inverse">
	<tr>
	<th>Item #</th>
	<th>Stock Code</th>
	<th>Description</th>
	<th>Price</th>
	<th>Unit</th>
	<th>Quantity</th>
	</tr>
</thead>
<tbody>
	<tr>
	<th scope="row">1</th>
	<!-- When a code is inputted, the onchange attribute executes the ajax scripts above, populating fields with appropriate data -->
	<td> <input class="form-control" id="order_stock_code_1" list="stock_codes" name="order_stock_code_1" onchange="showDescription(this.value, 'description_1'); showPrice(this.value, 'price_1'); showUnit(this.value, 'unit_1')"><br></td>
		<datalist id="stock_codes"></datalist>
	<td id="description_1"></td>
	<td id="price_1"></td>
	<td id="unit_1"></td>
	<td> <input class="form-control" type="number" name="quantity_1"><br></td>
	</tr>
	<tr>

	<th scope="row">2</th>
	<td> <input class="form-control" list="stock_codes" name="order_stock_code_2" onchange="showDescription(this.value, 'description_2'); showPrice(this.value, 'price_2'); showUnit(this.value, 'unit_2')"><br></td>
		<datalist id="stock_codes"></datalist>
	<td id="description_2"></td>
	<td id="price_2"></td>
	<td id="unit_2"></td>
	<td> <input class="form-control" type="number" name="quantity_2"><br></td>
	</tr>
	<tr>
	<th scope="row">3</th>
	<td> <input class="form-control" list="stock_codes" name="order_stock_code_3" onchange="showDescription(this.value, 'description_3'); showPrice(this.value, 'price_3'); showUnit(this.value, 'unit_3')"><br></td>
		<datalist id="stock_codes"></datalist>
	<td id="description_3"></td>
	<td id="price_3"></td>
	<td id="unit_3"></td>
	<td> <input class="form-control" type="number" name="quantity_3"><br></td>
	</tr>
	<tr>

	<th scope="row">4</th>
	<td> <input class="form-control" list="stock_codes" name="order_stock_code_4" onchange="showDescription(this.value, 'description_4'); showPrice(this.value, 'price_4'); showUnit(this.value, 'unit_4')"><br></td>
		<datalist id="stock_codes"></datalist>
	<td id="description_4"></td>
	<td id="price_4"></td>
	<td id="unit_4"></td>
	<td> <input class="form-control" type="number" name="quantity_4"><br></td>
	</tr>
	<tr>

	<th scope="row">5</th>
	<td> <input class="form-control" list="stock_codes" name="order_stock_code_5" onchange="showDescription(this.value, 'description_5'); showPrice(this.value, 'price_5'); showUnit(this.value, 'unit_5')"><br></td>
		<datalist id="stock_codes"></datalist>
	<td id="description_5"></td>
	<td id="price_5"></td>
	<td id="unit_5"></td>
	<td> <input class="form-control" type="number" name="quantity_5"><br></td>
	</tr>
	<tr>

	<th scope="row">6</th>
	<td> <input class="form-control" list="stock_codes" name="order_stock_code_6" onchange="showDescription(this.value, 'description_6'); showPrice(this.value, 'price_6'); showUnit(this.value, 'unit_6')"><br></td>
		<datalist id="stock_codes"></datalist>
	<td id="description_6"></td>
	<td id="price_6"></td>
	<td id="unit_6"></td>
	<td> <input class="form-control" type="number" name="quantity_6"><br></td>
	</tr>
</tbody>

</table>
<div class="form-group" id="orderFormDateRequired">
<label>Date Required</label><br>
<input type="date" class="form-control" name="order_date_required" required><br>
</div>
<div class="form-group" id="orderFormSpecialComments">
<label>Special Comments</label><br>
<input type="text" class="form-control" name="order_special_comments"><br>
</div>
<div class="form-group" id="orderFormSubmit">
<input type="submit" class="form-control btn btn-outline-info font-weight-bold" name="order_submit" value="Submit"><br>
</div>
</form>
</div>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</html>