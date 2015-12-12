<?php
/* 
Package: busAlert.me 
File: busAlert_call.php 
(c) 2011 Brooke Dukes All Rights Reserved

This file is  called when the user request to be alerted via phone. 
busAlert_cron.php sends the variables and redirects to this script. 
You must edit config.php before this file will work
*/

    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Say> <?php echo urldecode($_REQUEST['message']); ?></Say>
    <Pause length="1"/>
    <Gather>
        <Say>
            To repeat this message press a number key followed by the pound sign
        </Say>
    </Gather> 
   <Pause length="5"/>
   <Say>Goodbye</Say>
   <Hangup/>
</Response>
