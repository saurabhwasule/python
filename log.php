<html>
 <head>
  <link href="dist/css/bootstrap.min.css" rel="stylesheet" type="text/css "/>
  <link href="dist/css/style.css" rel="stylesheet" type="text/css "/>
  <style>
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
  <body class="bg-bdy">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Home Rental Search</a>
          </div>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="index.php">Home</a></li>
            <li class="active"><a href="log.php">Log</a></li>
          </ul>
        </div>
      </nav>
      <br/>
	  <div class=" container well ">
		<?php
function getlast($filename,$linenum_to_read,$linelength){

   // this function takes 3 arguments;


   if (!$linelength){ $linelength = 600;}
$f = fopen($filename, 'r');
$linenum = filesize($filename)/$linelength;

    for ($i=1; $i<=($linenum-$linenum_to_read);$i++) {
    $data = fread($f,$linelength);
    }
echo "<pre>";       
    for ($j=1; $j<=$linenum_to_read+1;$j++) {
	
	// replacing /r and /n with newline
    echo preg_replace('#(\\\r\\\n|\\\n)#', '<br/>',fread($f,$linelength));
	 //echo fread($f,$linelength);
    }

echo "</pre><hr />";
}

getlast("python\logs\message.log",6,300);
?>
	  </div>

	<script src="dist/js/jquery.js" type="text/javascript"></script>
    <script src="dist/js/script.js" type="text/javascript"></script>
  </body>
</html>

