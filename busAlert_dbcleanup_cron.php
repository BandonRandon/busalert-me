<?php
/* 
Package: busAlert.me 
File: busAlert_dbcleanup_cron.php 
(c) 2011 Brooke Dukes All Rights Reserved

This file will remove already sent database entries that are more than a few days old.
It is recommended that you set this as a cron.
You must edit config.php before this file will work
*/
//get the config settings
require("busAlert_config.php");

//get the key from the cron. You may need to run "php /busAlert_dbcleanup_cron.php my_key"
$key = $argv[1]; //comment out this line to use URL parsing

//if you are not going to be running a cron and just running this manually or use another method comment out the line below
//$key=$_REQUEST['k']; //get they key from the URL

//check to make sure the key is set and matches
if((!isset($key)) || ($key != $cleanup_key)){
	 echo "Invalid Key"; die();
}

//the keys match delete the records
elseif($key == $cleanup_key){
        //delete old records the database. 
	$delete_alert_qry = "DELETE FROM " .$table_name ."  WHERE `alert_status` = 1 AND DATE_SUB(CURDATE(),INTERVAL 3 DAY) >= date ";
	$result = mysql_query($delete_alert_qry) or die(mysql_error());
	echo "sucess";
}
//we don't know what happend, everything exploded.
else{ 
	echo "unknown error"; die();
}

?>
