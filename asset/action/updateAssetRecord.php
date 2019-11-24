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

    // Include config file
    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "GET"){

		              
		// Validate entries are in
		if(isset($_GET["Name"]) && isset($_GET["Price"])){
			
			if ($_GET["action"] == "add") {

				$sql = "INSERT INTO assets (Name, Price) VALUES (?, ?)";
				
				if($stmt = mysqli_prepare($acclink, $sql)){ 
					// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($stmt, "sd", $param_Name, $param_Price);

					$param_Name = $_GET["Name"];
					$param_Price = $_GET["Price"];          

					// Attempt to execute the prepared statement
					if(mysqli_stmt_execute($stmt)){
						header("location: /asset");
					} else{
						echo "Oops! Something went wrong. Please try again later.";
					}
				}

				mysqli_stmt_close($stmt);
			}

			if ($_GET["action"] == "edit" && isset($_GET["aid"])) {
				$sql = "UPDATE assets SET Name = ?, Price = ? WHERE AssetID = ?";
				
				if($stmt = mysqli_prepare($acclink, $sql)){ 
					// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($stmt, "sdi", $param_Name, $param_Price, $param_AssetID);

					$param_Name = $_GET["Name"];
					$param_Price = $_GET["Price"];
					$param_AssetID = $_GET["aid"];        

					// Attempt to execute the prepared statement
					if(mysqli_stmt_execute($stmt)){
						header("location: /asset");
					} else{
						echo "Oops! Something went wrong. Please try again later.";
					}
				}

				mysqli_stmt_close($stmt);
			}

		} 

		if ($_GET["action"] == "delete" && isset($_GET["aid"])) {

			$sql = "DELETE FROM assets WHERE AssetID = ?";

			if($stmt = mysqli_prepare($acclink, $sql)){ 

				mysqli_stmt_bind_param($stmt, "i", $param_AssetID);

				$param_AssetID = $_GET["aid"];   

				if(mysqli_stmt_execute($stmt)){
					header("location: /asset");
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
			}

			mysqli_stmt_close($stmt);
		}

		// Close connection
		mysqli_close($acclink);
    }
    


?>
