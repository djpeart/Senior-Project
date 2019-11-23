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

		if( isset($_GET["FullName"]) && isset($_GET["PhoneNumber"]) && isset($_GET["Street"]) && isset($_GET["City"]) && isset($_GET["State"]) && isset($_GET["ZIP"])){

			if ($_GET["action"] == "add") {

				$sql = "INSERT INTO clients (FullName, PhoneNumber, Street, City, State, ZIP) VALUES (?, ?, ?, ?, ?, ?)";
            
				if($stmt = mysqli_prepare($acclink, $sql)){ //This is the line that gives me the error
					// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($stmt, "sssssi", $param_FullName, $param_PhoneNumber, $param_Street, $param_City, $param_State, $param_ZIP);
				

					// Set parameters
					$param_FullName = $_GET["FullName"];
					$param_PhoneNumber = $_GET["PhoneNumber"];
					$param_Street = $_GET["Street"];
					$param_City = $_GET["City"];
					$param_State = $_GET["State"];
					$param_ZIP = $_GET["ZIP"];      

					

					if(mysqli_stmt_execute($stmt)){
						header("location: /client");
					} else{
						echo "Oops! Something went wrong. Please try again later.";
					}
				}
				
				// Close statement
				mysqli_stmt_close($stmt);
			}
			
			if ($_GET["action"] == "edit" && isset($_GET["cid"])) {

				$sql = "UPDATE clients SET FullName = ?, PhoneNumber = ?, Street = ?, City = ?, State = ?, ZIP = ? WHERE ClientID = ?";
				
				if($stmt = mysqli_prepare($acclink, $sql)){
					// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($stmt, "sssssii", $param_FullName, $param_PhoneNumber, $param_Street, $param_City, $param_State, $param_ZIP, $param_ClientID);
				

					// Set parameters
					$param_FullName = $_GET["FullName"];
					$param_PhoneNumber = $_GET["PhoneNumber"];
					$param_Street = $_GET["Street"];
					$param_City = $_GET["City"];
					$param_State = $_GET["State"];
					$param_ZIP = $_GET["ZIP"];   
					$param_ClientID = $_GET["cid"];   

					// Attempt to execute the prepared statement
					if(mysqli_stmt_execute($stmt)){
						header("location: /client/view.php?id=" . $_GET["cid"]);
					} else{
						echo "Oops! Something went wrong. Please try again later.";
					}
				}
				
				// Close statement
				mysqli_stmt_close($stmt);

			}

		} 

		if ($_GET["action"] == "delete" && isset($_GET["cid"])) {

			$sql = "DELETE from clients where ClientID = ?";
	
			if($stmt = mysqli_prepare($acclink, $sql)){ 
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "i", $param_ClientID);

				// Set parameters
				$param_ClientID = $_GET["cid"];           

				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					header("location: /client");
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
			}
				
			// Close statement
			mysqli_stmt_close($stmt);
		}

		mysqli_close($acclink);
    }
    


?>
