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
    
    $sql = "call bill()";
    if($stmt = mysqli_prepare($acclink, $sql)){ 
        if(mysqli_stmt_execute($stmt)){
        
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        
    }

    mysqli_stmt_close($stmt);
    mysqli_close($acclink);
    
    if (isset($_GET["id"]))
        header("location: /client/view.php?id=" . $_GET["id"]);
    else
        header("location: /client");
?>