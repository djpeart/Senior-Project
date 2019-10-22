<?php 
    function isLoggedIn() {
        return (isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == true);
    }

    function updatePermissions() {   
        require_once $_SERVER['DOCUMENT_ROOT'] . '/databases/login.php'; 
        $sql = "SELECT permlevel FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($loginlink, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){ 
                    mysqli_stmt_bind_result($stmt, $permlevel);
                    if(mysqli_stmt_fetch($stmt)){
                        $_SESSION["permlevel"] = $permlevel;
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($loginlink);
    }

?>