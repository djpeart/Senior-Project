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
    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 

    // Define variables and initialize with empty values
	$Name = $Price = $Client = $StartDate = $BillDueBy = "";
	$Name_err = $Price_err = $Client_err = $StartDate_err = $BillDueBy_err = "";
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		// Check if nName is empty
		if(empty(trim($_POST["Name"]))){
			$Name_err = "Please the name.";
		} else{
			$Name = trim($_POST["Name"]);
		}

		// Check if nPrice is empty
		if(empty(trim($_POST["Price"]))){
			$Price_err = "Please enter the phone number.";
		} else{
			$Price = trim($_POST["Price"]);
        }
        
        // Check if nClient is empty
		if(empty(trim($_POST["Client"]))){
			$Client_err = "Please enter the Client address.";
		} else{
			$Client = trim($_POST["Client"]);
        }
        
        // Check if nStartDate is empty
		if(empty(trim($_POST["StartDate"]))){
			$StartDate_err = "Please enter the StartDate.";
		} else{
			$StartDate = trim($_POST["StartDate"]);
        }
        
        // Check if nBillDueBy is empty
		if(empty(trim($_POST["BillDueBy"]))){
			$BillDueBy_err = "Please enter the BillDueBy.";
		} else{
			$BillDueBy = trim($_POST["BillDueBy"]);
        }
                
		// Validate entries are in
		if(empty($Name_err) && empty($Price_err) && empty($Client_err) && empty($StartDate_err) && empty($BillDueBy_err)){
			// Prepare a select statement
			$sql = "INSERT INTO assets (Name, Price, Client, StartDate, BillDueBy) VALUES (?, ?, ?, ?, ?)";
            
			if($stmt = mysqli_prepare($acclink, $sql)){ 
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "siiss", $param_formName, $param_formPrice, $param_formClient, $param_formStartDate, $param_formBillDueBy);

				// Set parameters
                $param_formName = $Name;
                $param_formPrice = $Price;
                $param_formClient = $Client;
                $param_formStartDate = $StartDate;
                $param_formBillDueBy = $BillDueBy;             

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
                    echo "Successfully saved the record.";
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
			}
			
			// Close statement
			mysqli_stmt_close($stmt);
		} 

		// Close connection
		mysqli_close($acclink);
		header("location: /account/login.php");
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

					<h2>Add Asset</h2>
					<p>Please fill in asset details</p>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

						<div class="form-group <?php echo (!empty($Name_err)) ? 'has-error' : ''; ?>">
							<label>Name</label>
							<input type="text" name="Name" class="form-control" value="<?php echo $Name; ?>">
							<span class="help-block"><?php echo $Name_err; ?></span>
						</div> 

						<div class="form-group <?php echo (!empty($Price_err)) ? 'has-error' : ''; ?>">
							<label>Price Per Month</label>
							<input type="text" name="Price" class="form-control">
							<span class="help-block"><?php echo $Price_err; ?></span>
						</div>
						
						<div class="form-group <?php echo (!empty($Client_err)) ? 'has-error' : ''; ?>">
							<label>ClientID</label>
							<input type="text" name="Client" class="form-control">
							<span class="help-block"><?php echo $Client_err; ?></span>
						</div>

						<div class="form-group <?php echo (!empty($StartDate_err)) ? 'has-error' : ''; ?>">
							<label>StartDate</label>
							<input type="text" name="StartDate" class="form-control">
							<span class="help-block"><?php echo $StartDate_err; ?></span>
						</div>

						<div class="form-group <?php echo (!empty($BillDueBy_err)) ? 'has-error' : ''; ?>">
							<label>BillDueBy</label>
							<input type="text" name="BillDueBy" class="form-control">
							<span class="help-block"><?php echo $BillDueBy_err; ?></span>
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






