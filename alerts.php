<?php 
    function alert($type, $title, $message) {
        echo "<div class=\"container\" style=\"position: fixed; text-align: center\">"
            . "     <div class=\"row\">"
            . "         <div class=\"col-xs-6\">"
            . "             <div class=\"alert alert-dismissible fade in " . $type . "\">"
            . "		            <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"
            . "		            <strong>" . $title . "</strong> " . $message
            . "             </div>"
            . "         </div>"
            . "     </div>"
            . "</div>";
    }
?>