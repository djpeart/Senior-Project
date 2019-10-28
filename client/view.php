<?php ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }
    
    requirePermissionLevel(1);

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
                <?php 
                                        
                    // Include config file
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
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
                                    . str_pad("Balance", 7);
                                echo "</b></p>"; 
                                
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

                <a class="btn btn-primary btn-block" href="add.php">Add a client</a>
                <a class="btn btn-primary btn-block" href="remove.php">Remove a client</a>
                <a class="btn btn-default btn-block" href="../welcome.php">Back</a>
                
            </div>
            <div class="column edge"></div>
        </div>
	</body>
</html>