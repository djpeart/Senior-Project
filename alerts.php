<?php 
    function alert($type, $title, $message) {
        echo "<div class=\"alert alert-dismissible fade in " . $type . "\">"
        . "		<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"
        . "		<strong>" . $title . "</strong> " . $message
        . "</div>";
    }
?>