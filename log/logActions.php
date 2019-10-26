<?php 
    function logprint($message) {
        $log = fopen( 'log-' . date("Y-m-d") . '.txt', "a");

        if (isset($_SESSION["username"])) {
            $user = $_SESSION["username"];
        }
        else {
            $user = "";
        }

        $string = '[' . date("h:i:sa") . '] [' . $user . '] '. $message;

        fwrite($log, $string);
        fclose($log);
    }
?>

