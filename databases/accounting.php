<?php
	/* Database credentials. */
	define('ACC_DB_SERVER', 'localhost');
	define('ACC_DB_USERNAME', 'php');
	define('ACC_DB_PASSWORD', 'whatsapassword');
	define('ACC_DB_NAME', 'accounting');

	/* Attempt to connect to MySQL database */
	$acclink = mysqli_connect(ACC_DB_SERVER, ACC_DB_USERNAME, ACC_DB_PASSWORD, ACC_DB_NAME);

	// Check connection
	if($acclink === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
?>
