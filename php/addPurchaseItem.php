<?php
session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}


require __DIR__ . "/classLib.php";

$productDetails = new ProductDetails();
$errorMessages = array();
$successMessages = array();


// store form values in variables to be passed to the object
// trim is used to remove extra characters (such as a space before an input)
if(isset($_POST['item_submit'])) {
	if(isset($_POST['stock_code_1'])) {
		$stock_code_1 = trim($_POST['stock_code_1']);
	}
	if(isset($_POST['description_1'])) {
		$description_1 = trim($_POST['description_1']);
	}
	if(isset($_POST['supplier_1'])) {
		$supplier_1 = trim($_POST['supplier_1']);
	}
	if(isset($_POST['price_1'])) {
		$price_1 = trim($_POST['price_1']);
	}
	if(isset($_POST['unit_1'])) {
		$unit_1 = trim($_POST['unit_1']);
	}
	if(isset($_POST['stock_code_2'])) {
		$stock_code_2 = trim($_POST['stock_code_2']);
	}
	if(isset($_POST['description_2'])) {
		$description_2 = trim($_POST['description_2']);
	}
	if(isset($_POST['supplier_2'])) {
		$supplier_2 = trim($_POST['supplier_2']);
	}
	if(isset($_POST['price_2'])) {
		$price_2 = trim($_POST['price_2']);
	}
	if(isset($_POST['unit_2'])) {
		$unit_2 = trim($_POST['unit_2']);
	}
	if(isset($_POST['stock_code_3'])) {
		$stock_code_3 = trim($_POST['stock_code_3']);
	}
	if(isset($_POST['description_3'])) {
		$description_3 = trim($_POST['description_3']);
	}
	if(isset($_POST['supplier_3'])) {
		$supplier_3 = trim($_POST['supplier_3']);
	}
	if(isset($_POST['price_3'])) {
		$price_3 = trim($_POST['price_3']);
	}
	if(isset($_POST['unit_3'])) {
		$unit_3 = trim($_POST['unit_3']);
	}
	if(isset($_POST['stock_code_4'])) {
		$stock_code_4 = trim($_POST['stock_code_4']);
	}
	if(isset($_POST['description_4'])) {
		$description_4 = trim($_POST['description_4']);
	}
	if(isset($_POST['supplier_4'])) {
		$supplier_4 = trim($_POST['supplier_4']);
	}
	if(isset($_POST['price_4'])) {
		$price_4 = trim($_POST['price_4']);
	}
	if(isset($_POST['unit_4'])) {
		$unit_4 = trim($_POST['unit_4']);
	}
	if(isset($_POST['stock_code_5'])) {
		$stock_code_5 = trim($_POST['stock_code_5']);
	}
	if(isset($_POST['description_5'])) {
		$description_5 = trim($_POST['description_5']);
	}
	if(isset($_POST['supplier_5'])) {
		$supplier_5 = trim($_POST['supplier_5']);
	}
	if(isset($_POST['price_5'])) {
		$price_5 = trim($_POST['price_5']);
	}
	if(isset($_POST['unit_5'])) {
		$unit_5 = trim($_POST['unit_5']);
	}
	if(isset($_POST['stock_code_6'])) {
		$stock_code_6 = trim($_POST['stock_code_6']);
	}
	if(isset($_POST['description_6'])) {
		$description_6 = trim($_POST['description_6']);
	}
	if(isset($_POST['supplier_6'])) {
		$supplier_6 = trim($_POST['supplier_6']);
	}
	if(isset($_POST['price_6'])) {
		$price_6 = trim($_POST['price_6']);
	}
	if(isset($_POST['unit_6'])) {
		$unit_6 = trim($_POST['unit_6']);
	}

$errorCount = 0;

if (!empty($stock_code_1)){
	if (empty($description_1) AND empty($supplier_1) AND empty($price_1) AND empty($unit_1)) {
		array_push($errorMessages, "All fields for item 1 are required.\n");
		$errorCount += 1;
	}
	else {
	$product1 = new Product($stock_code_1, $description_1, $supplier_1, $price_1, $unit_1);
	$productSubmit1 = $product1->addProduct();
	if ($productSubmit1 == 0) {
		array_push($errorMessages, "Stock code 1 already exists.\n");
	}
	else {
		array_push($successMessages, "Item 1 successfully added.\n");
	}
	}
}	

if (!empty($stock_code_2)) {
	if (empty($description_2) AND empty($supplier_2) AND empty($price_2) AND empty($unit_2)) {
		array_push($errorMessages, "All fields for item 2 are required.\n");
		$errorCount += 1;
	}
	else {
	$product2 = new Product($stock_code_2, $description_2, $supplier_2, $price_2, $unit_2);
	$productSubmit2 = $product2->addProduct();
	if ($productSubmit2 == 0) {
		array_push($errorMessages, "Stock code 2 already exists.\n");
	}
	else {
		array_push($successMessages, "Item 2 successfully added.\n");
	}
	}
}

if (!empty($stock_code_3)) {
	if (empty($description_3) AND empty($supplier_3) AND empty($price_3) AND empty($unit_3)) {
		array_push($errorMessages, "All fields for item 3 are required.\n");
		$errorCount += 1;
	}
	else {
	$product3 = new Product($stock_code_3, $description_3, $supplier_3, $price_3, $unit_3);
	$productSubmit3 = $product3->addProduct();
	if ($productSubmit3 == 0) {
		array_push($errorMessages, "Stock code 3 already exists.\n");
	}
	else {
		array_push($successMessages, "Item 3 successfully added.\n");
	}
	}
}

if (!empty($stock_code_4)) {
	if (empty($description_4) AND empty($supplier_4) AND empty($price_4) AND empty($unit_4)) {
		array_push($errorMessages, "All fields for item 4 are required.\n");
		$errorCount += 1;
	}
	else {
	$product4 = new Product($stock_code_4, $description_4, $supplier_4, $price_4, $unit_4);
	$productSubmit4 = $product4->addProduct();
	if ($productSubmit4 == 0) {
		array_push($errorMessages, "Stock code 4 already exists.\n");
	}
	else {
		array_push($successMessages, "Item 4 successfully added.\n");
	}
	}
}

if (!empty($stock_code_5)) {
	if (empty($description_5) AND empty($supplier_5) AND empty($price_5) AND empty($unit_5)) {
		array_push($errorMessages, "All fields for item 5 are required.\n");
		$errorCount += 1;
	}
	else {
	$product5 = new Product($stock_code_5, $description_5, $supplier_5, $price_5, $unit_5);
	$productSubmit5 = $product5->addProduct();
	if ($productSubmit5 == 0) {
		array_push($errorMessages, "Stock code 5 already exists.\n");
	}
	else {
		array_push($successMessages, "Item 5 successfully added.\n");
	}
	}
}

if (!empty($stock_code_6)) {
	if (empty($description_6) AND empty($supplier_6) AND empty($price_6) AND empty($unit_6)) {
		array_push($errorMessages, "All fields for item 6 are required.\n");
		$errorCount += 1;
	}
	else {
	$product6 = new Product($stock_code_6, $description_6, $supplier_6, $price_6, $unit_6);
	$productSubmit6 = $product6->addProduct();
	if ($productSubmit6 == 0) {
		array_push($errorMessages, "Stock code 6 already exists.\n");
	}
	else {
		array_push($successMessages, "Item 6 successfully added.\n");
	}
	}
}





} // end of if submit


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System - Add item</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="header">
<?php include "../html/header.html"; ?>
</div>

