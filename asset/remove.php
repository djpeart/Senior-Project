<?php  ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }
    
	
    // Include config file
	require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
	
	include $_SERVER['DOCUMENT_ROOT'] . '/alerts.php';

	requirePermissionLevel(2);

	if($_SERVER["REQUEST_METHOD"] == "GET"){

		if(empty($_GET["id"])){
			alert("alert-danger","Error!", "Did not receive AssetID.");
		} else{
			$AssetID = trim($_GET["id"]);
		}

		$sql = "DELETE from assets where AssetID = ?";
		
		if($stmt = mysqli_prepare($acclink, $sql)){ 
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "i", $param_AssetID);

			// Set parameters
			$param_AssetID = $AssetID;           

			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				header("location: /asset/view.php");
			} else{
				alert("alert-warning","Error!", "An error has occured and your change has not been saved.");
			}
		}
			
		// Close statement
		mysqli_stmt_close($stmt);

		// Close connection
		mysqli_close($acclink);

	}

?>
