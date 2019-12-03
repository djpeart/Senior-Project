<?php  ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	include $_SERVER['DOCUMENT_ROOT'] . '/alerts.php';
	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }

	if ($_SESSION["permlevel"] < 3) {
        header("location: /account/users.php");
    }

    // Include config file
    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/login.php'; 

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "GET"){

		// Validate entries are in
		if(isset($_GET["id"])){
			
			if ($_GET["action"] == "edit" && isset($_GET["permlevel"])) {
				$sql = "UPDATE users SET permlevel = ? WHERE id = ?";
				
				if($stmt = mysqli_prepare($loginlink, $sql)){ 
					// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($stmt, "ii", $param_permlevel, $param_id);

					$param_permlevel = $_GET["permlevel"];
					$param_id = $_GET["id"];        

					// Attempt to execute the prepared statement
					if(mysqli_stmt_execute($stmt)){
						header("location: /account/users.php");
					} else{
						echo "Oops! Something went wrong. Please try again later.";
					}
				}

				mysqli_stmt_close($stmt);
			}


            if ($_GET["action"] == "delete") {

                $sql = "DELETE FROM users WHERE id = ?";

                if($stmt = mysqli_prepare($loginlink, $sql)){ 

                    mysqli_stmt_bind_param($stmt, "i", $param_id);

                    $param_id = $_GET["id"];   

                    if(mysqli_stmt_execute($stmt)){
                        header("location: /account/users.php");
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }

                mysqli_stmt_close($stmt);
            }
        
        }

		// Close connection
		mysqli_close($loginlink);
    }
?>
