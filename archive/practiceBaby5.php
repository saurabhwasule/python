<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> New Document </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
</HEAD>
<BODY>
<?
// MySQL Connection Information
$server = "localhost";
$username = "sharlene";
$password = "";
$dbname = "baby_names";
// MySQL Connect String
$connection = mysql_connect($server,$username,$password);
// Select the database
mysql_select_db($dbname);
$gender = $_POST['gender'];
$meaning = $_POST['meaning'];
$name = $_POST['name'];
$origin = $_POST['origin'];
$gender = mysql_real_escape_string($gender);
$meaning = mysql_real_escape_string($meaning);
$name = mysql_real_escape_string($name);
$origin = mysql_real_escape_string($origin);
$sql = "SELECT * FROM names WHERE gender = '" . $gender . "' and name LIKE '" . $name . "%' and 
meaning LIKE '%" . $meaning . "%' and origin = '" . $origin . "'";
//print($sql);
//execute SQL query and get result
$sql_result = mysql_query($sql, $connection)
	or die(mysql_error());
//print("after sql");
// Loop through the data set and extract each row into it's own variable set
while ($row = mysql_fetch_array($sql_result)) {
	extract($row);
}
// Loop through the data set and extract each row into it's own variable set
//while ($row = mysql_fetch_array($sql_result)) {
//	echo $row['gender']." ".$row['meaning']." ".$row['name']." ".$row['origin']."<br>";
?>
</BODY>
</HTML>