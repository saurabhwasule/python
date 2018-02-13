<?php
error_reporting(0);
include("config.php");
?>
<html>
  <head>
    <title>Properties in <?php echo $_GET['location'] ;?></title>
    <link href="dist/css/bootstrap.min.css" rel="stylesheet" type="text/css "/>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="dist/css/style.css" rel="stylesheet" type="text/css "/>
	 <style>
    html, body {
    width: 100%;
    overflow-x: hidden;
}
      body{

      }
      .bg-bdy{
        /* background-color:#fdc600; */
        height: 100%;
        background:url('images/bg-images.jpg'), rgba(52, 73, 94, 0.75);
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        background-size: cover;
        /* background-color: #464646; */
      }
      .header{
        background-color: #fff;
        height: 12%;
      }
      .header h1{
        color:#fdc600;
      }
    </style>
  </head>
  <body>
      <div class="container-fluid bg-bdy">
        <div class="container-fluid header">
            <h1>Rental Search</h1>
        </div>
        <br/>
        <div class=" container well ">
          <form id="form1" name="form1" method="get" autocomplete="off" action="<?php echo $_SERVER[REQUEST_URI]; ?>" class="form-inline text-center">
            <div class="">
              <div class="form-group">
                <label for="keyword">Location</label>
                <input name="location" value="<?php echo $_GET['location'] ;?>" type="text" id="keyword" tabindex="0" class="form-control">
                <input name="created_date_time" value="<?php echo $_GET['created_date_time'] ;?>" type="hidden">
              </div>
              <div class="form-group">
                <label for="bedrmsslct">Bedrooms</label>
                <select  class="form-control" id="bedrmsslct" name="Bedrooms">
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
		
		$sql_result1 = mysqli_query($mysqli,$sql) or die(mysqli_error());
		while ($row = mysqli_fetch_assoc($sql_result1)) {
			echo "<option value='".$row["Bedrooms"]."'".($row["Bedrooms"]==$_REQUEST["Bedrooms"] ? " selected" : "").">".$row["Bedrooms"]."</option>";
		}
	?>
	</select>
              </div>
              <div class="form-group">
                <label for="pstbyslct">Posted By</label>
                <select  class="form-control" id="pstbyslct" name="posted_by">
                  <option value="">--</option>
						   <?php
				$sql = "select distinct owner_dealer AS posted_by FROM property_detail";
				
				$sql_result = mysqli_query($mysqli,$sql) or die(mysqli_error());
				while ($row = mysqli_fetch_assoc($sql_result)) {
					echo "<option value='".$row["posted_by"]."'".($row["posted_by"]==$_REQUEST["posted_by"] ? " selected" : "").">".$row["posted_by"]."</option>";
				}
			?>
                </select>
              </div>
              <div class="form-group">
                <label for="srtbyslct">Sort_by</label>
                <select  class="form-control" id="srtbyslct" name="sort_by">
                  <option value="">--</option>
                	<option value='last_3_days'>Last 3 Days</option>
                	<option value='last_7_days'>Last 7 Days</option>
                	<option value='price_high_to_low'>Price High to Low</option>
                	<option value='price_low_to_high'>Price Low to High</option>
                </select>
              </div>
              <button type="submit" name="button" id="button" value="Filter" class="btn btn-default">Filter</button>
              <a href="search_property.php">reset</a>
            </div>
          </form>
          <br/>
          <table class="table users" border="1" >
            <thead>
              <tr>
                <th>Source</th>
                <th>Heading</th>
                <th>Price</th>
                <th>Buildup</th>
                <th>Posted_date</th>
                <th>Posted_by</th>
                <th>Name</th>
                <th>Property_details</th>
              </tr>
			  <?php
				if (strpos($_REQUEST["Bedrooms"],'BHK')) {
					$search_bedrooms = " AND (REPLACE(SUBSTRING_INDEX(heading, ',', 1), 'Bedroom', 'BHK')='".mysqli_real_escape_string($mysqli,$_REQUEST["Bedrooms"])."' OR SUBSTRING_INDEX(heading, ' ', 2)='".mysqli_real_escape_string($mysqli,$_REQUEST["Bedrooms"])."' )";
				}
				
				if ($_REQUEST["Bedrooms"]=='Others') {
					$search_bedrooms = " AND REPLACE(SUBSTRING_INDEX(heading, ',', 1), 'Bedroom', 'BHK') not like '%BHK%'";
				}
				if ($_REQUEST["location"]<>'') {
					$search_location = " AND location='".mysqli_real_escape_string($mysqli,$_REQUEST["location"])."' ";
				}
				if ($_REQUEST["posted_by"]<>'') {
					$search_postedby = " AND owner_dealer='".mysqli_real_escape_string($mysqli,$_REQUEST["posted_by"])."' ";
				}
				$order_by="order by posted_date desc";
				if ($_REQUEST["sort_by"]=='price_high_to_low') {
					$order_by = " order by price desc";
				}
				else if ($_REQUEST["sort_by"]=='price_low_to_high') {
					$order_by = " order by price asc";
				}
				else if ($_REQUEST["sort_by"]=='last_3_days'||$_GET['posted_since']==3) {
					$search_posted_date = " AND POSTED_DATE > DATE_ADD(CURDATE(), INTERVAL -3 DAY)";
				}
				else if ($_REQUEST["sort_by"]=='last_7_days'||$_GET['posted_since']==7) {
					$search_posted_date = " AND POSTED_DATE > DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
				}
				$sql1 = "SELECT distinct source,heading,price,super_buildup,posted_date,owner_dealer,owner_dealer_name AS name,PROPERTY_DETAIL_URL FROM property_detail where 1=1";

				//echo $latest_timestamp = str_replace(";"," ",$_GET['created_date_time']);
				$latest_timestamp = " AND created_timestamp = '". str_replace(";"," ",$_GET['created_date_time'])."'";
				
				//pagination logic

				// define how many results you want per page
				$results_per_page = 7;

				// find out the number of results stored in database
				$sql2=$sql1.$search_location.$search_bedrooms.$search_postedby.$search_posted_date.$latest_timestamp;
				$result = mysqli_query($mysqli, $sql2);
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

				//latest searched created timestamp 

				

				$sql=$sql1.$search_location.$search_bedrooms.$search_postedby.$search_posted_date.$latest_timestamp.$order_by.$LIMIT;

					//$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
					$sql_result = mysqli_query($mysqli,$sql) or die(mysqli_error());
					if (mysqli_num_rows($sql_result)>0) {
						while ($row = mysqli_fetch_assoc($sql_result)) {
				?>
            </thead>
            
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
    <tr><td colspan="8">No results found.</td>
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
<?php
		for ($page=1;$page<=$number_of_pages;$page++) {
		  echo '&nbsp;&nbsp;<a href="'.$finalurl.'&page=' . $page . '">' . $page . '</a> ';
		}
?>
</div>
</body>
</html>
    <script src="dist/js/jquery.js" type="text/javascript"></script>
    <script src="dist/js/script.js" type="text/javascript"></script>
    <script src="dist/js/read_more.js"></script>
    <script src="dist/js/bootstrap.min.js" type="text/javascript"></script>
