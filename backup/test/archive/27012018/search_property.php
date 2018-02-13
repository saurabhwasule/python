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
	<link href="css/style.css" rel="stylesheet" type="text/css">
	<SCRIPT LANGUAGE="JavaScript" src="js/jquery.js"></SCRIPT>
	<SCRIPT LANGUAGE="JavaScript" src="js/script.js"></SCRIPT>
	<SCRIPT LANGUAGE="JavaScript" src="js/read_more.js"></SCRIPT>
</head>

<body>

<form id="form1" name="form1" method="get" autocomplete="off" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div class="main">
     	 <div id="holder"> 
		 <label>Location</label>
		<input name="location" value="<?php echo $_GET['location'] ;?>" type="text" id="keyword" tabindex="0"><img src="images/loading.gif" id="loading">
		 </div>
   </div>
    </select>
	<label>Bedrooms</label>
	<select name="Bedrooms">
	<option value="">--</option>
	<?php
		$sql = "select t.BHK Bedrooms from (SELECT distinct CASE WHEN s.Bedrooms like '%BHK%' THEN s.Bedrooms ELSE 'Others' END BHK 
				 FROM(
					SELECT REPLACE(SUBSTRING_INDEX(heading, ',', 1), 'Bedroom', 'BHK') AS Bedrooms FROM property_detail
					where source='99_acres'
					union 
					SELECT SUBSTRING_INDEX(heading, ' ', 2) AS Bedrooms FROM property_detail
					where source='magic_brick'
					) s ) t GROUP BY t.BHK
					order by 1";
		$sql_result1 = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
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
	<option value='last_3_days'<?= $_REQUEST["sort_by"]=="last_3_days"?" selected='selected'":"" ?>>Last 3 Days</option>
	<option value='last_7_days'<?= $_REQUEST["sort_by"]=="last_7_days"?" selected='selected'":"" ?>>Last 7 Days</option>
	<option value='price_high_to_low'<?= $_REQUEST["sort_by"]=="price_high_to_low"?" selected='selected'":"" ?>>Price High to Low</option>
	<option value='price_low_to_high'<?= $_REQUEST["sort_by"]=="price_low_to_high"?" selected='selected'":"" ?>>Price Low to High</option>
    </select>
    <input type="submit" name="button" id="button" value="Filter" />
    </label>	
    <a href="search_property.php">
        reset</a>
</form>
<br /><br />
<table border="1" class="users">
    <tr>
        <td width="90" bgcolor="#CCCCCC"><strong>Source</strong></td>
        <td width="159" bgcolor="#CCCCCC"><strong>Heading</strong></td>
        <td width="50" bgcolor="#CCCCCC"><strong>Price</strong></td>
        <td width="70" bgcolor="#CCCCCC"><strong>Buildup</strong></td>
		<td width="60" bgcolor="#CCCCCC"><strong>Posted_date</strong></td>
		<td width="70" bgcolor="#CCCCCC"><strong>Posted_by</strong></td>
		<td width="100" bgcolor="#CCCCCC"><strong>Name</strong></td>
		<td width="80" bgcolor="#CCCCCC"><strong>Property_details</strong></td>
    </tr>
<?php
if (strpos($_REQUEST["Bedrooms"],'BHK')) {
	$search_bedrooms = " AND (REPLACE(SUBSTRING_INDEX(heading, ',', 1), 'Bedroom', 'BHK')='".mysql_real_escape_string($_REQUEST["Bedrooms"])."' OR SUBSTRING_INDEX(heading, ' ', 2)='".mysql_real_escape_string($_REQUEST["Bedrooms"])."' )";
}
else if (strpos($_REQUEST["Bedrooms"],'Others')) {
	$search_bedrooms = " AND (REPLACE(SUBSTRING_INDEX(heading, ',', 1), 'Bedroom', 'BHK') <> 'BHK' OR SUBSTRING_INDEX(heading, ',', 2) <>'BHK'";
}
if ($_REQUEST["location"]<>'') {
	$search_location = " AND location='".mysql_real_escape_string($_REQUEST["location"])."' ";
}
if ($_REQUEST["posted_by"]<>'') {
	$search_postedby = " AND owner_dealer='".mysql_real_escape_string($_REQUEST["posted_by"])."' ";
}
$order_by="order by posted_date desc";
if ($_REQUEST["sort_by"]=='price_high_to_low') {
	$order_by = " order by price desc";
}
else if ($_REQUEST["sort_by"]=='price_low_to_high') {
	$order_by = " order by price asc";
}
else if ($_REQUEST["sort_by"]=='last_3_days') {
	$search_posted_date = " AND POSTED_DATE > DATE_ADD(CURDATE(), INTERVAL -3 DAY)";
}
else if ($_REQUEST["sort_by"]=='last_7_days') {
	$search_posted_date = " AND POSTED_DATE > DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
}
$sql1 = "SELECT distinct source,heading,price,super_buildup,posted_date,owner_dealer,owner_dealer_name AS name,PROPERTY_DETAIL_URL FROM ".$SETTINGS["property_table"]." where 1=1";


//pagination logic

// connect to database
$con = mysqli_connect('localhost','saurabh','saurabh');
mysqli_select_db($con, 'rental');

// define how many results you want per page
$results_per_page = 13;

// find out the number of results stored in database
$sql2=$sql1.$search_location.$search_bedrooms.$search_postedby.$search_posted_date;
$result = mysqli_query($con, $sql2);
$number_of_results = mysqli_num_rows($result);
// determine number of total pages available
$number_of_pages = ceil($number_of_results/$results_per_page);

// determine which page number visitor is currently on
if (!isset($_GET['page'])) {
  $page = 1;
} else {
  $page = $_GET['page'];
}
// determine the sql LIMIT starting number for the results on the displaying page
$this_page_first_result = ($page-1)*$results_per_page;

// retrieve selected results from database and display them on page
$LIMIT=' LIMIT ' . $this_page_first_result . ',' .  $results_per_page;
//$result = mysqli_query($con, $sql);

	$sql =	$sql1.$search_location.$search_bedrooms.$search_postedby.$search_posted_date.$order_by.$LIMIT;

	
    $sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
    if (mysql_num_rows($sql_result)>0) {
        while ($row = mysql_fetch_assoc($sql_result)) {
?>
            <tr>
                <td><?php echo "<img src=images/".$row["source"].".PNG width=90 height=20>"; ?></td>
                <td class="item" width="250"><?php echo $row["heading"]; ?></td>
                <td><?php echo $row["price"]; ?></td>
                <td><?php echo $row["super_buildup"]; ?></td>
				<td><?php echo $row["posted_date"]; ?></td>
				<td><?php echo $row["owner_dealer"]; ?></td>
				<td><?php echo $row["name"]; ?></td>
				<td><?php echo "<a href=".$row["PROPERTY_DETAIL_URL"].">Click here</a>"; ?></td>
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
<div class="pagination" id="lnk" style="text-align: left;">
	<?php
// display the links to the pages
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// get the position where '&page.. ' text start.
$pos = strpos($actual_link , '&page');
if ($pos==0 ){
	$finalurl=$actual_link;
}
else
{
	$finalurl = substr($actual_link,0,$pos);
}
// remove string from the specific postion

?>
<span class="Apple-style-span" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; line-height: 18px; ">
<br>
<?php
		for ($page=1;$page<=$number_of_pages;$page++) {
		  echo '&nbsp;&nbsp;<a href="'.$finalurl.'&page=' . $page . '">' . $page . '</a> ';
		}
?>
</div>
</body>
</html>