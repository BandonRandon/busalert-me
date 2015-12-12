<?php
/* 
Package: busAlert.me 
File: busAlert_if_called.php 
(c) 2011 Brooke Dukes All Rights Reserved

This is the action to take if the user calls the number directly.
TO DO: allow call in request.
*/

    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Say>Thank you for your intrest in Bus Alert Me. At this time you must text in your request. Please remember to visit us online at bus alert dot m e</Say>
   <Pause length="2"/>
   <Say>Goodbye</Say>
   <Hangup/>
</Response>
