<?php
//$data = array('as', 'df', 'gh');
$location="panathur";

$cmdText = "C:\Users\Saurabh\AppData\Local\Programs\Python\Python36\python.exe 99_acres.py ".$location;
echo "Running command: " . $cmdText . "\n";
$result = shell_exec($cmdText);

echo "Got the following result:\n";
echo $result;

$resultData = json_decode($result, true);

echo "The result was transformed into:\n";
var_dump($resultData);
?>


