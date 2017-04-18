<?php

require __DIR__ . '/dbConfig.php';

$stockCode = intval($_GET['code']);

try {
	$dbConnect = DBC();
	$itemDescriptionQuery = $dbConnect->prepare("SELECT description FROM product_list WHERE stock_code = :stock_code");
	$itemDescriptionQuery->bindParam(":stock_code", $stockCode, PDO::PARAM_STR);
	$itemDescriptionQuery->execute();
	if ($itemDescriptionQuery->rowCount() > 0) {
		$itemDescription = $itemDescriptionQuery->fetchColumn();
		echo $itemDescription;

	}
}
catch (PDOException $descriptionError) {
	die($descriptionError->getMessage());
}

?>