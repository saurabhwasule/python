
<?php
$host    = "localhost"; // Host name
$db_name = "test";		// Database name
$db_user = "saurabh";		// Database user name
$db_pass = "saurabh";			// Database Password
$db_table= "username";		// Table name
$db_column = "name";	// Table column from which suggestions will get shown

 $mysqli=mysqli_connect($host,$db_user,$db_pass,$db_name) or die("Database Error");  
 // Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  } 

?>
