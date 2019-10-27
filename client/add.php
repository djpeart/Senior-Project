<?php  ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }
    
    updatePermissions();

	//include $_SERVER['DOCUMENT_ROOT'] . '/log/logActions.php';

    // Include config file
    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 

    // Define variables and initialize with empty values
	$FullName = $PhoneNumber = $Street = $City = $State = $ZIP = $Balance  = "";
	$FullName_err = $PhoneNumber_err = $Street_err = $City_err = $State_err = $ZIP_err = $Balance_err  = "";
    
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		// Check if nFullName is empty
		if(empty(trim($_POST["FullName"]))){
			$FullName_err = "Please the full name.";
		} else{
			$FullName = trim($_POST["FullName"]);
		}

		// Check if nPhoneNumber is empty
		if(empty(trim($_POST["PhoneNumber"]))){
			$PhoneNumber_err = "Please enter the phone number.";
		} else{
			$PhoneNumber = trim($_POST["PhoneNumber"]);
        }
        
        // Check if nStreet is empty
		if(empty(trim($_POST["Street"]))){
			$Street_err = "Please enter the street address.";
		} else{
			$Street = trim($_POST["Street"]);
        }
        
        // Check if nCity is empty
		if(empty(trim($_POST["City"]))){
			$City_err = "Please enter the city.";
		} else{
			$City = trim($_POST["City"]);
        }
        
        // Check if nState is empty
		if(empty(trim($_POST["State"]))){
			$State_err = "Please enter the state.";
		} else{
			$State = trim($_POST["State"]);
        }
        
        // Check if nZIP is empty
		if(empty(trim($_POST["ZIP"]))){
			$ZIP_err = "Please enter the ZIP code.";
		} else{
			$ZIP = trim($_POST["ZIP"]);
        }
        
		// Validate entries are in
		if(empty($FullName_err) && empty($PhoneNumber_err) && empty($Street_err) && empty($City_err) && empty($State_err) && empty($ZIP_err)){
			// Prepare a select statement
			$sql = "INSERT INTO clients (FullName, PhoneNumber, Street, City, State, ZIP) VALUES (?, ?, ?, ?, ?, ?)";
            
			if($stmt = mysqli_prepare($acclink, $sql)){ //This is the line that gives me the error
				// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "sssssi", $param_formFullName, $param_formPhoneNumber, $param_formStreet, $param_formCity, $param_formState, $param_formZIP);

				// Set parameters
                $param_formFullName = $FullName;
                $param_formPhoneNumber = $PhoneNumber;
                $param_formStreet = $Street;
                $param_formCity = $City;
                $param_formState = $State;
                $param_formZIP = $ZIP;               

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					echo "Successfully saved the record.";
					//logprint("added client" . $FullName);
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

						<div class="form-group <?php echo (!empty($FullName_err)) ? 'has-error' : ''; ?>">
							<label>Full Name</label>
							<input type="text" name="FullName" class="form-control" value="<?php echo $FullName; ?>">
							<span class="help-block"><?php echo $FullName_err; ?></span>
						</div> 

						<div class="form-group <?php echo (!empty($PhoneNumber_err)) ? 'has-error' : ''; ?>">
							<label>Phone Number</label>
							<input type="tel" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" name="PhoneNumber" class="form-control">
							<span class="help-block"><?php echo $PhoneNumber_err; ?></span>
						</div>
						
						<div class="form-group <?php echo (!empty($Street_err)) ? 'has-error' : ''; ?>">
							<label>Street</label>
							<input type="text" name="Street" class="form-control">
							<span class="help-block"><?php echo $Street_err; ?></span>
						</div>

						<div class="form-group <?php echo (!empty($City_err)) ? 'has-error' : ''; ?>">
							<label>City</label>
							<input type="text" name="City" class="form-control">
							<span class="help-block"><?php echo $City_err; ?></span>
						</div>

						<div class="form-group <?php echo (!empty($State_err)) ? 'has-error' : ''; ?>">
							<label>State</label>
							<input type="text" name="State" class="form-control">
							<span class="help-block"><?php echo $State_err; ?></span>
						</div>

						<div class="form-group <?php echo (!empty($ZIP_err)) ? 'has-error' : ''; ?>">
							<label>ZIP</label>
							<input type="text" name="ZIP" class="form-control">
							<span class="help-block"><?php echo $ZIP_err; ?></span>
						</div>

						<div class="form-group <?php echo (!empty($Balance_err)) ? 'has-error' : ''; ?>">
							<label>Balance</label>
							<input type="text" name="Balance" class="form-control" value=0>
							<span class="help-block"><?php echo $Balance_err; ?></span>
						</div>

						<div class="form-group">
							<input type="submit" class="btn btn-primary btn-block" value="Submit">
						</div>
					</form>
					<a class="btn btn-primary btn-block" href="view.php">Back</a>
				</div>
			</div>
			<div class="column edge"></div>
        </div> 
    </body>
</html>






