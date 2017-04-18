<?php

require __DIR__ . "/dbConfig.php";

class userLogin // class for functions associated with users & logging in
{

public function processLogin($username, $password) // function used to log the user in
{
	try {
		$dbConnect = DBC(); // object for connecting to the database
		// prepares the SQL statement for execution
		$loginQuery = $dbConnect->prepare("SELECT userID FROM users WHERE username=:username AND password=:password");
		// associates the username value in the SQL statement with the username variable passed through from the form
		$loginQuery->bindParam(":username", $username, PDO::PARAM_STR); 
		// encrypts the password using the sha256 algorithm
		$passwordEncrypt = hash('sha256', $password);
		// associates the password value in the SQL statement with the password variable passed through from the form
		$loginQuery->bindParam(":password", $password, PDO::PARAM_STR);
		// executes the statement and returns the number of rows
		$loginQuery->execute();
		// if the query matches the data in the database, a row will be returned
		if($loginQuery->rowCount() > 0) {
			// fetch the data from the row that is returned
			$loginResult = $loginQuery->fetch(PDO::FETCH_OBJ);
			return $loginResult->userID;
		} 
		else {
			// if the login is invalid
			return false;
		}
	}
	catch (PDOException $errorMessage) {
		die($errorMessage->getMessage()); // terminates and returns an error if one occurs
	}
}

public function getUserDetails($userID) 
{
	try {
		$dbConnect = DBC();
		$detailsQuery = $dbConnect->prepare("SELECT forename, surname FROM users WHERE userID=:userID");
		$detailsQuery->bindParam(":userID", $userID, PDO::PARAM_STR);
		$detailsQuery->execute();
		if ($detailsQuery->rowCount() > 0) {
			return $detailsQuery->fetch(PDO::FETCH_OBJ);
		}
	}
	catch (PDOException $errorMessage) {
		die($errorMessage->getMessage());
	}
}

}

