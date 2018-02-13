<?php
	include("config.php");
	$keyword = $_POST['data'];
	$sql = "select distinct name from ".$db_table." where ".$db_column." like '".$keyword."%' and region='east' limit 0,20";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	if($result)
	{
		echo '<ul class="list">';
		while($row = mysqli_fetch_array($result))
		{
			$str = strtolower($row['name']);
			$start = strpos($str,$keyword); 
			$end   = similar_text($str,$keyword); 
			$last = substr($str,$end,strlen($str));
			$first = substr($str,$start,$end);
			
			$final = '<span class="bold">'.$first.'</span>'.$last;
		
			echo '<li><a href=\'javascript:void(0);\'>'.$final.'</a></li>';
		}
		echo "</ul>";
	}
	else
		echo 0;
?>	   