<div class="container">
<h2>Add a new purchase item</h2>

<div class="alert alert-info mx-auto center-block" role="alert" style="width: 600px;">
<p>Enter the product information from left to right in the form below. Add up to 6 items at a time.  Click submit when complete.</p>
<p class="font-weight-bold">If a new supplier needs to be added for an item, use the <a href="addSupplier.php">add supplier page</a></p>
<!-- javascript is used below to open the page in a new window, as opposed to being on a seperate tab -->
<p class="font-weight-bold">To view existing items, <a href="viewExistingItems.php" onclick="window.open('viewExistingItems.php', 'newwindow', 'width=500, height=800'); return false;">click here</a></p>
</div>


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

<div class="container" id="success_message">
<?php 
if (!empty($successMessages)) {
?>
	<div class="alert alert-success rounded mt-1 font-weight-bold"><?php foreach ($successMessages as $successMessage) {echo nl2br($successMessage);}; ?>
<?php
}
?>
</div>



<datalist id="suppliers">
	<?php $suppliers = $productDetails->getSupplierNames(); foreach($suppliers as $supplier) { ?>
		<option value="<?php echo $supplier['supplier_name']; ?>"><?php echo $supplier['supplier_name']; ?></option>
		<?php } ?>
</datalist>

<form action="addPurchaseItem.php" method="post">

