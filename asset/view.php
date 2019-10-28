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
            <div class="column edge"></div>
            <div class="column middle"> 
                <?php 
                    if ($_SESSION["permlevel"] < 1) {
                        print "<pre class=\"alert-warning\"><h1> You do not have permission to read data yet!</h1></pre>";
                        exit;
                    }
                    
                    // Include config file
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
                    $sql = "SELECT AssetID, Name, Price, Client, StartDate, BillDueBy FROM assets";
                    if($stmt = mysqli_prepare($acclink, $sql)){
                        if(mysqli_stmt_execute($stmt)){
                            mysqli_stmt_store_result($stmt);
                            if(mysqli_stmt_num_rows($stmt) > 0){
                                mysqli_stmt_bind_result($stmt, $AssetID, $Name, $Price, $Client, $StartDate, $BillDueBy);
                                
                                echo "<br><br><pre>" . "<br><p><b>"
                                    . str_pad("AssetID",10)
                                    . str_pad("Name", 32)
                                    . str_pad("Price", 7)
                                    . str_pad("Client", 10)
                                    . str_pad("StartDate", 16)
                                    . str_pad("BillDueBy", 14);
                                echo "</b></p>"; 
                                
                                while (mysqli_stmt_fetch($stmt)){
                                    echo "<p>" ;
                                    echo str_pad($AssetID,10)
                                        . str_pad($Name, 32)
                                        . str_pad($Price, 7)
                                        . str_pad($Client, 10)
                                        . str_pad($StartDate, 16)
                                        . str_pad($BillDueBy, 14);
                                    echo "</p>";
                                }

                                echo "</pre>";
                            }
                        }
                    }
                    mysqli_stmt_close($stmt);
                    mysqli_close($acclink);
                ?>

                <a class="btn btn-primary btn-block" href="add.php">Add an asset</a>
                <a class="btn btn-primary btn-block" href="remove.php">Remove an asset</a>
                <a class="btn btn-default btn-block" href="../welcome.php">Back</a>
                
            </div>
            <div class="column edge"></div>
        </div>
	</body>
</html>