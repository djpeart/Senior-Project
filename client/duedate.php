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

        		
        $sql = "UPDATE clients SET DueDate = ? WHERE ClientID = ?";
        
        if($stmt = mysqli_prepare($acclink, $sql)){ 
            mysqli_stmt_bind_param($stmt, "si", $param_DueDate, $param_ClientID);

            $param_ClientID = $_GET["cid"];
            $param_DueDate = $_GET["Date"];           

            if(mysqli_stmt_execute($stmt)){
            
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
      
        mysqli_close($acclink);
    }

    header("location: /client/edit.php?id=" . $_GET["cid"]);
?>