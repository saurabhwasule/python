<?php
error_reporting(0);
include("config.php");
?>
<html>
  <head>
    <title>Properties in 
      <?php
echo $_GET['location'];
?>
    </title>
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
      div.pagination {
        padding: 3px;
        margin: 3px;
      }
      div.pagination a {
        padding: 2px 5px 2px 5px;
        margin: 2px;
        border: 1px solid #AAAADD;
        text-decoration: none;
        /* no underline */
        color: #000099;
      }
      div.pagination a:hover, div.pagination a:active {
        border: 1px solid #000099;
        color: #000;
      }
      div.pagination span.current {
        padding: 2px 5px 2px 5px;
        margin: 2px;
        border: 1px solid #000099;
        font-weight: bold;
        background-color: #000099;
        color: #FFF;
      }
      div.pagination span.disabled {
        padding: 2px 5px 2px 5px;
        margin: 2px;
        border: 1px solid #EEE;
        color: #DDD;
      }
    </style>
  </head>
  <body class="bg-bdy">
    <div class="loader"></div>
    <!-- <div class="container-fluid bg-bdy">
      <div class="container-fluid header">
          <h1>Home Rental Search</h1>
      </div> -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Home Rental Search</a>
          </div>
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="log.php">Log</a></li>
          </ul>
        </div>
      </nav>
      <br/>
      <div class=" container well ">
        <form id="form1" name="form1" method="get" autocomplete="off" action="<?php echo $_SERVER[REQUEST_URI]; ?>" 
              class="form-inline text-center">
          <div class="">
            <div class="form-group">
              <label for="keyword">Location
              </label>
              <input name="location" value="<?php echo $_GET['location'];?>" 
                     type="text" id="keyword" tabindex="0" class="form-control">
              <input name="created_date_time" value="<?php echo $_GET['created_date_time'];?>" type="hidden">
              <input name="posted_since" value="<?php echo $_GET['posted_since'];?>" type="hidden">
            </div>
            <div class="form-group">
              <label for="bedrmsslct">Bedrooms
              </label>
              <select  class="form-control" id="bedrmsslct" name="Bedrooms">
                <option value="">--
                </option>
                <?php
