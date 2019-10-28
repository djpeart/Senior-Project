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
	$AssetID = "";
	$AssetID_err= "";
    
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		// Check if assetid is empty
		if(empty(trim($_POST["AssetID"]))){
			$AssetID_err = "Please enter the AssetID.";
		} else{
			$AssetID = trim($_POST["AssetID"]);
		}
        
		// Validate entries are in
		if(empty($AssetID_err)){
			// Prepare a select statement
			$sql = "DELETE from assets where Assetid = ?";
            
			if($stmt = mysqli_prepare($acclink, $sql)){ 
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "i", $param_AssetID);

				// Set parameters
                $param_AssetID = $AssetID;           

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
			<title>Remove Asset</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
            <style type="text/css">body{ font: 14px sans-serif; text-align: center; }</style>
		</head>
	<body>
        <div class="row">
			<div class="column edge"></div>
			<div class="column middle"> 
			
				<div class="" >

					<h2>Delete Asset</h2>
					<p>Please fill in Asset details</p>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

						<div class="form-group <?php echo (!empty($FullName_err)) ? 'has-error' : ''; ?>">
							<label>AssetID</label>
							<select name="AssetID" class="form-control">
								<?php
									 require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
									 $sql = "SELECT AssetID, Name FROM assets";
									 if($stmt = mysqli_prepare($acclink, $sql)){
										 if(mysqli_stmt_execute($stmt)){
											 mysqli_stmt_store_result($stmt);
											 if(mysqli_stmt_num_rows($stmt) > 1){
												 mysqli_stmt_bind_result($stmt, $AssetID, $Name);
												
												 echo "\r\n";

												 while (mysqli_stmt_fetch($stmt)){
													 echo '								<option value=' . $AssetID . '> (' . $AssetID . ') ' . $Name . '</option>';
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
							<span class="help-block"><?php echo $AssetID_err; ?></span>
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