<?php  ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	include $_SERVER['DOCUMENT_ROOT'] . '/alerts.php';
	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }
    
    requirePermissionLevel(2);

	//include $_SERVER['DOCUMENT_ROOT'] . '/log/logActions.php';

    // Include config file
    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 

    // Define variables and initialize with empty values
	$FullName = $PhoneNumber = $Street = $City = $State = $ZIP = "";
	$FullName_err = $PhoneNumber_err = $Street_err = $City_err = $State_err = $ZIP_err = "";
    
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
			mysqli_stmt_bind_param($stmt, "sssssi", $param_FullName, $param_PhoneNumber, $param_Street, $param_City, $param_State, $param_ZIP);
			

				// Set parameters
                $param_FullName = $FullName;
                $param_PhoneNumber = $PhoneNumber;
                $param_Street = $Street;
                $param_City = $City;
                $param_State = $State;
				$param_ZIP = $ZIP;      

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					alert("alert-success","Success!", "The record has been saved.");
				} else{
					alert("alert-danger","Error!", "An error has occured and your record has not been saved.");
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
			<title>Add Client</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
        <div class="container">

			<div class="text-center">
				<h2>Add Client</h2>
				<p>Please fill in client details</p>
			</div>
			<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

				<div class="form-group <?php echo (!empty($FullName_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">Full Name</label>
					<div class="col-sm-offset-2 col-sm-10">
						<input type="text" name="FullName" class="form-control" value="<?php echo $FullName; ?>">
						<span class="help-block"><?php echo $FullName_err; ?></span>
					</div>
				</div> 

				<div class="form-group <?php echo (!empty($PhoneNumber_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">Phone Number</label>
					<div class="col-sm-offset-2 col-sm-10">
						<input type="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="PhoneNumber" class="form-control" placeholder="012-345-6789">
						<span class="help-block"><?php echo $PhoneNumber_err; ?></span>
					</div>
				</div>
				
				<div class="form-group <?php echo (!empty($Street_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">Street</label>
					<div class="col-sm-offset-2 col-sm-10">
						<input type="text" name="Street" class="form-control">
						<span class="help-block"><?php echo $Street_err; ?></span>
					</div>
				</div>

				<div class="form-group <?php echo (!empty($City_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">City</label>
					<div class="col-sm-offset-2 col-sm-10">
						<input type="text" name="City" class="form-control">
						<span class="help-block"><?php echo $City_err; ?></span>
					</div>
				</div>

				<div class="form-group <?php echo (!empty($State_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">State</label>
					<div class="col-sm-offset-2 col-sm-10">
						<input type="text" name="State" class="form-control">
						<span class="help-block"><?php echo $State_err; ?></span>
					</div>
				</div>

				<div class="form-group <?php echo (!empty($ZIP_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">ZIP</label>
					<div class="col-sm-offset-2 col-sm-10">
						<input type="text" name="ZIP" class="form-control" placeholder="12345">
						<span class="help-block"><?php echo $ZIP_err; ?></span>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="submit" class="btn btn-primary " value="Submit">
						<a class="btn btn-default" href="view.php">Back</a>
					</div>
				</div>
			</form>
			
			
        </div> 
    </body>
</html>






