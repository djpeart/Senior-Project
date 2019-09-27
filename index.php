<?php
	// Initialize the session
	session_start();

	// Check if the user is logged in. If not then redirect him to login page. Otherwise, sent them to the welcome screen.
	include "account/accountActions.php";
	if( isLoggedIn() ){
		header("location: welcome.php");
		exit;
	} else {
        header("location: account/login.php");
        exit;
    }
?>