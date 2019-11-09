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
	if($_SERVER["REQUEST_METHOD"] == "GET"){

		// Prepare a select statement
		$sql = "SELECT ClientID, FullName, PhoneNumber, Street, City, State, ZIP, Balance FROM clients WHERE ClientID = ?";
		
		if($stmt = mysqli_prepare($acclink, $sql)){ 
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "i", $param_ClientID);

			// Set parameters
			$param_ClientID = $_GET["id"];           

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
				mysqli_stmt_bind_param($stmt, "sssssidi", $param_FullName, $param_PhoneNumber, $param_Street, $param_City, $param_State, $param_ZIP, $param_Balance, $param_ClientID);
			

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

?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
			<title>Edit Client</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
        <div class="container">

			<div class="text-center">
				<h2>Edit Client</h2>
				<p>Please fill in client details</p>
			</div>
			<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

				<div class="form-group <?php echo (!empty($FullName_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">Full Name</label>
					<div class="col-sm-10">
						<input type="text" name="FullName" class="form-control" value="<?php echo $FullName; ?>">
						<span class="help-block"><?php echo $FullName_err; ?></span>
					</div>
				</div> 

				<div class="form-group <?php echo (!empty($PhoneNumber_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">Phone Number</label>
					<div class="col-sm-10">
						<input type="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="PhoneNumber" class="form-control" value="<?php echo $PhoneNumber; ?>">
						<span class="help-block"><?php echo $PhoneNumber_err; ?></span>
					</div>
				</div>
				
				<div class="form-group <?php echo (!empty($Street_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">Street</label>
					<div class="col-sm-10">
						<input type="text" name="Street" class="form-control" value="<?php echo $Street; ?>">
						<span class="help-block"><?php echo $Street_err; ?></span>
					</div>
				</div>

				<div class="form-group <?php echo (!empty($City_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">City</label>
					<div class="col-sm-10">
						<input type="text" name="City" class="form-control" value="<?php echo $City; ?>">
						<span class="help-block"><?php echo $City_err; ?></span>
					</div>
				</div>

				<div class="form-group <?php echo (!empty($State_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">State</label>
					<div class="col-sm-10">
						<input type="text" name="State" class="form-control" value="<?php echo $State; ?>">
						<span class="help-block"><?php echo $State_err; ?></span>
					</div>
				</div>

				<div class="form-group <?php echo (!empty($ZIP_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">ZIP</label>
					<div class="col-sm-10">
						<input type="text" name="ZIP" class="form-control" value="<?php echo $ZIP; ?>">
						<span class="help-block"><?php echo $ZIP_err; ?></span>
					</div>
				</div>

				<div class="form-group <?php echo (!empty($Balance_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">Balance</label>
					<div class="col-sm-10">
						<input type="number" step="any" name="Balance" class="form-control" value="<?php echo $Balance; ?>">
						<span class="help-block"><?php echo $Balance_err; ?></span>
					</div>
				</div>

				<div class="form-group">
				<label class="control-label col-sm-2"></label>
					<div class="col-sm-10">
						<input type="submit" class="btn btn-primary" value="Update">
						<a class="btn btn-danger" data-toggle="modal" data-target="#myModal">Delete</a>
						<a class="btn btn-default" href="view.php">Back</a>
					</div>
				</div>

				<input type="hidden" name="ClientID" value="<?php echo $ClientID; ?>">

			</form>


			<div class="modal fade" id="myModal" role="dialog">
				<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 class="modal-title"><strong>Are you sure?</strong></h2>
					</div>
					<div class="modal-body">
						<h4>
							This will peremenenantly remove <strong><?php echo $FullName; ?></strong> from the system.
							<br><br>
							<strong>This cannot be undone.</strong>
						</h4>
					</div>
					<div class="modal-footer">
						<a class="btn btn-danger" href="remove.php?id=<?php echo $ClientID; ?>">Delete</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			
			</div>
        </div> 
    </body>
</html>






