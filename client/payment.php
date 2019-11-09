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
	$ClientID = $Payment = 0;
	$ClientID_err = $Payment_err = "";
    
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		// Check if nFullName is empty
		if(empty(trim($_POST["ClientID"]))){
			$ClientID_err = "Please enter the ClientID.";
		} else{
			$ClientID = trim($_POST["ClientID"]);
		}

		// Check if nPhoneNumber is empty
		if(empty(trim($_POST["Payment"]))){
			$Payment_err = "Please enter the Payment amount.";
		} else{
			$Payment = trim($_POST["Payment"]);
        }
        
        
		// Validate entries are in
		if(empty($ClientID_err) && empty($Payment_err)){
			// Prepare a select statement
			$sql = "UPDATE clients SET Balance = Balance + ? WHERE ClientID = ?";
            
			if($stmt = mysqli_prepare($acclink, $sql)){ //This is the line that gives me the error
				// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "di", $param_Payment, $param_ClientID);
			
				// Set parameters
                $param_ClientID = $ClientID;
                $param_Payment = $Payment;  

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					echo "Successfully saved the record." . $ClientID . " " . $Payment;
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
			<title>Add Client</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
			<style type="text/css">body{ font: 14px sans-serif; }</style>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
        <div class="row">
			<div class="column edge"></div>
			<div class="column middle"> 
			
				<div class="wrapper" >

					<h2>Add Payment</h2>
					<p>Please fill in Payment details</p>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

					<div class="form-group <?php echo (!empty($Client_err)) ? 'has-error' : ''; ?>">
							<label>Client</label>
							<select name="ClientID" class="form-control">
								<?php
									 require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
									 $sql = "SELECT ClientID, FullName FROM clients";
									 if($stmt = mysqli_prepare($acclink, $sql)){
										 if(mysqli_stmt_execute($stmt)){
											 mysqli_stmt_store_result($stmt);
											 if(mysqli_stmt_num_rows($stmt) > 1){
												 mysqli_stmt_bind_result($stmt, $ClientID, $FullName);
												
												 echo "\r\n";

												 while (mysqli_stmt_fetch($stmt)){
													echo '								<option value=' . $ClientID . '> (' . $ClientID . ') ' . $FullName . '</option>';
													 echo "\r\n";
												 }

												 echo "\r\n";
											 }
										 }
									 }
									 mysqli_stmt_close($stmt);
									 mysqli_close($acclink); 
								?>
							</select>
							<span class="help-block"><?php echo $ClientID_err; ?></span>
						</div>

						<div class="form-group <?php echo (!empty($Payment_err)) ? 'has-error' : ''; ?>">
							<label>Payment Amount</label>
							<input type="number" step="any" min=0 name="Payment" class="form-control">
							<span class="help-block"><?php echo $Payment_err; ?></span>
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






