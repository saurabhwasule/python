<HTML>
 <HEAD>
  <TITLE>Home-Rental Search </TITLE>
  <link href="css/style.css" rel="stylesheet" type="text/css">
 <SCRIPT LANGUAGE="JavaScript" src="js/jquery.js"></SCRIPT>
 <SCRIPT LANGUAGE="JavaScript" src="js/script.js"></SCRIPT>
  <script>
   
function myFunction() {
    var x = document.getElementById("loader");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
</script>
 </HEAD>
 <BODY>
 <div class="loader"></div>
<form id="form1" name="form1" method="post" autocomplete="off" action="main_test.py">
  <div class="main">
     	 <div> 
		 <label>Location</label>
		<input name="location" value="<?php if(isset($_POST['location']))echo $_POST['location'];?>" type="text" id="keyword" tabindex="0">
		<label>Posted_Since</label>
		<select  name="posted_since">
		  <option value="all">All</option>
		  <option value="3">Last 3 Days</option>
		  <option value="7">Last 7 Days</option>
		  <option value="30">Last Month</option>
		</select>
		 </div>
		 <div id="ajax_response"></div> 
   </div>
	 <input type="submit" name="button" id="button" value="Submit" onclick="myFunction()" />
    </label>	
    <a href="index.php">reset</a>
	<div id="loader1">
	<img id="loader" name="image01" src="images/wait1.gif"  value= "Show image" style="display:none;">
	</div>
   </div>
</form>
 </BODY>
</HTML>