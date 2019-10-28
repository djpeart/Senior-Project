<?php ini_set('display_errors',1); error_reporting(E_ALL);
    session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
	if( !isLoggedIn() ){
		header("location: /account/login.php");
		exit;
    }
    
    requirePermissionLevel(1);

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if ($_POST["button"] == "Edit") {

            /*$url = '/client/edit.php';
            $data = array('ClientID' => $_POST["ClientID"]);

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                )
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if ($result === FALSE) {  
                var_dump($result);
            }*/

            $url = '/client/edit.php';

            //The data you want to send via POST
            $fields = [
                'ClientID' => $_POST["ClientID"]
            ];

            //url-ify the data for the POST
            $fields_string = http_build_query($fields);

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

            //So that curl_exec returns the contents of the cURL; rather than echoing it
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

            //execute post
            $result = curl_exec($ch);
            echo $result;
            
        }
        
    
    }
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
                <?php 
                                        
                    // Include config file
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
                    $sql = "SELECT ClientID, FullName, PhoneNumber, Street, City, State, ZIP, Balance FROM clients";
                    if($stmt = mysqli_prepare($acclink, $sql)){
                        if(mysqli_stmt_execute($stmt)){
                            mysqli_stmt_store_result($stmt);
                            if(mysqli_stmt_num_rows($stmt) > 0){
                                mysqli_stmt_bind_result($stmt, $ClientID, $FullName, $PhoneNumber, $Street, $City, $State, $ZIP, $Balance);

                                echo "<form action=\"/client/edit.php\" method=\"POST\"> \r\n"
                                    . "<input type=\"hidden\" name=\"action\" value=\"pull\">\r\n";

                                echo "<br><br><pre>" . "<br><b>"
                                    . str_pad("ClientID",10, " ", STR_PAD_BOTH)
                                    . str_pad("Name", 32)
                                    . str_pad("Phone Number", 16)
                                    . str_pad("Street", 32)
                                    . str_pad("City", 16)
                                    . str_pad("State", 8)
                                    . str_pad("ZIP", 8)
                                    . str_pad("Balance", 7);
                                echo "</b>"; 
                                
                                echo "<div class=\"form-group\">\r\n";
                                while (mysqli_stmt_fetch($stmt)){
                                    echo "<input type=\"radio\" name=\"ClientID\" value=" . $ClientID . ">";
                                    echo str_pad($ClientID,10, " ", STR_PAD_BOTH)
                                        . str_pad($FullName, 32)
                                        . str_pad($PhoneNumber, 16)
                                        . str_pad($Street, 32)
                                        . str_pad($City, 16)
                                        . str_pad($State, 8)
                                        . str_pad($ZIP, 8)
                                        . str_pad($Balance, 7);
                                    echo "<br>\r\n";
                                }
                                echo "</div>"
                                    . "</pre>";

                                echo "<div class=\"form-group\" style=\"text-align: left\">\r\n"
                                    . "<input type=\"submit\" class=\"btn btn-primary\" name=\"button\" value=\"Edit\">\r\n"
                                    . "</div>";
                                    
                                echo "</form>";
                                
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