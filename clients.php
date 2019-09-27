<?php ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }
    
    updatePermissions();

	// Include config file
    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/login.php';

    require_once "databases/accounting.php"; 
        $sql = "SELECT ClientID, FullName, PhoneNumber, Street, City, State, ZIP, Balance FROM clients";
        if($stmt = mysqli_prepare($acclink, $sql)){
            //mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) > 1){
                    mysqli_stmt_bind_result($stmt, $ClientID, $FullName, $PhoneNumber, $Street, $City, $State, $ZIP, $Balance);

                    echo "<pre>" . "<br>"
                        . str_pad("ClientID",10)
                        . str_pad("Name", 32)
                        . str_pad("Phone Number", 16)
                        . str_pad("Street", 32)
                        . str_pad("City", 16)
                        . str_pad("State", 6)
                        . str_pad("ZIP", 6)
                        . str_pad("Balance", 7)
                        . "<br>"; 
                    
                    while (mysqli_stmt_fetch($stmt)){
                        echo "<br>" 
                        . str_pad($ClientID,10)
                        . str_pad($FullName, 32)
                        . str_pad($PhoneNumber, 16)
                        . str_pad($Street, 32)
                        . str_pad($City, 16)
                        . str_pad($State, 6)
                        . str_pad($ZIP, 6)
                        . str_pad($Balance, 7);
                    }

                    echo "</pre>";


                }
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($acclink);
?>