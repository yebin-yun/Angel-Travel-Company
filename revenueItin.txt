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
<h3 style="font-size:25px; font-weight:bold;">Calculating the revenues by itinerary</h3>

<hr>

<p style="font-size:18px">
Please select one of the itineraries on the dropdown list.</br>
The revenue will be calculated based on the selected itinerary.
<p>

<form action="calculateRevenueItin.php" method="POST">
<select style="font-size:15px;" name="itindropdown" required>
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
	?>
	</select>
	<input style="font-size:15px;" type="submit" value="Submit">
	<input style="font-size:15px;" type="reset" value="Reset">
</form>
<hr>

<p>
<a href="revenueItin.txt">Contents</a>
of the PHP program that created this page.

</body>
</html>


