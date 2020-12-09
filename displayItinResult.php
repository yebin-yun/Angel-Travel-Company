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
  
<hr></br>
<?php
  
	$itinName = $_POST['itinerary'];
	$itinName = mysqli_real_escape_string($conn, $itinName);

        $query = "SELECT itinerary_id FROM Itinerary WHERE itinerary_name=";
        $query .= "'".$itinName."';";
	$result = mysqli_query($conn, $query)
		or die(mysqli_error($conn));

	$row = mysqli_fetch_array($result, MYSQLI_BOTH);
	$itinID = $row[itinerary_id];
	
	echo "<div style='font-size: 25px; font-weight:bold;'>The schedule of the itinerary '".$itinName."'.</div>";
	
	mysqli_free_result($result);
?>

</br><hr>

<?php
$query = "SELECT restaurant_name AS place_name, which_meal, day_num, CONCAT(time_format(enter_time ,'%H:%i'), ' - ', time_format(exit_time ,'%H:%i')) AS time_period, CONCAT(addr, ', ', r.city, ', FL') AS address
FROM SelectedRestaurant sr LEFT JOIN Restaurant r USING(restaurant_id)
WHERE itinerary_id=";
$query .= $itinID . "\nUNION
SELECT attraction_name AS place_name, 'N/A' AS which_meal, day_num, CONCAT(time_format(arrival_time ,'%H:%i'), ' - ', time_format(departure_time ,'%H:%i')) AS time_period, CONCAT(addr, ', ', a.city, ', FL') AS address
FROM SelectedAttraction sa LEFT JOIN Attraction a USING(attraction_id)
WHERE itinerary_id=";
$query .= $itinID . "\nORDER BY day_num, time_period";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    
$current_day = 0;
echo "<table border='1'>";

while($row=mysqli_fetch_array($result, MYSQLI_BOTH)){
	if ($current_day != $row['day_num']) {
		$current_day = $row['day_num'];
		echo "<tr align='left' height='50'>
			<th colspan='3'>Day ".$row[day_num]."</th></tr>";
		echo "<tr align='center' height='50'>
			<th>Activity</th>
			<th>Time</th>
			<th>Address</th>
			</tr>";
	}	
	echo "<tr align='left' height='30'>";
	
	switch ($row[which_meal]) {
		case "B":
			echo "<td width='400'><span style='font-weight:bold'>Restaurant for Breakfast:</span> $row[place_name]</td>";
			break;
		case "L":
			echo "<td width='400'><span style='font-weight:bold'>Restaurant for Lunch:</span> $row[place_name]</td>";
			break;
		case "D":
			echo "<td width='400'><span style='font-weight:bold'>Restaurant for Dinner:</span> $row[place_name]</td>";
			break;
		default:
			echo "<td width='400'><span style='font-weight:bold'>Attraction:</span> $row[place_name]</td>";
			break;
	}

        echo "<td width='120'>$row[time_period]</td>";

        echo "<td width='350'>$row[address]</td>";

        echo "</tr>";
}

echo "</table>";

mysqli_free_result($result);
mysqli_close($conn);
?>

<hr>
<p>
<a href="displayItinResult.txt" >Contents</a>
of the PHP program that created this page.
</body>
</html>

