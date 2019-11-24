<?php
	// Include config file
	require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/login.php';

	// Define variables and initialize with empty values
	$username = $password = $confirm_password = "";
	$username_err = $password_err = $confirm_password_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		// Validate username
		if(empty(trim($_POST["username"]))){
			$username_err = "Please enter a username.";
		} else{
			// Prepare a select statement
			$sql = "SELECT id FROM users WHERE username = ?";

			if($stmt = mysqli_prepare($loginlink, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_username);

				// Set parameters
				$param_username = trim($_POST["username"]);

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					/* store result */
					mysqli_stmt_store_result($stmt);

					if(mysqli_stmt_num_rows($stmt) == 1){
						$username_err = "This username is already taken.";
					} else{
						$username = trim($_POST["username"]);
					}
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
			}

		// Close statement
		mysqli_stmt_close($stmt);
		}

		// Validate password
		if(empty(trim($_POST["password"]))){
			$password_err = "Please enter a password."; 
		} elseif(strlen(trim($_POST["password"])) < 8){
			$password_err = "Password must have atleast 8 characters.";
		} else{
			$password = trim($_POST["password"]);
		}

		// Validate confirm password
		if(empty(trim($_POST["confirm_password"]))){
			$confirm_password_err = "Please confirm password."; 
		} else{
			$confirm_password = trim($_POST["confirm_password"]);
			if(empty($password_err) && ($password != $confirm_password)){
				$confirm_password_err = "Password did not match.";
			}
		}

		// Check input errors before inserting in database
		if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

			// Prepare an insert statement
			$sql = "INSERT INTO users (username, password) VALUES (?, ?)";

			if($stmt = mysqli_prepare($loginlink, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

				// Set parameters
				$param_username = $username;
				$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Redirect to login page
					header("location: login.php");
				} else{
					echo "Something went wrong. Please try again later.";
				}
			}

			// Close statement
			mysqli_stmt_close($stmt);
		}

		// Close connection
		mysqli_close($loginlink);
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
		<title>Register</title>
		<style type="text/css">body{ font: 14px sans-serif;}</style>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-4"></div>
				
				<div class="col-md-4" >
					<h2>Sign Up</h2>
					<p>Fill this form to request an account.</p>
					<p>Please note that you will be able to sign in, but you won't have access to anything yet.</p>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
						<label>Username</label>
						<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
						<span class="help-block"><?php echo $username_err; ?></span>
					</div> 
					<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
						<label>Password</label>
						<input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
						<span class="help-block"><?php echo $password_err; ?></span>
					</div>
					<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
						<label>Confirm Password</label>
						<input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
						<span class="help-block"><?php echo $confirm_password_err; ?></span>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-primary" value="Submit">
						<a class="btn btn-default" href="/">Back</a><br /><br />
					</div>
					<p>Once you've made your account, <a href="login.php">login here</a>.</p>
					</form>
				</div>
			</div>
		</div> 
	</body>
</html>