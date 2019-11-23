<?php

    session_start();

    // Check if the user is already logged in, if yes then redirect him to welcome page
    include $_SERVER['DOCUMENT_ROOT'] . '/account/accountActions.php';
    if( !isLoggedIn() ){
        header("location: /account/login.php");
        exit;
    }

    requirePermissionLevel(2);

    // Include config file
    require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/accounting.php'; 
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){

        if ($_GET["action"] == "add") {
		
            $sql = "INSERT INTO payments(ClientID, Amount, Date) VALUES (?,?,?)";
            
            if($stmt = mysqli_prepare($acclink, $sql)){ 
                mysqli_stmt_bind_param($stmt, "iis", $param_ClientID, $param_Amount, $param_Date);

                $param_ClientID = $_GET["cid"];
                $param_Amount = $_GET["Amount"];
                $param_Date = $_GET["Date"];       

                if(mysqli_stmt_execute($stmt)){
                
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
                
            }

            $sql = "UPDATE clients SET balance = balance + ? WHERE ClientID = ?";
            
            if($stmt = mysqli_prepare($acclink, $sql)){ 
                mysqli_stmt_bind_param($stmt, "ii", $param_Amount, $param_ClientID);

                $param_Amount = $_GET["Amount"];
                $param_ClientID = $_GET["cid"];
                
                if(mysqli_stmt_execute($stmt)){
                
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
                
            }
            
            $sql = "call bill()";
            
            if($stmt = mysqli_prepare($acclink, $sql)){ 
                if(mysqli_stmt_execute($stmt)){
                
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
                
            }
        }

        if ($_GET["action"] == "delete") {
            $sql = "DELETE FROM payments WHERE PaymentID = ?";
            
            if($stmt = mysqli_prepare($acclink, $sql)){ 
                mysqli_stmt_bind_param($stmt, "i", $param_PaymentID);

                $param_PaymentID = $_GET["pid"];  

                if(mysqli_stmt_execute($stmt)){
                
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
            }
        }
		

		mysqli_close($acclink);
    }

    header("location: /client/view.php?id=" . $_GET["cid"]);
?>