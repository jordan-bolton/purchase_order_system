<?php
session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}

require __DIR__ . '/classLib.php';

$errorMessage = "";

$productDetails = new ProductDetails();
$supplierNames = $productDetails->getSupplierNames();


if (isset($_POST['supplier_submit'])) {
	$supplierName = trim($_POST{'supplier_name'});
	if (!empty($supplierName)) {
		$addSupplier = $productDetails->addSupplier($supplierName);
	}

	if ($addSupplier == 1) {
		header("Location: supplierSuccess.php");
	}
	else {
		$errorMessage = "Supplier name already exists.";
	}
}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System - Add supplier</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="header">
<?php include "../html/header.html"; ?>
</div>

<div class="container">
<h2>Add a new supplier</h2>

<div class="alert alert-info mx-auto center-block" role="alert" style="width: 600px;">
<p>Enter the supplier name below.</p>
<p class="font-weight-bold">Products sold by this supplier can be added on the <a class="text-primary" href="addPurchaseItem.php">add purchase item page.</a></p>
</div>

<div id="form">
<form action="addSupplier.php" method="post" autocomplete="off">
	<div class="form-group" id="supplier_name">
	<input type="text" class="form-control" name="supplier_name" placeholder="Enter supplier name here" ><br>
	</div>
	<div class="form-group" id="supplier_submit">
	<input type="submit" class="form-control btn btn-outline-info font-weight-bold" name="supplier_submit" value="Submit"><br>
	</div>
	<?php
	if(!empty($errorMessage)) {
	?>
		<div class="alert alert-danger rounded mt-1"><?php echo $errorMessage; ?>
	<?php
	}
	?>
</form>
</div>

<div id="existing_suppliers">
<h3>Existing Suppliers</h3>
<?php /*displays each supplier that already exists */ foreach ($supplierNames as $existingSupplierName) {echo $existingSupplierName['supplier_name']; echo "<br>";}; ?>
</div>


</div>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</html>