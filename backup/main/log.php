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