<table class="table" id="item_table">
<thead class="thead-inverse">
	<tr>
		<th>#</th>
		<th>Stock Code</th>
		<th>Item Description</th>
		<th>Supplier</th>
		<th>Price</th>
		<th>Unit</th>
	</tr>
</thead>

<tbody>
	<tr>
		<th scope="row">1</th>
		<td><input class="form-control" name="stock_code_1"></td>
		<td> <input class="form-control" name="description_1"><br></td>
		<td> <input class="form-control" name="supplier_1" list="suppliers"><br></td>
		<datalist id="suppliers"></datalist>
		<td> <input class="form-control" name="price_1"><br></td>
		<td> <input class="form-control" name="unit_1"><br></td>
	</tr>
	<tr>
		<th scope="row">2</th>
		<td><input class="form-control" name="stock_code_2"></td>
		<td> <input class="form-control" name="description_2"><br></td>
		<td> <input class="form-control" name="supplier_2" list="suppliers"><br></td>
		 <datalist id="suppliers"></datalist>
		<td> <input class="form-control" name="price_2"><br></td>
		<td> <input class="form-control" name="unit_2"><br></td>
	</tr>
	<tr>
		<th scope="row">3</th>
		<td><input class="form-control" name="stock_code_3"></td>
		<td> <input class="form-control" name="description_3"><br></td>
		<td> <input class="form-control" name="supplier_3" list="suppliers"><br></td>
		<datalist id="suppliers"></datalist>
		<td> <input class="form-control" name="price_3"><br></td>
		<td> <input class="form-control" name="unit_3"><br></td>
	</tr>
	<tr>
		<th scope="row">4</th>
		<td><input class="form-control" name="stock_code_4"></td>
		<td> <input class="form-control" name="description_4"><br></td>
		<td> <input class="form-control" name="supplier_4" list="suppliers"><br></td> 
		<datalist id="suppliers"></datalist>
		<td> <input class="form-control" name="price_4"><br></td>
		<td> <input class="form-control" name="unit_4"><br></td>
	</tr>
	<tr>
		<th scope="row">5</th>
		<td><input class="form-control" name="stock_code_5"></td>
		<td> <input class="form-control" name="description_5"><br></td>
		<td> <input class="form-control" name="supplier_5" list="suppliers"><br></td>
		<datalist id="suppliers"></datalist>
		<td> <input class="form-control" name="price_5"><br></td>
		<td> <input class="form-control" name="unit_5"><br></td>
	</tr>
	<tr>
		<th scope="row">6</th>
		<td><input class="form-control" name="stock_code_6"></td>
		<td> <input class="form-control" name="description_6"><br></td>
		<td> <input class="form-control" name="supplier_6" list="suppliers"><br></td>
		<datalist id="suppliers"></datalist>
		<td> <input class="form-control" name="price_6"><br></td>
		<td> <input class="form-control" name="unit_6"><br></td>
	</tr>
</tbody>
</table>

<div class="form-group" id="itemFormSubmit">
<input type="submit" class="form-control btn btn-outline-info font-weight-bold" name="item_submit" value="Submit"><br>
</div>

</form>


</body>
</html>