<HTML>
 <HEAD>
  <TITLE> Rental Serach-Home </TITLE>
  <link href="css/style.css" rel="stylesheet" type="text/css">
 <SCRIPT LANGUAGE="JavaScript" src="js/jquery.js"></SCRIPT>
 <SCRIPT LANGUAGE="JavaScript" src="js/script.js"></SCRIPT>
 </HEAD>
 <BODY>
 <div class="loader"></div>
<form id="form1" name="form1" method="post" autocomplete="off" action="99_acres.py">
  <div class="main">
     	 <div id="holder"> 
		 <label>Location</label>
		<input name="location" value="<?php if(isset($_POST['location']))echo $_POST['location'];?>" type="text" id="keyword" tabindex="0">
		 </div>
		 <div id="ajax_response"></div> 
   </div>
	 <input type="submit" name="button" id="button" value="Submit" onclick="myFunction()" />
    </label>	
    <a href="index.php">reset</a>
   </div>
</form>
 </BODY>
</HTML>