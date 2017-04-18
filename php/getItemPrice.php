<?php
require __DIR__ . '/dbConfig.php';

$stockCode = intval($_GET['code']);


try {
	$dbConnect = DBC();
	$itemPriceQuery = $dbConnect->prepare("SELECT price FROM product_list WHERE stock_code = :stock_code");
	$itemPriceQuery->bindParam(":stock_code", $stockCode, PDO::PARAM_STR);
	$itemPriceQuery->execute();
	if ($itemPriceQuery->rowCount() > 0) {
		$itemPrice = $itemPriceQuery->fetchColumn();
		echo $itemPrice;
	}
}

catch (PDOException $priceError) {
	die($priceError->getMessage());
}


?>