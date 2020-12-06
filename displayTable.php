<?php

include('connectionData.txt');

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

$table = $_POST['table'];
$table = mysqli_real_escape_string($conn, $table);
?>

<html>
<head>
  <title>Final Project - Angel Travel</title>
</head>
    
<body bgcolor="white">
  
<hr>
<h3 style="font-size:25px; font-weight:bold;">Display the contents in the table '<?php echo $table ?>'.</h3>

<hr>

<?php
$query = "SELECT * FROM ";
$query .= $table . ";";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
echo "<br>";
echo "<table border='1'>";
$first_row = true;
while($row = mysqli_fetch_assoc($result)){
	if ($first_row){
		$first_row = false;
		echo "<tr>";
		foreach ($row as $key => $field) {
			echo "<th>" . htmlspecialchars($key) . "</th>";
		}
	}
	echo "</tr>";
	foreach ($row as $key => $field) {
		echo "<td>" . htmlspecialchars($field) . "</td>";
	}
	echo "</tr>";
}
echo "</table>";

mysqli_free_result($result);
mysqli_close($conn);
?>

<hr>
<p>
<a href="displayTable.txt" >Contents</a>
of the PHP program that created this page.
</body>
</html>
