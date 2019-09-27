<?php
	/* Database credentials. */
	define('LG_DB_SERVER', 'localhost');
	define('LG_DB_USERNAME', 'php');
	define('LG_DB_PASSWORD', 'whatsapassword');
	define('LG_DB_NAME', 'logins');

	/* Attempt to connect to MySQL database */
	$loginlink = mysqli_connect(LG_DB_SERVER, LG_DB_USERNAME, LG_DB_PASSWORD, LG_DB_NAME);

	// Check connection
	if($loginlink === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
?>
