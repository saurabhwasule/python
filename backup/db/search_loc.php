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

<form id="form1" name="form1" method="post" action="search_loc.php">
    <label>ID:</label>
    <input type="text" name="string" id="string" value="<?php echo stripcslashes($_REQUEST["string"]); ?>" />
	<label>Region</label>
	<select name="region">
	<option value="">--</option>
	<?php
		$sql = "SELECT distinct region FROM ".$SETTINGS["loc_table"]." GROUP BY region ORDER BY region";
		$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
		while ($row = mysql_fetch_assoc($sql_result)) {
			echo "<option value='".$row["region"]."'".($row["region"]==$_REQUEST["region"] ? " selected" : "").">".$row["region"]."</option>";
		}
	?>
    </select>
    <input type="submit" name="button" id="button" value="Filter" />
    </label>
    <a href="search_loc.php">
        reset</a>
</form>
<br /><br />
<table width="700" border="1" cellspacing="0" cellpadding="4" style="height: 500px;">
    <tr>
        <td width="90" bgcolor="#CCCCCC"><strong>ID</strong></td>
        <td width="159" bgcolor="#CCCCCC"><strong>Name</strong></td>
        <td width="191" bgcolor="#CCCCCC"><strong>Region</strong></td>
        <td width="113" bgcolor="#CCCCCC"><strong>City</strong></td>
    </tr>
<?php
    if ($_REQUEST["string"]<>'') {
        $sql = "SELECT * FROM ".$SETTINGS["loc_table"]." WHERE ID=".mysql_real_escape_string($_REQUEST["string"])."";
    }
	else if ($_REQUEST["region"]<>'') {
		$sql = "SELECT * FROM ".$SETTINGS["loc_table"]." WHERE region='".mysql_real_escape_string($_REQUEST["region"])."' ";
	}
    else {
        $sql = "SELECT * FROM ".$SETTINGS["loc_table"]." order by name LIMIT 100";
        }
    $sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
    if (mysql_num_rows($sql_result)>0) {
        while ($row = mysql_fetch_assoc($sql_result)) {
?>
            <tr>
                <td><?php echo $row["ID"]; ?></td>
                <td><?php echo $row["NAME"]; ?></td>
                <td><?php echo $row["REGION"]; ?></td>
                <td><?php echo $row["CITY"]; ?></td>
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