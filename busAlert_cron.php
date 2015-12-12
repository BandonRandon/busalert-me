<?php 
/*
Package: busAlert.me 
File: busAlert_cron.php 
(c) 2011 Brooke Dukes All Rights Reserved

This is the cron file of BusAlert.me it is the file that alerts the user when the bus is near. 
You may set this to check how ever often you'd like, I'd suggest every min. 
You must edit config.php before this file will work
*/
//get the config settings
require("busAlert_config.php");

//return records that have been added in the last day (this is to prevent people getting notified weeks later if the system was down)
$alert_check_qry = mysql_query("SELECT `sms_from`,`stop_number`, `route_number`, `alert_time`, `alert_after_time`, `alert_type` FROM ".$table_name."  WHERE DATE_SUB(CURDATE(),INTERVAL 1 DAY) <= `date` AND `alert_status` ='0'") or die(mysql_error());

//loop though and see if we have any notifications to send out
while($alert_check_result = mysql_fetch_array($alert_check_qry)) {

//get our stop and route data from the database 
$stop_number = $alert_check_result['stop_number']; //stop number
$route_number = $alert_check_result['route_number']; //route number
$message_to = $alert_check_result['sms_from']; //who to alert

//get the onebus route data from the onebus api
$jsonurl = "http://api.onebusaway.org/api/where/arrivals-and-departures-for-stop/1_$stop_number.json?key=$onebus_api&version=2";
$json = file_get_contents($jsonurl,0,null,null);
$json_output = json_decode($json,true);

//return an array of arivals anddepartures
$json_route_data_array= $json_output[data][entry][arrivalsAndDepartures];
//sort the array


if(is_array($json_route_data_array)){
	foreach($json_route_data_array as $key => $value){
		//if the routenumber has same value as our data and the predicted time is lessthan or equal to the current time aler the user 
		if ((preg_replace("/[^0-9\s]/", "",$value['routeShortName']) == $route_number ) && ($notified_user != $message_to)) {
		//get the arivial time in seconds since epoch
		$arival_time = (($value['predicted'] =="true") ? $value['predictedArrivalTime']/1000 : $value['scheduledArrivalTime']/1000);
		//get how min seconds the user wants to know before bus arives
		$alert_time = time() + ($alert_check_result['alert_time']*60);
		$seconds_away= $arival_time - time(); //how many seconds away the bus is 
		$min_away = floor($seconds_away/60); //convert the seconds to min 
		$min_suffix = (($min_away == 1) ? "minute" : "minutes" ); //stupid grammer
		$left_stop_check = max(0, $seconds_away); //if the bus has left set this to zero
		$alert_after_time = $alert_check_result['alert_after_time']; // the earliest time to send alert
		$alert_type=  $alert_check_result['alert_type']; //what type of alert to send
		
		//echo print_r($json_route_data_array);
		
		//if the arival time is less than or equal to the alert time alert the user. not negative and more than the least amout of time they wish to be notified
			if(($arival_time <= $alert_time) && ($left_stop_check != 0) && ($arival_time >= $alert_after_time)) { 
				$message =  (($value['predicted'] =="true") ? "Better hurry, the ".$route_number." is ". $min_away." ".$min_suffix. " away." : "No real-time data available for the ".$route_number .", scheduled departure in ". $min_away." ".$min_suffix.".");							

			      		        										
				//alert the user
				if($alert_type == 0){ //sms the user
					$response = $client->request("/$twilio_ApiVersion/Accounts/$twilio_Sid/SMS/Messages",
						"POST", array(
							"To" => $message_to,
							"From" => $twilio_number,
							"Body" => $message
						));
				}
				if($alert_type == 1) { //call the user
					$response = $client->request("/$twilio_ApiVersion/Accounts/$twilio_Sid/Calls",
						"POST", array(
							"To" => $message_to,
							"From" => $twilio_number,
							"Url" => $base_call_url . 'busAlert_call.php?message='.urlencode($message)
						));
				}							
			        $notified_user = $message_to;
				//update the database saying we've already alerted the user. 
				$update_alert_qry = "UPDATE " .$table_name ."  SET `alert_status` = '1' WHERE `sms_from` = '$message_to' AND `route_number` = '$route_number' AND `stop_number` ='$stop_number'";
				$result = mysql_query($update_alert_qry) or die(mysql_error());
				
			}
		
}
}
}
}
//if there are entries older than 3 days set the staus to 3 so we can see why they are still there.
	$alert_error_check = "UPDATE ".$table_name." SET `alert_status` = '3'  WHERE DATE_SUB(CURDATE(),INTERVAL 3 DAY) >= `date` AND `alert_status` != 1";
	$err_result = mysql_query($alert_error_check) or die(mysql_error());

?>
