<?php
	// Initialize the session
	session_start();

	// Check if the user is logged in, if not then redirect him to login page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: account/login.php");
		exit;
	}
?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
			<title>Welcome!</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
			<style type="text/css">body{ font: 14px sans-serif; text-align: center; }</style>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
		<?php requirePermissionLevel(1); ?>		
		<div class="container">
			<br><nav class="navbar navbar-inverse">
				<div class="container-fluid">
					<div class="navbar-header">
						<div class="navbar-brand" href="">Dan's Senior Project</div>
					</div>
					<ul class="nav navbar-nav">
						<li class="active"><a href="#">Welcome</a></li>
						<li><a href="/client">Clients</a></li>
						<li><a href="/asset/view.php">Assets</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="/account/reset-password.php"><span class="glyphicon glyphicon-user"></span> Change Password</a></li>
						<li><a href="/account/logout.php"><span class="glyphicon glyphicon-log-in"></span> Sign Out</a></li>
					</ul>
				</div>
			</nav>

			<div class="jumbotron">
				<h1><h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to my Senior Project site.</h1></h1>
				<p>Bootstrap is the most popular HTML, CSS, and JS framework for developing
				responsive, mobile-first projects on the web.</p>
			</div>
				<p>This is some text.</p>
				<p>This is another text.</p>
			</div>
		</div>

		
	</body>
</html>