//echo $latest_timestamp = str_replace(";"," ",$_GET['created_date_time']);
$latest_timestamp = " AND created_timestamp = '" . str_replace(";", " ", $_GET['created_date_time']) . "' ";
$sql = "SELECT t.BHK Bedrooms
FROM (SELECT DISTINCT
CASE
WHEN s.Bedrooms LIKE '%BHK%' THEN s.Bedrooms
ELSE 'Others'
END
BHK
FROM (SELECT REPLACE(SUBSTRING_INDEX(heading, ',', 1),
'Bedroom',
'BHK')
AS Bedrooms
FROM property_detail
WHERE source = '99_acres'
and location='".$_GET['location']."'".$latest_timestamp.
" UNION
SELECT SUBSTRING_INDEX(heading, ' ', 2) AS Bedrooms
FROM property_detail
WHERE source = 'magic_brick' 
and location='".$_GET['location']."'".$latest_timestamp.
" ) s) t
GROUP BY t.BHK
ORDER BY 1";
$sql_result1 = mysqli_query($mysqli, $sql) or die(mysqli_error());
while ($row = mysqli_fetch_assoc($sql_result1)) 
{
echo "<option value='" . $row["Bedrooms"] . "'" . ($row["Bedrooms"] == $_REQUEST["Bedrooms"] ? " selected" : "") . ">" . $row["Bedrooms"] . "</option>";
}
?>
              </select>
            </div>
            <div class="form-group">
              <label for="pstbyslct">Posted By
              </label>
              <select  class="form-control" id="pstbyslct" name="posted_by">
                <option value="">--
                </option>
                <?php 
$sql = "select distinct owner_dealer AS posted_by FROM property_detail";
$sql_result = mysqli_query($mysqli, $sql) or die(mysqli_error());
while ($row = mysqli_fetch_assoc($sql_result)) 
{
echo "<option value='" . $row["posted_by"] . "'" . ($row["posted_by"] == $_REQUEST["posted_by"] ? " selected" : "") . ">" . $row["posted_by"] . "</option>";
}
?>
              </select>
            </div>
            <div class="form-group">
              <label for="srtbyslct">Sort_by
              </label>
              <select  class="form-control" id="srtbyslct" name="sort_by">
                <option value="">--
                </option>
                <option value='owner'>Owner Properties
                </option>
                <option value='price_high_to_low'>Price High to Low
                </option>
                <option value='price_low_to_high'>Price Low to High
                </option>
              </select>
            </div>
            <button type="submit" name="button" id="button" value="Search" class="btn btn-default">Search
            </button>
          </div>
        </form>
        <br/>
		<div class="table-responsive">         <table class="table users" border="1" >
          <thead>
            <tr>
              <th>Source
              </th>
              <th>Heading
              </th>
              <th>Price
              </th>
              <th>Buildup
              </th>
              <th>Posted date
              </th>
              <th>Posted by
              </th>
              <th>Name
              </th>
              <th>Property details
              </th>
            </tr>
            <?php
if (strpos($_REQUEST["Bedrooms"], 'BHK')) {
$search_bedrooms = " AND (REPLACE(SUBSTRING_INDEX(heading, ',', 1), 'Bedroom', 'BHK')='" . mysqli_real_escape_string($mysqli, $_REQUEST["Bedrooms"]) . "' OR SUBSTRING_INDEX(heading, ' ', 2)='" . mysqli_real_escape_string($mysqli, $_REQUEST["Bedrooms"]) . "' )";
}
if ($_REQUEST["Bedrooms"] == 'Others') {
$search_bedrooms = " AND REPLACE(SUBSTRING_INDEX(heading, ',', 1), 'Bedroom', 'BHK') not like '%BHK%'";
}
if ($_REQUEST["location"] <> '') {
$search_location = " AND location='" . mysqli_real_escape_string($mysqli, $_REQUEST["location"]) . "' ";
}
if ($_REQUEST["posted_by"] <> '') {
$search_postedby = " AND owner_dealer='" . mysqli_real_escape_string($mysqli, $_REQUEST["posted_by"]) . "' ";
}
$order_by = "order by posted_date desc";
if ($_REQUEST["sort_by"] == 'price_high_to_low') {
$order_by = " order by price desc";
} else if ($_REQUEST["sort_by"] == 'price_low_to_high') {
$order_by = " order by price asc";
} else if ($_REQUEST["sort_by"] == 'owner') {
$order_by = " order by case when owner_dealer='owner' then 1 else 2 end asc ";
}
$search_posted_date = " AND POSTED_DATE > DATE_ADD(CURDATE(), INTERVAL -" . $_GET['posted_since'] . " DAY)";
$sql1 = "SELECT distinct source,heading,price,super_buildup,posted_date,owner_dealer,owner_dealer_name AS name,PROPERTY_DETAIL_URL FROM property_detail where 1=1";
//pagination logic
// How many adjacent pages should be shown on each side?
$adjacents = 3;
/* 
First get total number of rows in data table. 
If you have a WHERE clause in your query, make sure you mirror it here.
*/
$sql3 = "SELECT distinct source,heading,price,super_buildup,posted_date,owner_dealer,owner_dealer_name AS name,PROPERTY_DETAIL_URL FROM property_detail where 1=1";
$sql2  = "select count(*) as num from (".$sql3 . $search_location . $search_bedrooms . $search_postedby . $search_posted_date . $latest_timestamp.")t";
$sql_result = mysqli_query($mysqli, $sql2) or die(mysqli_error());
$total_pages = mysqli_fetch_array($sql_result);
$total_pages = $total_pages[num];
/* Setup vars for query. */
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// get the position where '&page.. ' text start.
$pos = strpos($actual_link, '&page');
if ($pos == 0) {
$finalurl = $actual_link;
} else {
$finalurl = substr($actual_link, 0, $pos);
}
$targetpage = $finalurl; 	//your file name  (the name of this file)
$limit = 7; 								//how many items to show per page
$page = $_GET['page'];
$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.

if ($page < 1) $page = 1;						// If page number is less than 1 then make it 1
if ($page > $lastpage) $page = $lastpage;						// If page number is less than 1 then make it 1
if($page) 
$start = ($page - 1) * $limit; 			//first item to display on this page
else
$start = 0;								//if no page var is given, set start to 0


/* Get data. */
//$sql = "SELECT distinct source,heading,price,super_buildup,posted_date,owner_dealer,owner_dealer_name AS name,PROPERTY_DETAIL_URL FROM $tbl_name LIMIT //$start, $limit";
$sql = $sql1 . $search_location . $search_bedrooms . $search_postedby . $search_posted_date . $latest_timestamp . $order_by . " LIMIT ".$start.",". $limit;
/* Setup page vars for display. */

if ($page == 0) $page = 1;					//if no page var is given, default to 1.
$prev = $page - 1;							//previous page is page - 1
$next = $page + 1;							//next page is page + 1
//$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
$lpm1 = $lastpage - 1;						//last page minus 1

/* 
Now we apply our rules and draw the pagination object. 
We're actually saving the code to a variable in case we want to draw it more than once.
*/
$pagination = "";
if($lastpage > 1)
{	
$pagination .= "<div class=\"pagination\">";
//previous button
if ($page > 1) 
$pagination.= "<a href=\"$targetpage&page=$prev\">&lt&ltprevious</a>";
else
//$pagination.= "<span class=\"disabled\">&lt&ltprevious</span>";
$pagination.= "";	
//pages	
if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
{	
for ($counter = 1; $counter <= $lastpage; $counter++)
{
if ($counter == $page)
$pagination.= "<span class=\"current\">$counter</span>";
else
$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
}
}
elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
{
//close to beginning; only hide later pages
if($page < 1 + ($adjacents * 2))		
{
for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
{
if ($counter == $page)
$pagination.= "<span class=\"current\">$counter</span>";
else
$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
}
$pagination.= "...";
$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
}
//in middle; hide some front and some back
elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
{
$pagination.= "<a href=\"$targetpage&page=1\">1</a>";
$pagination.= "<a href=\"$targetpage&page=2\">2</a>";
$pagination.= "...";
for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
{
if ($counter == $page)
$pagination.= "<span class=\"current\">$counter</span>";
else
$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
}
$pagination.= "...";
$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
}
//close to end; only hide early pages
else
{
$pagination.= "<a href=\"$targetpage&page=1\">1</a>";
$pagination.= "<a href=\"$targetpage&page=2\">2</a>";
$pagination.= "...";
for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
{
if ($counter == $page)
$pagination.= "<span class=\"current\">$counter</span>";
else
$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
}
}
}
//next button
if ($page < $counter - 1) 
$pagination.= "<a href=\"$targetpage&page=$next\">next >></a>";
else
//$pagination.= "<span class=\"disabled\">next >></span>";
$pagination.= "";
$pagination.= "</div>\n";		
}
$sql_result = mysqli_query($mysqli, $sql) or die(mysqli_error());
if (mysqli_num_rows($sql_result) > 0) {
while ($row = mysqli_fetch_assoc($sql_result)) {
?>
          </thead>
          <tr>
            <td>
              <?php echo "<img src=images/" . $row["source"] . ".PNG width=90 height=20>";?>
            </td>
            <td class="item" width="200">
              <?php echo $row["heading"];?>
            </td>
            <td>
              <?php
				if ($row["price"] == 9999999) {
				echo 'Call for Price';
				} else {
				echo $row["price"];
				}
				;
				?>
            </td>
            <td>
              <?php echo $row["super_buildup"];?>
            </td>
            <td>
              <?php echo $row["posted_date"];?>
            </td>
            <td>
              <?php echo $row["owner_dealer"];?>
            </td>
            <td>
              <?php echo $row["name"];?>
            </td>
            <td>
              <?php echo "<a href=".$row["PROPERTY_DETAIL_URL"] . ">Click here</a>";?>
            </td>
          </tr>
          <?php
}	
}
else {
?>
          <tr>
            <td colspan="8">No results found.
            </td>
            <?php
}
?>
        </table>
        <?=$pagination?></div>

      </div>
      </body>
    </html>
  <script src="dist/js/jquery.js" type="text/javascript">
  </script>
  <script src="dist/js/script.js" type="text/javascript">
  </script>
  <script src="dist/js/read_more.js">
  </script>
  <script src="dist/js/bootstrap.min.js" type="text/javascript">
  </script>
