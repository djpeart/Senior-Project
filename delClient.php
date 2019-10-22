<?php  ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }
    
    updatePermissions();

    // Include config file
    require_once "databases/accounting.php"; 

    // Define variables and initialize with empty values
	$ClientID = "";
	$ClientID_err= "";
    
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		// Check if nFullName is empty
		if(empty(trim($_POST["ClientID"]))){
			$ClientID_err = "Please enter the ClientID.";
		} else{
			$ClientID = trim($_POST["ClientID"]);
		}
        
		// Validate entries are in
		if(empty($ClientID_err)){
			// Prepare a select statement
			$sql = "DELETE from clients where clientid = ?";
            
			if($stmt = mysqli_prepare($acclink, $sql)){ 
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_ClientID);

				// Set parameters
                $param_ClientID = $ClientID;           

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
                    echo "Successfully deleted the record.";
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
			}
			
			// Close statement
			mysqli_stmt_close($stmt);
		} 

		// Close connection
		mysqli_close($acclink);
    }
    


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
        <div class="row">
			<div class="column edge"></div>
			<div class="column middle"> 
			
				<div class="" >

					<h2>Delete Client</h2>
					<p>Please fill in client details</p>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

						<div class="form-group <?php echo (!empty($FullName_err)) ? 'has-error' : ''; ?>">
							<label>ClientID</label>
							<input type="text" name="ClientID" class="form-control" value="<?php echo $ClientID; ?>">
							<span class="help-block"><?php echo $ClientID_err; ?></span>
						</div> 

						<div class="form-group">
							<input type="submit" class="btn btn-primary btn-block" value="Submit">
						</div>
					</form>
					<a class="btn btn-primary btn-block" href="clients.php">Back</a>
				</div>
			</div>
			<div class="column edge"></div>
        </div> 
    </body>
</html>






