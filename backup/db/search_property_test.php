<?php
error_reporting(0);
include("config1.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>MySQL table search</title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <style>
        BODY, TD {
            font-family:Arial, Helvetica, sans-serif;
            font-size:12px;
        }
    </style>
	<script>
        $(document).ready(function() {

            $('input.city').typeahead({
                name: 'city',
                remote: 'city.php?query=%QUERY'

            });

        })
    </script>
</head>


<body>

<form id="form1" name="form1" method="post" action="search_property_test.php">
<label>Location1</label>
 <input type="text" name="city" size="20" height="8" class="city">
	<label>Location</label>
	<select name="location">
	<option value="">--</option>
	<?php
		$sql = "SELECT distinct location FROM ".$SETTINGS["property_table"]." GROUP BY location ORDER BY location";
		$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
		while ($row = mysql_fetch_assoc($sql_result)) {
			echo "<option value='".$row["location"]."'".($row["location"]==$_REQUEST["location"] ? " selected" : "").">".$row["location"]."</option>";
		}
	?>
    </select>
	<label>Bedrooms</label>
	<select name="Bedrooms">
	<option value="">--</option>
	<?php
		$sql1 = "SELECT s.Bedrooms FROM (SELECT REPLACE(SUBSTRING_INDEX(heading, ',', 1), 'Bedroom', 'BHK') AS Bedrooms FROM ".$SETTINGS["property_table"]." ) s GROUP BY s.Bedrooms";
		$sql_result1 = mysql_query ($sql1, $connection ) or die ('request "Could not execute SQL query" '.$sql1);
		while ($row = mysql_fetch_assoc($sql_result1)) {
			echo "<option value='".$row["Bedrooms"]."'".($row["Bedrooms"]==$_REQUEST["Bedrooms"] ? " selected" : "").">".$row["Bedrooms"]."</option>";
		}
	?>
	</select>
	<label>Posted By </label>
	<select name="posted_by">
	<option value="">--</option>
	<?php
		$sql = "select distinct owner_dealer AS posted_by FROM ".$SETTINGS["property_table"];
		$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
		while ($row = mysql_fetch_assoc($sql_result)) {
			echo "<option value='".$row["posted_by"]."'".($row["posted_by"]==$_REQUEST["posted_by"] ? " selected" : "").">".$row["posted_by"]."</option>";
		}
	?>
    </select>
	<label>Sort_by</label>
	<select name="sort_by">
	<option value="">--</option>
	<option value='last_3_days'>Last 3 Days</option><option value='last_7_days'>Last 7 Days</option><option value='price_high_to_low'>Price High to Low</option><option value='price_low_to_high'>Price Low to High</option>
    </select>
    <input type="submit" name="button" id="button" value="Filter" />
    </label>
    <a href="search_property_test.php">
        reset</a>
</form>
<br /><br />
<table width="700" border="1" cellspacing="0" cellpadding="4">
    <tr>
        <td width="90" bgcolor="#CCCCCC"><strong>Source</strong></td>
        <td width="159" bgcolor="#CCCCCC"><strong>Heading</strong></td>
        <td width="191" bgcolor="#CCCCCC"><strong>Price</strong></td>
        <td width="113" bgcolor="#CCCCCC"><strong>Buildup</strong></td>
		<td width="80" bgcolor="#CCCCCC"><strong>Name</strong></td>
    </tr>
<?php
if ($_REQUEST["Bedrooms"]<>'') {
	$search_bedrooms = " AND REPLACE(SUBSTRING_INDEX(heading, ',', 1), 'Bedroom', 'BHK')='".mysql_real_escape_string($_REQUEST["Bedrooms"])."' ";
}
if ($_REQUEST["location"]<>'') {
	$search_location = " AND location='".mysql_real_escape_string($_REQUEST["location"])."' ";
}
if ($_REQUEST["posted_by"]<>'') {
	$search_postedby = " AND owner_dealer='".mysql_real_escape_string($_REQUEST["posted_by"])."' ";
}
if ($_REQUEST["sort_by"]=='price_high_to_low') {
	$order_by = " order by price desc";
}
else if ($_REQUEST["sort_by"]=='price_low_to_high') {
	$order_by = " order by price asc";
}
else if ($_REQUEST["sort_by"]=='last_3_days') {
	$search_posted_date = " AND POSTED_DATE >= DATE_ADD(CURDATE(), INTERVAL -3 DAY)";
}
else if ($_REQUEST["sort_by"]=='last_7_days') {
	$search_posted_date = " AND POSTED_DATE >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
}
$sql1 = "SELECT distinct source,heading,price,super_buildup,owner_dealer_name AS name FROM ".$SETTINGS["property_table"]." where 1=1";

	$sql =	$sql1.$search_location.$search_bedrooms.$search_postedby.$search_posted_date.$order_by;

	
    $sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql.'11111111111'.$_REQUEST["sort_by"]);
    if (mysql_num_rows($sql_result)>0) {
        while ($row = mysql_fetch_assoc($sql_result)) {
?>
            <tr>
                <td><?php echo $row["source"]; ?></td>
                <td><?php echo $row["heading"]; ?></td>
                <td><?php echo $row["price"]; ?></td>
                <td><?php echo $row["super_buildup"]; ?></td>
				<td><?php echo $row["name"]; ?></td>
            </tr>
            <?php
        }
    } else {
    ?>
    <tr><td colspan="5">No results found.</td>
        <?php
        }
        ?>
</table>