<?php ini_set('display_errors',1); error_reporting(E_ALL);
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
	$FullName = $PhoneNumber = $Street = $City = $State = $ZIP = $Balance  = "";
	$FullName_err = $PhoneNumber_err = $Street_err = $City_err = $State_err = $ZIP_err = $Balance_err  = "";
    
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		// Check if nFullName is empty
		if(empty(trim($_POST["nFullName"]))){
			$FullName = "Please the full name.";
		} else{
			$FullName_err = trim($_POST["nFullName"]);
		}

		// Check if nPhoneNumber is empty
		if(empty(trim($_POST["nPhoneNumber"]))){
			$PhoneNumber_err = "Please enter the phone number.";
		} else{
			$PhoneNumber = trim($_POST["nPhoneNumber"]);
        }
        
        // Check if nStreet is empty
		if(empty(trim($_POST["nStreet"]))){
			$Street_err = "Please enter the street address.";
		} else{
			$Street = trim($_POST["nStreet"]);
        }
        
        // Check if nCity is empty
		if(empty(trim($_POST["nCity"]))){
			$City_err = "Please enter the city.";
		} else{
			$City = trim($_POST["nCity"]);
        }
        
        // Check if nState is empty
		if(empty(trim($_POST["nState"]))){
			$State_err = "Please enter the state.";
		} else{
			$State = trim($_POST["nState"]);
        }
        
        // Check if nZIP is empty
		if(empty(trim($_POST["nZIP"]))){
			$ZIP_err = "Please enter the ZIP code.";
		} else{
			$ZIP = trim($_POST["nZIP"]);
        }
        
		// Validate entries are in
		if(empty($FullName_err) && empty($PhoneNumber_err) && empty($Street_err) && empty($City_err) && empty($State_err) && empty($ZIP_err)){
			// Prepare a select statement
			$sql = "INSERT INTO clients (FullName, PhoneNumber, Street, City, State, ZIP) VALUES (?, ?, ?, ?, ?, ?)";
            
			if($stmt = mysqli_prepare($acclink, $sql)){ //This is the line that gives me the error
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "sssssi", $param_formFullName, $param_formPhoneNumber, $param_formStreet, $param_formCity, $param_formState, $param_formZIP, $param_formBalance);

				// Set parameters
                $param_formFullName = $FullName;
                $param_formPhoneNumber = $PhoneNumber;
                $param_formStreet = $Street;
                $param_formCity = $City;
                $param_formState = $State;
                $param_formZIP = $ZIP;
                $param_formBalance = $Balance;                

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
                    echo "Successfully saved the record."
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

                    <h2>Add Client</h2>
                    <p>Please fill in client details</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                            <span class="help-block"><?php echo $username_err; ?></span>
                        </div> 
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control">
                            <span class="help-block"><?php echo $password_err; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block" value="Login">
                        </div>
                    </form>

                </div>
        </div> 
    </body>
</html>






