<?php

session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}

require __DIR__ . "/classLib.php";
$order = new Order();
$nextID = $order->getLastID();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Purchase Order System</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="footer">
<?php include "../html/header.html"; ?>
</div>
<div class=" container alert alert-success mt-3" align="center">
<p>Purchase order created successfully.  The order ID is:</p>
<p class="font-weight-bold"> <?php echo $nextID - 1 ?> </p>
</div>

<div class="container">
<p class="alert alert-info">Redirecting to home page</p>
<?php header("Refresh: 3,url=welcome.php"); ?>
</div>
</body>
</html>
