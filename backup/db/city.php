<?php
include("config1.php");
//CREATE QUERY TO DB AND PUT RECEIVED DATA INTO ASSOCIATIVE ARRAY
//if (isset($_REQUEST['query'])) {
    //$query = $_REQUEST['query'];
	//$query = "Kad"
	//$sql = "select name from ".$SETTINGS["locality"]." WHERE name LIKE 'Kad%'";
	//$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql.$query);
	$sql = "select name from ".$SETTINGS["loc_table"]." WHERE name like 'Kad%'";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	$array = array();
    while ($row = mysql_fetch_array($sql_result)) {
        $array[] = array (
            'label' => $row['name'],
            'value' => $row['name'],
        );
  //  }
    //RETURN JSON ARRAY
    echo json_encode ($array);
}
?>

