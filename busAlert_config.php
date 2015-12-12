<?php
/*
Package: busAlert.Me
Description: Get text or call notifications when your bus is getting close to your stop. 
URI: http://busalert.me
(c) 2011 Brooke Dukes All Rights Reserved

Author: Brooke Dukes
Author URI: http://bandonrandon.wordpress.com

Version: 1.0
Date: 2011, April 2nd

File: config.php 
This is the main configuration file of BusAlert.me

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//************************** START CONFIG ***************************//

//Twilio Account Settings
$twilio_Sid="YOUR_TWILIO_SID";
$twilio_Token="YOUR_TWILIO_TOKEN";
$twilio_number= "206-504-2559"; //XXX-XXX-XXXX
$twilio_ApiVersion = "2010-04-01"; //this should probally be left alone :)

//OneBusAway API key
$onebus_api ="YOUR_ONEBUSAWAY_API_KEY";

//Database Settings
$dbhost = 'localhost';
$dbuser = 'YOUR_DB_USER';
$dbpass = 'YOUR_DB_PASSWORD';
$dbname = 'YOUR_DB_NAME';
$table_name = 'bus_data'; //this is the default but may be changed.

// Other Settings
$base_call_url = "http://busalert.example.com/"; // used to referance call files needs tailing slash
$alert_time = 5; // default amount of min to alert user before bus arives.
$alert_type = 0; // default type of alert (0 = SMS 1 = CALL)

// This is just a random string to prevent unauthorized deletion. 
// This is used in busAlert_dbcleanup_cron.php before the cleanup will run.
// Example cron 'busAlert_dbcleanup_cron.php #####YOUR_KEY_HERE######'
// You can create a new key at http://randomkeygen.com/ if you'd like 
$cleanup_key= "D1DFD3878CAD1D2938A52BF7D3CFF"; 

//set timezone list of timezones: http://www.php.net/manual/en/timezones.php 
date_default_timezone_set('America/Los_Angeles');

//************************** END CONFIG ****************************//
// DO NOT EDIT BELOW THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING  //

//connect to the database
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
mysql_select_db($dbname);

//set up twilio
//include the class
require_once ("lib/twilio/twilio.php");
// Twilio REST API version
;

    // Instantiate a new Twilio Rest Client
    $client = new TwilioRestClient($twilio_Sid, $twilio_Token);
?>
