<?php
session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}

require __DIR__ . "/classLib.php";

$productDetails = new ProductDetails();

$existingItems = $productDetails->getExistingItems();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Existing Items</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">

<table class="table" id="existing_items_table">

<thead class="thead-inverse">
	<th>Stock Code</th>
	<th>Item Description</th>
	<th>Supplier</th>
	<th>Price</th>
	<th>Unit</th>
</thead>

<tbody>
	<?php foreach($existingItems as $existingItem) {
		echo "<tr>";
		echo "<td>"; echo $existingItem['stock_code']; echo "</td>";
		echo "<td>"; echo $existingItem['description']; echo "</td>";
		echo "<td>"; echo $productDetails->getSupplier($existingItem['supplier_id']); echo "</td>";
		echo "<td>"; echo "£" . $existingItem['price']; echo "</td>";
		echo "<td>"; echo $existingItem['unit']; echo "</td>";
		echo "</tr>";
	}
	?>

</tbody>

</table>

<a href="welcome.php">Return to homepage</a>

</div>



</body>
</html>