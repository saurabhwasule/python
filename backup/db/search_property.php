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
</head>


<body>

<form id="form1" name="form1" method="post" action="search_property.php">
	<label>Location</label>
	<select name="location">
	<option value="">--</option>
	<?php
		$sql = "select distinct location FROM ".$SETTINGS["property_table"]."ORDER BY location";
		$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
		while ($row = mysql_fetch_assoc($sql_result)) {
			echo "<option value='".$row["location"]."'".($row["location"]==$_REQUEST["location"] ? " selected" : "").">".$row["location"]."</option>";
		}
	?>
    </select>
    <input type="submit" name="button" id="button" value="Filter" />
    </label>
    <a href="search_property.php">
        reset</a>
</form>
<br /><br />
<table width="700" border="1" cellspacing="0" cellpadding="4" style="height: 500px;">
    <tr>
        <td width="90" bgcolor="#CCCCCC"><strong>Source</strong></td>
        <td width="159" bgcolor="#CCCCCC"><strong>Heading</strong></td>
        <td width="191" bgcolor="#CCCCCC"><strong>Price</strong></td>
        <td width="113" bgcolor="#CCCCCC"><strong>Buildup</strong></td>
    </tr>
<?php
	if ($_REQUEST["location"]<>'') {
		$sql = "SELECT source,heading,price,super_buildup FROM ".$SETTINGS["property_table"]." WHERE location='".mysql_real_escape_string($_REQUEST["location"])."' ";
	}
    else {
        $sql = "SELECT source,heading,price,super_buildup FROM ".$SETTINGS["property_table"]." order by price LIMIT 100";
        }
    $sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
    if (mysql_num_rows($sql_result)>0) {
        while ($row = mysql_fetch_assoc($sql_result)) {
?>
            <tr>
                <td><?php echo $row["Source"]; ?></td>
                <td><?php echo $row["Heading"]; ?></td>
                <td><?php echo $row["Price"]; ?></td>
                <td><?php echo $row["Heading"]; ?></td>
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