class Order
{
// function for adding a purchase order to the databsae
public function createOrder($order_id, $creator_name, $order_stock_code_1, $quantity_1, $order_stock_code_2, $quantity_2, $order_stock_code_3, $quantity_3, $order_stock_code_4, $quantity_4, $order_stock_code_5, $quantity_5, $order_stock_code_6, $quantity_6, $date_required, $special_comments) {
	try {
		$dbConnect = DBC();
		$dbConnect->beginTransaction(); // doesn't auto commit SQL as multiple statements need to be executed before commiting
		// statement for adding order details to the database, and binding appropriate params to be added
		$purchaseOrderQuery = $dbConnect->prepare("INSERT INTO purchase_orders (order_id, creator_name, order_date, date_required, received, special_comments) VALUES (:order_id, :creator_name, NOW(), :date_required, 0, :special_comments)");
		$purchaseOrderQuery->bindParam(":order_id", $order_id, PDO::PARAM_INT);
		$purchaseOrderQuery->bindParam(":creator_name", $creator_name, PDO::PARAM_STR);
		$purchaseOrderQuery->bindParam(":date_required", $date_required, PDO::PARAM_STR);
		$purchaseOrderQuery->bindParam(":special_comments", $special_comments, PDO::PARAM_STR);
		$purchaseOrderQuery->execute();
		// 2d array for storing item details to be added to the order_details table
		$stockToInsert = array (
			array($order_id, $order_stock_code_1, $quantity_1),
			array($order_id, $order_stock_code_2, $quantity_2),
			array($order_id, $order_stock_code_3, $quantity_3),
			array($order_id, $order_stock_code_4, $quantity_4),
			array($order_id, $order_stock_code_5, $quantity_5),
			array($order_id, $order_stock_code_6, $quantity_6)
		);
		// statement for adding each item in to the order_detauks table
		$orderDetailsQuery = $dbConnect->prepare("INSERT INTO order_details (order_id, stock_code, quantity) VALUES (?, ?, ?)");
		// loop to iterate through each item, adding each to the table
		foreach ($stockToInsert as $rowToInsert) {
			$orderDetailsQuery->execute($rowToInsert);
		}
		// commit to both changes
		$dbConnect->commit();
		if ($purchaseOrderQuery->rowCount() > 0 AND $orderDetailsQuery->rowCount() > 0) {
			return 1;
		}
		else {
			$dbConnect->rollBack();
			return "Error occured";
		}
	}
	catch (PDOException $creationError) {
		// if a problem occurs, changes are rolled back and an error is displayed
		die($creationError->getMessage());
		$dbConnect->rollBack();
	}
}

// gets the last order id from the database for the next order, so there are no conflicts
public function getLastID() {
	try {
		$dbConnect = DBC();
		$idQuery = $dbConnect->prepare("SELECT MAX(order_id) FROM purchase_orders");
		$idQuery->execute();
		// casts the value as in integer so that one can be added; the next available order ID
		$idInt =  (int) $idQuery->fetchColumn() + 1;
		return $idInt;
	}
	catch (PDOException $idError) {
		die($idError->getMessage());
	}
}

// function for getting orders that haven't been marked as delivered
public function getUndeliveredOrders() {
	try {
		$dbConnect = DBC();
		$undeliveredOrdersQuery = $dbConnect->prepare("SELECT order_id, order_date, date_required FROM purchase_orders WHERE received = 0");
		$undeliveredOrdersQuery->execute();
		if ($undeliveredOrdersQuery->rowCount() > 0) {
			return $undeliveredOrdersQuery->fetchAll();
		}
	}
	catch (PDOException $undeliveredOrdersQueryError) {
		die($undeliveredOrdersQueryError->getMessage());
	}
}
// function for orders that haven't had a confirmation generated yet
public function getUnconfirmedOrders() {
	try {
		$dbConnect = DBC();
		$unconfirmedOrdersQuery = $dbConnect->prepare("SELECT order_id, order_date, date_required FROM purchase_orders WHERE confirmation_generated = 0");
		$unconfirmedOrdersQuery->execute();
		if ($unconfirmedOrdersQuery->rowCount() > 0) {
			return $unconfirmedOrdersQuery->fetchAll();
		}
	}
	catch (PDOException $unconfirmedOrdersQueryError) {
		die($unconfirmedOrdersQueryError->getMessage());
	}
}
// used to get all order details (used in updateOrder.php)
public function getOrderDetails($orderID) {
	try {
		$dbConnect = DBC();
		$orderDetails = $dbConnect->prepare("SELECT * FROM purchase_orders, order_details, product_list WHERE purchase_orders.order_id = :order_id AND purchase_orders.order_id = order_details.order_id AND product_list.stock_code = order_details.stock_code");
		$orderDetails->bindParam(":order_id", $orderID, PDO::PARAM_INT);
		$orderDetails->execute();
		if ($orderDetails->rowCount() > 0) {
			return $orderDetails->fetchAll();
		}
	}
	catch (PDOException $orderDetailsError) {
		die($orderDetailsError->getMessage());
	}
}
// function for deleting an order
public function deleteOrder($orderID) {
	try {
		$dbConnect = DBC();
		$deleteOrderQuery = $dbConnect->prepare("DELETE FROM purchase_orders, order_details USING purchase_orders, order_details WHERE purchase_orders.order_id = :order_id AND purchase_orders.order_id = order_details.order_id");
		$deleteOrderQuery->bindParam(":order_id", $orderID, PDO::PARAM_INT);
		$deleteOrderQuery->execute();
		if ($deleteOrderQuery->rowCount() > 0) {
			return 1;
		}
	}
	catch (PDOException $deleteOrderQueryError) {
		die($deleteOrderQueryError->getMessage());
	}
}

// function used to update an existing order's details (date, creator name)
public function updateOrderDetails($order_id, $creator_name, $order_date) {
	try {
	$dbConnect = DBC();
	$dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$updateOrderQuery = $dbConnect->prepare("UPDATE purchase_orders SET creator_name = :creator_name, order_date = :order_date WHERE order_id = :order_id");
	$updateOrderQuery->bindParam(":creator_name", $creator_name, PDO::PARAM_STR);
	$updateOrderQuery->bindParam(":order_date", $order_date, PDO::PARAM_STR);
	$updateOrderQuery->bindParam(":order_id", $order_id, PDO::PARAM_INT);
	$updateOrderQuery->execute();

	if ($updateOrderQuery->rowCount() > 0) {
		return 1;
	}
	}
	catch (PDOException $updateError) {
		// if a problem occurs, changes are rolled back and an error is displayed
		die($updateError->getMessage());
	}
}

public function updateStockCodes($stock_code, $quantity, $order_id, $original_stock_code) {
	try {
		$dbConnect = DBC();
		$updateStockCodesQuery = $dbConnect->prepare("UPDATE order_details SET stock_code = :stock_code, quantity = :quantity WHERE order_id = :order_id AND stock_code = :original_stock_code");
		$updateStockCodesQuery->bindParam(":stock_code", $stock_code, PDO::PARAM_INT);
		$updateStockCodesQuery->bindParam(":quantity", $quantity, PDO::PARAM_INT);
		$updateStockCodesQuery->bindParam(":order_id", $order_id, PDO::PARAM_INT);
		$updateStockCodesQuery->bindParam(":original_stock_code", $original_stock_code, PDO::PARAM_INT);
		$updateStockCodesQuery->execute();
		if ($updateStockCodesQuery->rowCount() > 0) {
			return 1;
		}
	}

	catch (PDOException $updateStockCodesQueryError) {
		die($updateStockCodesQueryError->getMessage());
	}
}

// function used to update an order with regards to the delivery required date, special comments, and whether the order has been received
// allows the function to be used to update each column field individually by passin in the column name and the new value
public function updateOrderRequirements($column_name, $new_value, $order_id) {
	try {
		$dbConnect = DBC();
		$updateOrderRequirementsQuery = $dbConnect->prepare("UPDATE purchase_orders SET {$column_name} = :new_value WHERE order_id = :order_id");
		$updateOrderRequirementsQuery->bindParam(":new_value", $new_value, PDO::PARAM_STR);
		$updateOrderRequirementsQuery->bindParam(":order_id", $order_id, PDO::PARAM_INT);
		$updateOrderRequirementsQuery->execute();
		if ($updateOrderRequirementsQuery->rowCount() > 0) {
			return 1;
		}
	}
	catch (PDOException $updateOrderRequirementsQueryError) {
		die($updateOrderRequirementsQueryError->getMessage());
	}
}

// same function as generateConfirmation, but doesn't mark confirmation as generated, instead only gets data
public function getExistingItemData($orderID) {
	try {
		$dbConnect = DBC();
		$existingOrderData = $dbConnect->prepare("SELECT purchase_orders.order_id, creator_name, order_date, date_required, received, date_received, special_comments, confirmation_generated, product_list.stock_code, description, quantity, unit, price FROM purchase_orders, order_details, product_list WHERE purchase_orders.order_id = :order_id AND purchase_orders.order_id = order_details.order_id AND order_details.stock_code = product_list.stock_code
");
		$existingOrderData->bindParam(":order_id", $orderID, PDO::PARAM_INT);
		$existingOrderData->execute();
		if ($existingOrderData->rowCount() > 0) {
			$resultArray = array();
			while ($row = $existingOrderData->fetch(PDO::FETCH_ASSOC)) {
				$resultArray[] = $row;
			}
			return $resultArray;
		}
	}
	catch (PDOException $existingOrderDataError) {
		die($existingOrderDataError->getMessage());
	}
}

public function markAsDelivered($orderID) {
	try {
		$dbConnect = DBC();
		$markAsDeliveredQuery = $dbConnect->prepare("UPDATE purchase_orders SET received=1, date_received=NOW() WHERE order_id = :order_id");
		$markAsDeliveredQuery->bindParam(":order_id", $orderID, PDO::PARAM_INT);
		$markAsDeliveredQuery->execute();
		if ($markAsDeliveredQuery->rowCount() > 0) {
			return 1;
		}
	}
	catch (PDOException $markAsDeliveredQueryError) {
		die($markAsDeliveredQueryError->getMessage());
	}
}

}

class OrderConfirmation
{

public $orderID;

// constructor so that all objects have the same properties (in this case, an order ID)
public function __construct($orderID) {
	$this->orderID = $orderID;
}

public function generateConfirmation() {
	try {
		$dbConnect = DBC();
		$confirmationQuery = $dbConnect->prepare("SELECT purchase_orders.order_id, creator_name, order_date, date_required, special_comments, product_list.stock_code, description, quantity, unit, price FROM purchase_orders, order_details, product_list WHERE purchase_orders.order_id = :order_id AND purchase_orders.order_id = order_details.order_id AND order_details.stock_code = product_list.stock_code
");
		$confirmationQuery->bindParam(":order_id", $this->orderID, PDO::PARAM_INT);
		$confirmationQuery->execute();
		if ($confirmationQuery->rowCount() > 0) {
			$resultArray = array();
			while ($row = $confirmationQuery->fetch(PDO::FETCH_ASSOC)) {
				$resultArray[] = $row;
			}
			// calls the protected function to mark purchase order as having a confirmation generated
			$markConfirmationGenerated = $this->markConfirmationGenerated();
			return $resultArray;
		}
	}
	catch (PDOException $confirmationError) {
		die($confirmationError->getMessage());
	}
}

public function getSupplierFromStockCode($stockCode) {
	try {
		$dbConnect = DBC();
		$supplierNameStockQuery = $dbConnect->prepare("SELECT supplier_name FROM supplier_list, product_list, order_details WHERE product_list.stock_code = :stock_code AND product_list.stock_code = order_details.stock_code AND product_list.supplier_id = supplier_list.supplier_id;");
		$supplierNameStockQuery->bindParam(":stock_code", $stockCode, PDO::PARAM_INT);
		$supplierNameStockQuery->execute();
		if ($supplierNameStockQuery->rowCount() > 0) {
			return $supplierNameStockQuery->fetchColumn();
		}
	}
	catch (PDOException $supplierNameStockQueryError) {
		die($supplierNameStockQueryError->getMessage());
	}
}

public function calculateTotalValue($stockCode) {
	try {
		$dbConnect = DBC();
		$totalValueQuery = $dbConnect->prepare("SELECT price * quantity FROM product_list, order_details WHERE order_details.stock_code = :stock_code AND order_details.order_id = :order_id AND product_list.stock_code = order_details.stock_code");
		$totalValueQuery->bindParam(":stock_code", $stockCode, PDO::PARAM_INT);
		$totalValueQuery->bindParam("order_id", $this->orderID, PDO::PARAM_INT);
		$totalValueQuery->execute();
		if ($totalValueQuery->rowCount() > 0) {
			return $totalValueQuery->fetchColumn();
		}
	}
	catch (PDOException $totalValueQueryError) {
		die($totalValueQueryError->getMessage());
	}
}

// function is protected so that it can only be accessed from within this class
protected function markConfirmationGenerated() {
	try {
		$dbConnect = DBC();
		$markConfirmationGeneratedQuery = $dbConnect->prepare("UPDATE purchase_orders SET confirmation_generated=1 WHERE order_id = :order_id");
		$markConfirmationGeneratedQuery->bindParam(":order_id", $this->orderID, PDO::PARAM_INT);
		$markConfirmationGeneratedQuery->execute();
		if ($markConfirmationGeneratedQuery->rowCount() > 0) {
			return 1;
		}
	}
	catch (PDOException $markConfirmationGeneratedQueryError) {
		die($markConfirmationGeneratedQueryError->getMessage());
	}
}

}

class Product
{

// declare variables to be used when constructing a product
public $productStockCode;
public $productDescription;
public $productSupplierID;
public $productPrice;
public $productUnit;

// constructor for an object, so that all instances have the same properties
public function __construct($productStockCode, $productDescription, $productSupplierID, $productPrice, $productUnit) {
	// allows for methods from other classes to be called in this class
	$this->productStockCode = $productStockCode;
	$this->productDescription = $productDescription;
	$this->productSupplierID = $productSupplierID;
	$this->productPrice = $productPrice;
	$this->productUnit = $productUnit;
}



public function addProduct() {
	// sets the variable equal to the id of the supplier selected
	$this->productSupplierID = ProductDetails::getSupplierID($this->productSupplierID);
	try {
		$dbConnect = DBC();
		$addProductQuery = $dbConnect->prepare("INSERT INTO product_list (stock_code, description, supplier_id, price, unit) VALUES (:stock_code, :description, :supplier_id, :price, :unit)");
		$addProductQuery->bindParam(":stock_code", $this->productStockCode, PDO::PARAM_INT);
		$addProductQuery->bindParam(":description", $this->productDescription, PDO::PARAM_STR);
		$addProductQuery->bindParam(":supplier_id", $this->productSupplierID, PDO::PARAM_STR);
		$addProductQuery->bindParam(":price", $this->productPrice, PDO::PARAM_STR);
		$addProductQuery->bindParam(":unit", $this->productUnit, PDO::PARAM_STR);
		$addProductQuery->execute();
		if ($addProductQuery->rowCount() > 0) {
			return 1;
		}
		else {
			return 0;
		}
	}
	catch (PDOException $addProductError) {
		die($addProductError->getMessage());
	}
}

}

class ProductDetails
{

// gets all existing stock codes, regardless of supplier
public function getExistingItems() {
	try {
		$dbConnect = DBC();
		$existingItemsQuery = $dbConnect->prepare("SELECT * FROM product_list");
		$existingItemsQuery->execute();
		if ($existingItemsQuery->rowCount() > 0) {
			return $existingItemsQuery->fetchAll();
		}
	}
	catch (PDOException $existingItemsQueryError) {
		die($existingItemsQueryError->getMessage());
	}
}

public function getSupplier($supplierID) {
	try {
		$dbConnect = DBC();
		$getSupplierQuery = $dbConnect->prepare("SELECT supplier_name FROM supplier_list, product_list WHERE product_list.supplier_id = :supplier_id AND supplier_list.supplier_id = product_list.supplier_id");
		$getSupplierQuery->bindParam(":supplier_id", $supplierID, PDO::PARAM_INT);
		$getSupplierQuery->execute();
		if ($getSupplierQuery->rowCount() > 0) {
			return $getSupplierQuery->fetchColumn();
		}
	}
	catch (PDOException $getSupplierError) {
		die($getSupplierError->getMessage());
	}
}

// function is static so that it can be accessed by other classes but not through instantiated objects
public static function getSupplierID($supplierName) {
	try {
		$dbConnect = DBC();
		$supplierIdQuery = $dbConnect->prepare("SELECT supplier_id FROM supplier_list WHERE supplier_name = :supplier_name");
		$supplierIdQuery->bindParam(":supplier_name", $supplierName, PDO::PARAM_STR);
		$supplierIdQuery->execute();
		if ($supplierIdQuery->rowCount() > 0) {
			return $supplierIdQuery->fetchColumn();
		}
	}
	catch (PDOException $supplierIdError) {
		die($supplierIdError->getMessage());
	}
}

// gets all stock codes from a specific supplier
public function getStockCodes($supplierName) {
	try {
		$dbConnect = DBC();
		$stockCodeQuery = $dbConnect->prepare("SELECT stock_code FROM product_list, supplier_list WHERE product_list.supplier_id = :supplier_id AND supplier_list.supplier_id = product_list.supplier_id");
		$stockCodeQuery->bindValue(":supplier_id", $this->getSupplierID($supplierName), PDO::PARAM_INT);
		$stockCodeQuery->execute();
		if ($stockCodeQuery->rowCount() > 0) {
			return $stockCodeQuery->fetchAll();
		}
	}
	catch (PDOException $stockCodeError) {
		die($stockCodeError->getMessage());
	}

}



// a function to return all the existing supplier names in the table
public function getSupplierNames() {
	try {
		$dbConnect = DBC();
		$supplierNameQuery = $dbConnect->prepare("SELECT supplier_name FROM supplier_list");
		$supplierNameQuery->execute();
		if ($supplierNameQuery->rowCount() > 0) {
			$supplierNames = $supplierNameQuery->fetchAll();
			return $supplierNames;
		}
	}
	catch (PDOException $supplierNameError) {
		die($supplierError->getMessage());
	}
}

// adds a new supplier to the supplier table
public function addSupplier($supplierName) {
	try {
		$dbConnect = DBC();
		$supplierQuery = $dbConnect->prepare("INSERT INTO supplier_list (supplier_name) VALUES (:supplier_name)");
		$supplierQuery->bindParam(":supplier_name", $supplierName, PDO::PARAM_STR);
		$supplierQuery->execute();
		if ($supplierQuery->rowCount() > 0) {
			return 1;
		}
	}
	catch (PDOException $supplierError) {
		die($supplierError->getMessage());
	}
}


}
?>