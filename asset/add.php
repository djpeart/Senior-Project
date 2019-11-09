<?php  ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }
    
	requirePermissionLevel(2);

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
		if($_POST["Client"] > 0) {
			$Client = $_POST["Client"];
		} else {
			$Client = NULL;
		}   
        
        // Check if nStartDate is empty
		if(empty(trim($_POST["StartDate"]))){
			if (isset($Client)) {
				$StartDate_err = "Please enter the StartDate.";
			} else {
				$StartDate = NULL;
			}
		} else{
			$StartDate = trim($_POST["StartDate"]);
        }
        
        // Check if nBillDueBy is empty
		if(empty(trim($_POST["BillDueBy"]))){
			if (isset($Client)) {
				$BillDueBy_err = "Please enter the BillDueBy.";
			} else {
				$BillDueBy = NULL;
			}
		} else{
			$BillDueBy = trim($_POST["BillDueBy"]);
        }
                
		// Validate entries are in
		if(empty($Name_err) && empty($Price_err) && empty($Client_err) && empty($StartDate_err) && empty($BillDueBy_err)){
			// Prepare a select statement
			$sql = "INSERT INTO assets (Name, Price, Client, StartDate, BillDueBy) VALUES (?, ?, ?, ?, ?)";
            
			if($stmt = mysqli_prepare($acclink, $sql)){ 
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "sdiss", $param_Name, $param_Price, $param_Client, $param_StartDate, $param_BillDueBy);

				// Set parameters
                $param_Name = $Name;
                $param_Price = $Price;
                $param_Client = $Client;
                $param_StartDate = $StartDate;
                $param_BillDueBy = $BillDueBy;             

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
    }
    

?>

<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
			<title>Add Asset</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		</head>
	<body>
        <div class="container">

			<div class="text-center">
				<h2>Add Asset</h2>
				<p>Please fill in asset details</p>
			</div>
			
			<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

				<div class="form-group <?php echo (!empty($Name_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">Name</label>
					<div class="col-sm-10">
						<input type="text" name="Name" class="form-control" value="<?php echo $Name; ?>">
						<span class="help-block"><?php echo $Name_err; ?></span>
					</div>
				</div> 

				<div class="form-group <?php echo (!empty($Price_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">Price Per Month</label>
					<div class="col-sm-10">
						<input type="number" step="any" min=0 name="Price" class="form-control">
						<span class="help-block"><?php echo $Price_err; ?></span>
					</div>
				</div>
				
				<div class="form-group <?php echo (!empty($Client_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">Client</label>
					<div class="col-sm-10">
						<select name="Client" class="form-control">
							<option value=-1>None</option>
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
						<span class="help-block"><?php echo $Client_err; ?></span>
					</div>
				</div>

				<div class="form-group <?php echo (!empty($StartDate_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">StartDate</label>
					<div class="col-sm-10">
						<input type="date" name="StartDate" class="form-control">
						<span class="help-block"><?php echo $StartDate_err; ?></span>
					</div>
				</div>

				<div class="form-group <?php echo (!empty($BillDueBy_err)) ? 'has-error' : ''; ?>">
					<label class="control-label col-sm-2">BillDueBy</label>
					<div class="col-sm-10">
						<input type="date" name="BillDueBy" class="form-control">
						<span class="help-block"><?php echo $BillDueBy_err; ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-2"></label>
					<div class="col-sm-10">
						<input type="submit" class="btn btn-primary" value="Submit">
						<a class="btn btn-default" href="view.php">Back</a>
					</div>
				</div>
			</form>
        </div> 
    </body>
</html>






