<?php

include('connectionData.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

?>

<html>
<head>
    <title>Final Project - Angel Travel</title>
</head>
    
<body bgcolor="white">

<hr>
<h3 style="font-size:25px; font-weight:bold;">Contents of Tables</h3>

<hr>

<p style="font-size:18px">
Please select one of the tables on the dropdown list.</br>
The contents on the selected table will be displayed.
<p>

<form action="displayTable.php" method="POST">
<select style="font-size:15px;" name="table" required>
	<option value="" disabled selected hidden>---Select Table---</option>
	<?php
        $query = "SHOW TABLES";
        $result=mysqli_query($conn, $query) or die (mysql_error($conn));

	while ($row=mysqli_fetch_array($result, MYSQLI_BOTH)) {
                $table=$row[0];
	?>
		<option value="<?php echo $table; ?>"><?php echo $table; ?></option>
	<?php
	}
	mysqli_free_result($result);
	mysqli_close($conn);
	?>
	</select>
	<input style="font-size:15px;" type="submit" value="Submit">
	<input style="font-size:15px;" type="reset" value="Reset">
</form>
<hr>

<p>
<a href="table.txt">Contents</a>
of the PHP program that created this page.

</body>
</html>



