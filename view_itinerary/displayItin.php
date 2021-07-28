<?php

$path = $_GET['path'];
include $path . '../connectionData.php';

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

?>

<html>
<head>
       <title>Angel Travel</title>
</head>

<body bgcolor="white">

<hr>
<h3 style="font-size:25px; font-weight:bold;">Viewing the itinerary</h3>

<hr>

<p style="font-size:18px"> Please select one of the itineraries on the dropdown list.<br>
You will be able to view the attractions and restaurants included in the selected itinerary ordered by the scheduled time.
<p>

<form action="displayItinResult.php" method="POST">
<select style="font-size:15px;" name="itinerary" required>
	<option value="" disabled selected hidden>---Select Itinerary---</option>
	<?php
        $query = "SELECT * FROM Itinerary";
        $result=mysqli_query($conn, $query) or die (mysql_error($conn));

	while ($row=mysqli_fetch_array($result, MYSQLI_BOTH)) {
                $itinName=$row[itinerary_name];
	?>
		<option value="<?php echo $itinName; ?>"><?php echo $itinName; ?></option>
	<?php
	}
	mysqli_free_result($result);
	?>
	</select>
	<input style="font-size:15px;" type="submit" value="Submit">
	<input style="font-size:15px;" type="reset" value="Reset">
</form>
<hr>

</body>
</html>

