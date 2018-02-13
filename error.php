<html>
 <head>
  <title>PHP Test</title>
  <script>
function validateForm() {
    var x = document.forms["myForm"]["fname"].value;
    if (x == "") {
        alert("Name must be filled out");
        return false;
    }
}
</script>
 </head>
 <body>
 <!--<?php echo '<p><font size="6"><b>Oops! Something went wrong. Please try again later</b></font></p>'; ?> -->
 <form name="myForm" action="error.php"
onsubmit="return validateForm()" method="post">
Name: <input type="text" name="fname" id="a">
<input type="submit" value="Submit">
</form>

 </body>
</html>
