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
			<title>View Client</title>
			<link rel="stylesheet" href="/css/bootstrap.css">
            <style type="text/css">body{ font: 14px sans-serif; text-align: center; }</style>
		</head>
	<body>
        <div class="row">
            <div class="column edge"></div>
            <div class="column middle"> 

                <form action="/client/edit.php" method="POST">
                    <input type="hidden" name="action" value="pull">
                    <?php 
                        
                        echo "<br><br><pre class=\"pre-scrollable\">" . "<br><b>"
                            . str_pad("ClientID",10, " ", STR_PAD_BOTH)
                            . str_pad("Name", 32)
                            . str_pad("Phone Number", 16)
                            . str_pad("Street", 32)
                            . str_pad("City", 16)
                            . str_pad("State", 7)
                            . str_pad("ZIP", 8)
                            . str_pad("Balance", 9)
                            . str_pad("Past Due", 8);
                        echo "</b>\r\n"; 

                        // Include config file
                        require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
                        $sql = "SELECT ClientID, FullName, PhoneNumber, Street, City, State, ZIP, clients.Balance, total FROM clients LEFT JOIN billing on ClientID = client";
                        if($stmt = mysqli_prepare($acclink, $sql)){
                            if(mysqli_stmt_execute($stmt)){
                                mysqli_stmt_store_result($stmt);
                                if(mysqli_stmt_num_rows($stmt) > 0){
                                    mysqli_stmt_bind_result($stmt, $ClientID, $FullName, $PhoneNumber, $Street, $City, $State, $ZIP, $Balance, $total);

                                    echo "<div class=\"form-group\">\r\n";
                                    while (mysqli_stmt_fetch($stmt)){
                                        echo "<input class=\"";
                                        echo ($total > 0) ? "background-color: #f2dede" : "";
                                        echo  "\" type=\"radio\" name=\"ClientID\" value=" . $ClientID . ">";
                                        echo str_pad($ClientID,10, " ", STR_PAD_BOTH)
                                            . str_pad($FullName, 32)
                                            . str_pad($PhoneNumber, 16)
                                            . str_pad($Street, 32)
                                            . str_pad($City, 16)
                                            . str_pad($State, 7)
                                            . str_pad($ZIP, 8)
                                            . str_pad($Balance, 9)
                                            . str_pad($total, 8);
                                        echo "<br>\r\n";
                                    }
                                    echo "</div>";
                                }
                            }
                        }

                        echo "</pre>";

                        mysqli_stmt_close($stmt);
                        mysqli_close($acclink); 
                    ?>

                    <div class="form-group" style="text-align: left">
                        <input type="submit" class="btn btn-primary" name="button" value="Edit">
                    </div>

                </form>

                <a class="btn btn-primary btn-block" href="add.php">Add a client</a>
                <a class="btn btn-primary btn-block" href="remove.php">Remove a client</a>
                <a class="btn btn-primary btn-block" href="payment.php">Make Payment</a>
                <a class="btn btn-default btn-block" href="../welcome.php">Back</a>
                
            </div>
            <div class="column edge"></div>
        </div>
	</body>
</html>