<?php
session_start();
if(empty($_SESSION['user_id'])) { // If user is not logged in, redirect to the login page (index)
	header("Location: ../index.php");
	exit;
}


require __DIR__ . '/classLib.php';

$order = new Order();


if(!empty($_POST['order_id_delivered'])) {
	$order_id_delivered = trim($_POST['order_id_delivered']);
	$markAsDelivered = $order->markAsDelivered($order_id_delivered);
	if ($markAsDelivered == 1) {
		header("Location: welcome.php");
	}
}
else {
	echo "An error occured.";
}


?>