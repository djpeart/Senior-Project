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
	$Name = $Price = $Client = $StartDate = $BillDueBy = $AssetID = "";
	$Name_err = $Price_err = $Client_err = $StartDate_err = $BillDueBy_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		if ($_POST["action"] == "pull"){
			
			// Prepare a select statement
			$sql = "SELECT AssetID, Name, Price, Client, StartDate, BillDueBy FROM assets WHERE AssetID = ?";
			
			if($stmt = mysqli_prepare($acclink, $sql)){ 
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "i", $param_AssetID);

				// Set parameters
				$param_AssetID = $_POST["AssetID"];           

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					mysqli_stmt_store_result($stmt);
					if(mysqli_stmt_num_rows($stmt) == 1){
						mysqli_stmt_bind_result($stmt, $AssetID, $Name, $Price, $Client, $StartDate, $BillDueBy);
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
        
		if(empty($_POST["AssetID"])){
			echo "Didn't receive AssetID, please go back and try again";
		} else {
			$AssetID = $_POST["AssetID"];
		}

		// Validate entries are in
		if(empty($Name_err) && empty($Price_err)){
				// Prepare a select statement
				$sql = "UPDATE assets SET Name = ?, Price = ? WHERE AssetID = ?";
				
				if($stmt = mysqli_prepare($acclink, $sql)){ //This is the line that gives me the error
					// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($stmt, "sdi", $param_Name, $param_Price, $param_AssetID);
			
					// Set parameters
					$param_Name = $Name;
					$param_Price = $Price; 
					$param_AssetID = $AssetID;

					// Attempt to execute the prepared statement
					if(mysqli_stmt_execute($stmt)){
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

				<h2>Edit Asset</h2>
				<p>Please fill in asset details</p>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

					<input type="hidden" name="action" value="update">
					<input type="hidden" name="AssetID" value="<?php echo $AssetID; ?>">

						<div class="form-group <?php echo (!empty($Name_err)) ? 'has-error' : ''; ?>">
							<label>Name</label>
							<input type="text" name="Name" class="form-control" value="<?php echo $Name; ?>">
							<span class="help-block"><?php echo $Name_err; ?></span>
						</div> 

						<div class="form-group <?php echo (!empty($Price_err)) ? 'has-error' : ''; ?>">
							<label>Price Per Month</label>
							<input type="number" step="any" min=0 name="Price" class="form-control" value="<?php echo $Price; ?>">
							<span class="help-block"><?php echo $Price_err; ?></span>
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






