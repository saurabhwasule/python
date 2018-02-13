<html>
  <head>
    <title>Home-Rental Search</title>
    <link href="dist/css/bootstrap.min.css" rel="stylesheet" type="text/css "/>
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
	  .required{
      color:red;
}

    </style>
    <script>

function myFunction() {
    var x = document.getElementById("loader");
	var y =document.getElementById("keyword").value;
    if (x.style.display === "none" && y!=="") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
</script>

  </head>
  <body class="bg-bdy">
    <div class="loader"></div>
     <!--<div class="container-fluid bg-bdy">-->
      <!--<div class="container-fluid header">
          <h1>Home Rental Search</h1>
      </div> -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Home Rental Search</a>
          </div>
          
        </div>
      </nav>
      <br/>
      <div class=" container well ">
        <form id="form1" name="form1" method="post" autocomplete="off" action="python\main_test.py" class="form-inline text-center" >
          <div class="">
            <div class="form-group">
              <label for="keyword"><span class="required">*&nbsp;</span>Location </label>
              <input name="location" value="<?php if(isset($_POST['location']))echo $_POST['location'];?>" type="text" id="keyword" tabindex="0" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="select">Posted_Since</label>
          		<select  name="posted_since"  class="form-control" id="select">
          		  <option value="3">Last 3 Days</option>
          		  <option value="7">Last Week</option>
				  <option value="14">Last 2 Weeks</option>
          		  <option value="30">Last Month</option>
          		</select>
            </div>
            <div id="ajax_response"></div>
            <button type="submit" name="button" id="button" value="Submit" onclick="myFunction()" class="btn btn-default">Submit</button>
          </div>
        </form>

      </div>
      <div id="loader1">
        <img id="loader" name="image01" src="images/loading.gif"  value= "Show image" style="display:none;">
      </div>
    </div>
    <script src="dist/js/jquery.js" type="text/javascript"></script>
    <script src="dist/js/script.js" type="text/javascript"></script>
    <script src="dist/js/bootstrap.min.js" type="text/javascript"></script>
  </body>
</html>
