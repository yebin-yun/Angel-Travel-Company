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
<h3 style="font-size:25px; font-weight:bold;">Modifying the exisitng itinerary</h3>
<hr>

<p style="font-size:18px">
Please select one of the itineraries on the dropdown list and one of the modifying options that you want to apply to the selected itinerary.
<p>

<form action="modifyItinAddDelete.php" method="POST">
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
	mysqli_close($conn);
	?>
</select>

<select style="font-size:15px;" name="modifyoption" required>
	<option value="" disabled selected hidden>---Select Modifying Option---</option>
	<option value = "Add">Add</option>
	<option value = "Delete">Delete</option> 
</select>
<input style="font-size:15px;" type="submit" value="Submit">
<input style="font-size:15px;" type="reset" value="Reset">
</form>
<hr>

</body>
</html>

