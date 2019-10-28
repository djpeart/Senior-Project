<?php  ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

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
	$FullName = $PhoneNumber = $Street = $City = $State = $ZIP = $Balance = $ClientID = "";
	$FullName_err = $PhoneNumber_err = $Street_err = $City_err = $State_err = $ZIP_err = $Balance_err  = "";    
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		if ($_POST["action"] == "pull"){
			
			// Prepare a select statement
			$sql = "SELECT ClientID, FullName, PhoneNumber, Street, City, State, ZIP, Balance FROM clients WHERE ClientID = ?";
			
			if($stmt = mysqli_prepare($acclink, $sql)){ 
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "i", $param_ClientID);

				// Set parameters
				$param_ClientID = $_POST["ClientID"];           

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					mysqli_stmt_store_result($stmt);
					if(mysqli_stmt_num_rows($stmt) == 1){
						mysqli_stmt_bind_result($stmt, $ClientID, $FullName, $PhoneNumber, $Street, $City, $State, $ZIP, $Balance);
						if (!mysqli_stmt_fetch($stmt)){	
							echo "Oops! Something went wrong. Please try again later.";			
						}
					}
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
			
				mysqli_stmt_close($stmt);
				mysqli_close($acclink);
			}
		}

		if ($_POST["action"] == "update"){

			echo $FullName . $PhoneNumber . $Street . $City . $State . $ZIP . $Balance . $ClientID;

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
			
			// Check if nZIP is empty
			if(empty(trim($_POST["Balance"]))){
				$Balance_err = "Please enter the Balance.";
			} else{
				$Balance = trim($_POST["Balance"]);
			}
			
			if(empty($_POST["ClientID"])){
				echo "Didn't receive ClientID, please go back and try again";
			} else {
				$ClientID = $_POST["ClientID"];
			}

			// Validate entries are in
			if(empty($FullName_err) && empty($PhoneNumber_err) && empty($Street_err) && empty($City_err) && empty($State_err) && empty($ZIP_err) && empty($Balance_err)){
				// Prepare a select statement
				$sql = "UPDATE clients SET FullName = ?, PhoneNumber = ?, Street = ?, City = ?, State = ?, ZIP = ?, Balance = ? WHERE ClientID = ?";
				
				if($stmt = mysqli_prepare($acclink, $sql)){ //This is the line that gives me the error
					// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "sssssiii", $param_FullName, $param_PhoneNumber, $param_Street, $param_City, $param_State, $param_ZIP, $param_Balance, $param_ClientID);
				

					// Set parameters
					$param_FullName = $FullName;
					$param_PhoneNumber = $PhoneNumber;
					$param_Street = $Street;
					$param_City = $City;
					$param_State = $State;
					$param_ZIP = $ZIP;
					$param_Balance = $Balance;   
					$param_ClientID = $ClientID;   

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
				mysqli_close($acclink);
			} 
		}
    }
    


?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
			<title>Edit Client</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
            <style type="text/css">body{ font: 14px sans-serif; text-align: center; }</style>
		</head>
	<body>
        <div class="row">
			<div class="column edge"></div>
			<div class="column middle"> 
			
			<div class="" >

				<h2>Edit Client</h2>
				<p>Please fill in client details</p>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

				<input type="hidden" name="action" value="update">
				<input type="hidden" name="ClientID" value="<?php echo $ClientID; ?>">

					<div class="form-group <?php echo (!empty($FullName_err)) ? 'has-error' : ''; ?>">
						<label>Full Name</label>
						<input type="text" name="FullName" class="form-control" value="<?php echo $FullName; ?>">
						<span class="help-block"><?php echo $FullName_err; ?></span>
					</div> 

					<div class="form-group <?php echo (!empty($PhoneNumber_err)) ? 'has-error' : ''; ?>">
						<label>Phone Number</label>
						<input type="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="PhoneNumber" class="form-control" value="<?php echo $PhoneNumber; ?>">
						<span class="help-block"><?php echo $PhoneNumber_err; ?></span>
					</div>
					
					<div class="form-group <?php echo (!empty($Street_err)) ? 'has-error' : ''; ?>">
						<label>Street</label>
						<input type="text" name="Street" class="form-control" value="<?php echo $Street; ?>">
						<span class="help-block"><?php echo $Street_err; ?></span>
					</div>

					<div class="form-group <?php echo (!empty($City_err)) ? 'has-error' : ''; ?>">
						<label>City</label>
						<input type="text" name="City" class="form-control" value="<?php echo $City; ?>">
						<span class="help-block"><?php echo $City_err; ?></span>
					</div>

					<div class="form-group <?php echo (!empty($State_err)) ? 'has-error' : ''; ?>">
						<label>State</label>
						<input type="text" name="State" class="form-control" value="<?php echo $State; ?>">
						<span class="help-block"><?php echo $State_err; ?></span>
					</div>

					<div class="form-group <?php echo (!empty($ZIP_err)) ? 'has-error' : ''; ?>">
						<label>ZIP</label>
						<input type="text" name="ZIP" class="form-control" value="<?php echo $ZIP; ?>">
						<span class="help-block"><?php echo $ZIP_err; ?></span>
					</div>

					<div class="form-group <?php echo (!empty($Balance_err)) ? 'has-error' : ''; ?>">
						<label>Balance</label>
						<input type="text" name="Balance" class="form-control" value="<?php echo $Balance; ?>">
						<span class="help-block"><?php echo $Balance_err; ?></span>
					</div>

					<div class="form-group">
						<input type="submit" class="btn btn-primary btn-block" value="Submit">
					</div>
				</form>
				<a class="btn btn-default btn-block" href="view.php">Back</a>
				</div>
			</div>
			<div class="column edge"></div>
        </div> 
    </body>
</html>






