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
		
            $sql = "INSERT INTO assignments(ClientID, AssetID, StartDate, EndDate, Active) VALUES (?,?,?,?,1)";
            
            if($stmt = mysqli_prepare($acclink, $sql)){ 
                mysqli_stmt_bind_param($stmt, "iiss", $param_ClientID, $param_AssetID, $param_StartDate, $param_EndDate);

                $param_ClientID = $_GET["cid"];
                $param_AssetID = $_GET["Asset"];
                $param_StartDate = $_GET["StartDate"];
                $param_EndDate = $_GET["EndDate"];           

                if(mysqli_stmt_execute($stmt)){
                
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
            }
        }
                
        if ($_GET["action"] == "update") {
            $sql = "UPDATE assignments SET StartDate = ?, EndDate = ? WHERE AssignmentID = ?";
            
            if($stmt = mysqli_prepare($acclink, $sql)){ 
                mysqli_stmt_bind_param($stmt, "ssi", $param_StartDate, $param_EndDate, $param_AssignmentID);

                $param_StartDate = $_GET["StartDate"];
                $param_EndDate = $_GET["EndDate"];     
                $param_AssignmentID = $_GET["aid"];      

                if(mysqli_stmt_execute($stmt)){
                
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
            }
        }

        if ($_GET["action"] == "delete") {
            $sql = "DELETE FROM assignments WHERE AssignmentID = ?";
            
            if($stmt = mysqli_prepare($acclink, $sql)){ 
                mysqli_stmt_bind_param($stmt, "s", $param_AssignmentID);

                $param_AssignmentID = $_GET["aid"];  

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