<?php
###########################################################

/* Define MySQL connection details and database table name */ 
$SETTINGS["hostname"]='localhost';
$SETTINGS["mysql_user"]='saurabh';
$SETTINGS["mysql_pass"]='saurabh';
$SETTINGS["mysql_database"]='rental';
$SETTINGS["loc_table"]='locality'; // Stores all the locality name list from 99 acres 
$SETTINGS["property_table"]='property_detail'; 

/* Connect to MySQL */

if (!isset($install) or $install != '1') {
	$connection = mysql_connect($SETTINGS["hostname"], $SETTINGS["mysql_user"], $SETTINGS["mysql_pass"]) or die ('Unable to connect to MySQL server.<br ><br >Please make sure your MySQL login details are correct.');
	$db = mysql_select_db($SETTINGS["mysql_database"], $connection) or die ('request "Unable to select database."');
};
?>