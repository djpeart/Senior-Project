<?php 
    function alert($type, $title, $message) {
        echo "<br><div class=\"container\">"
            . "<div class=\"alert alert-dismissible fade in " . $type . "\">"
            . "		<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"
            . "		<strong>" . $title . "</strong> " . $message
            . "</div></div>";
    }
?>