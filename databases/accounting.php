<?php
	/* Database credentials. */
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'php');
	define('DB_PASSWORD', 'whatsapassword');
	define('DB_NAME', 'accounting');

	/* Attempt to connect to MySQL database */
	$acclink = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	// Check connection
	if($acclink === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
?>
