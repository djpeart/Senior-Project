<?php
	// Initialize the session
	session_start();

	// Check if the user is logged in, if not then redirect him to login page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	/*if( !isLoggedIn() ){
		header("location: account/login.php");
		exit;
	}*/
?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
			<title>Welcome!</title>
			<style type="text/css">body{ font: 14px sans-serif;}</style>
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
		
			
		<div class="container">
			<br><nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>                        
                        </button>
                        <div class="navbar-brand" href="">Dan's Senior Project</div>
                    </div>
                    <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                            <li class="active"><a href="/">Welcome</a></li>
                            <li><a href="/client">Clients</a></li>
                            <li><a href="/asset">Assets</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
							<?php
								if ( isLoggedIn() ) {
									print "<li><a href=\"/account/reset-password.php\"><span class=\"glyphicon glyphicon-user\"></span> Change Password</a></li>\r\n"
										. "<li><a href=\"/account/logout.php\"><span class=\"glyphicon glyphicon-log-in\"></span> Sign Out</a></li>";
								} else {
									print "<li><a href=\"/account/register.php\"><span class=\"glyphicon glyphicon-user\"></span> Register</a></li>\r\n"
										. "<li><a href=\"/account/login.php\"><span class=\"glyphicon glyphicon-log-in\"></span> Sign In</a></li>";
								}
							?>
                        </ul>
                    </div>
                </div>
            </nav>

			<div class="jumbotron">

				<h2>Hi<?php echo (isset($_SESSION["username"])) ? ", <b>" . htmlspecialchars($_SESSION["username"]) . "</b>" : "" ?>. My Name is Dan Peart.</h2>
				<h1>Welcome to my Senior Project site.</h1><br />

			</div>

			<h2><b>Who are you?</b></h2>
			<h4>
				<p>
					I am a senior at Waynesburg University studying Computer Science.
				</p>
			</h4>

			<h2><b>What's this site?</b></h2>
			<h4>
				<p>
					This site is meant to be used by a small business that rents things out to customers. It keeps track of customers and the buisness's assets, keeps track of who has what, and gets their total bills.
				</p>
			</h4>

			<h2><b>Why'd you make this?</b></h2>
			<h4>
				<p>
					For my senior project, I decided to try my hand at something different. I wanted to make something useful. That's where this site came from.
				</p>
				<p>
					I didn't really know much about PHP or web development at first, so I learned a lot in the creation of this site.
				</p>
			</h4>

			<h2><b>What can it do?</b></h2>
			<h4>
				<p>
					This website has the following features:

					<ul>
						<li>Access the system from any device with a modern web browser (its mobile friendly!)</li>
						<li>Log in and view what the user has been permissioned to view</li>
						<li>Create and update records for customer information, payments, and assets.</li>
						<li>See it all in an easy to read format</li>
					</ul>
				</p>

				<p>If you'd like to see how it works, feel free to check out my <a href="https://github.com/djpeart/Senior-Project">Github page</a>
			</h4>

			<br /><br /><br /><br /><br />

			
			</div>
		</div>
	</body>
</html>
