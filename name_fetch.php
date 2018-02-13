<?php
	include("config.php");
     $keyword = $_POST['data'];
	//$keyword='k';
	$sql = "select distinct name from ".$db_table." where ".$db_column." like '".$keyword."%' and region='east' limit 0,20";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	if($result)
	{
		echo '<ul class="list">';
		$number_of_results = mysqli_num_rows($result);
		if ($number_of_results==0) {
				// list is empty.
			echo '<li><a href=\'javascript:void(0);\'>'.'no location found'.'</a></li>';
		 }
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
		echo '</ul>';
	}
?>	   
