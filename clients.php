<?php ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }
    
    updatePermissions();

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
            <div class="column side"></div>
            <div class="column middle" style=> 
                <?php 
                    if ($_SESSION["permlevel"] < 1) {
                        print "<pre class=\"alert-warning\"><h1> You do not have permission to read data yet!</h1></pre>";
                        exit;
                    }
                    
                    // Include config file
                    require_once "databases/accounting.php"; 
                    $sql = "SELECT ClientID, FullName, PhoneNumber, Street, City, State, ZIP, Balance FROM clients";
                    if($stmt = mysqli_prepare($acclink, $sql)){
                        if(mysqli_stmt_execute($stmt)){
                            mysqli_stmt_store_result($stmt);
                            if(mysqli_stmt_num_rows($stmt) > 1){
                                mysqli_stmt_bind_result($stmt, $ClientID, $FullName, $PhoneNumber, $Street, $City, $State, $ZIP, $Balance);

                                echo "<br><br><pre>" . "<br><p><b>"
                                    . str_pad("ClientID",10)
                                    . str_pad("Name", 32)
                                    . str_pad("Phone Number", 16)
                                    . str_pad("Street", 32)
                                    . str_pad("City", 16)
                                    . str_pad("State", 8)
                                    . str_pad("ZIP", 8)
                                    . str_pad("Balance", 7)
                                    . "</b></p>"; 
                                
                                while (mysqli_stmt_fetch($stmt)){
                                    echo "<p>" ;
                                    echo str_pad($ClientID,10)
                                        . str_pad($FullName, 32)
                                        . str_pad($PhoneNumber, 16)
                                        . str_pad($Street, 32)
                                        . str_pad($City, 16)
                                        . str_pad($State, 8)
                                        . str_pad($ZIP, 8)
                                        . str_pad($Balance, 7);
                                    echo "</p>";
                                }

                                echo "</pre>";
                            }
                        }
                    }
                    mysqli_stmt_close($stmt);
                    mysqli_close($acclink);
                ?>

                
            </div>
            <div class="column side"></div>
        </div>
	</body>
</html>