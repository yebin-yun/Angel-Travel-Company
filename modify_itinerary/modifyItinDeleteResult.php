<?php

$path = $_GET['path'];
include $path . '../connectionData.php';

$conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');

?>

<html>
<head>
       <title>Angel Travel</title>
</head>

<body bgcolor="white">

<hr>
<h3 style="font-size:25px; font-weight:bold;">Result of deleting attraction</h3>
<hr>

<?php
session_start();
$itinID = $_SESSION['itinID'];

$attraction = $_POST['attraction'];
mysqli_real_escape_string($conn, $attraction);

$query = "SELECT attraction_id FROM Attraction WHERE attraction_name='";
$query .= $attraction."';";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

$row = mysqli_fetch_array($result, MYSQLI_BOTH);
$attrID = $row[attraction_id];

mysqli_free_result($result);

$query = "DELETE FROM SelectedAttraction WHERE (itinerary_id = '";
$query .= $itinID . "') AND (attraction_id= '";
$query .= $attrID . "');";

mysqli_free_result($result);
?>

<p style='font-size:20px; font-weight:bold;'>Query: </br><p>

<?php echo $query; ?>
<hr>

<p style='font-size:20px; font-weight:bold;'>Query Result: </br><p>

<?php
if (mysqli_query($conn, $query)) {
	echo "<p style='font-size:18px;'> A new attraction is successfully deleted from the selected itinerary. <p>";
}
else {
	echo "<p style='font-size:18px; color:red'> Error: ".mysqli_error($conn).".<p>";
}
mysqli_close($conn);
?>
<hr>

</body>
</html>

