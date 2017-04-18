<?php
function DBC() { // function for connecting to the database
	try {
		// add the values needed for logging in in to the object
		$dbc = new PDO("mysql:host=localhost;dbname=nea", 'dbConnect', 'password');
		return $dbc; // returns the connection to a variable if valid
	}
	catch (PDOException $dbError) {
		// if connection can't be made, the error will be displayed
		echo "Error!: " . $dbError->getMessage();
		die(); // terminate
	}

}

?>