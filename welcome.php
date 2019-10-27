<?php
	// Initialize the session
	session_start();

	// Check if the user is logged in, if not then redirect him to login page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: account/login.php");
		exit;
	}

	updatePermissions();
?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
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

		<?php requirePermissionLevel(1); ?>

		<h4>Here's some links to work-in-progress pages:</h4>
		<a href=""></a>
		<a href="client/view.php">client/view.php</a><br>
		<a href="asset/view.php">asset/view.php</a><br>
		
		<br><br><b>Todo:</b><br>
		a global header<br>
		
	</body>
</html>
