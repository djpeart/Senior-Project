<?php
	// Initialize the session
	session_start();

	//testing

	// Check if the user is logged in, if not then redirect him to login page
	include "account/accountActions.php";
	if( !isLoggedIn() ){
		header("location: account/login.php");
		exit;
	}

	updatePermissions();
?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Welcome</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
			<style type="text/css">body{ font: 14px sans-serif; text-align: center; }</style>
		</head>
	<body>
		<div class="page-header">
		<h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to my Senior Project site.</h1>
		<p>
		<a href="account/reset-password.php" class="btn btn-warning">Update Password</a>
		<a href="account/logout.php" class="btn btn-danger">Sign Out</a>
		</p>
		</div>

		<?php 
			if ($_SESSION["permlevel"] < 1) {
				print "<pre class=\"alert-warning\"><h1> You do not have permission to read data yet!</h1></pre>";
				exit;
			}
		?>

		more data

	</body>
</html>