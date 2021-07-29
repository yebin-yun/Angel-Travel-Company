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
<h3 style="font-size:25px; font-weight:bold;">Result of adding attraction</h3>
<hr>

<?php
session_start();
$itinName = $_SESSION['itinName'];
$itinID = $_SESSION['itinID'];
$city = $_SESSION['city'];

$attraction = $_POST['attraction'];
mysqli_real_escape_string($conn, $attraction);
$dayNum = $_POST['dayNum'];
mysqli_real_escape_string($conn, $dayNum);
$arrival = $_POST['arrivalTime'];
mysqli_real_escape_string($conn, $arrival);
$departure = $_POST['departureTime'];
mysqli_real_escape_string($conn, $departure);

$query = "SELECT attraction_id FROM Attraction WHERE attraction_name='";
$query .= $attraction."';";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

$row = mysqli_fetch_array($result, MYSQLI_BOTH);
$attrID = $row[attraction_id];

mysqli_free_result($result);

// Check the format of day number typed in the text box
if (!is_numeric($dayNum)){
	echo "<p style='font-size:18px; color:red'> Error: Invalid input of the day number. Check if you typed in the correct format for the day number. Go back to check the format.<p><hr>";
	exit();
}

// Check the format of arrival time typed in the text box
if (!preg_match('/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $arrival)){
	echo "<p style='font-size:18px; color:red'> Error: Invalid input of the the arrival time. Check if you typed in the correct format for the arrival time. Go back to check the format.<p><hr>";
	exit();
}

// Check the format of departure time typed in the text box
if (!preg_match('/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $departure)){
	echo "<p style='font-size:18px; color:red'> Error: Invalid input of the the departure time. Check if you typed in the correct format for the departure time. Go back to check the format.<p><hr>";
	exit();
}

// Check whether the arrival time is before the departure time
if (strtotime($arrival) >= strtotime($departure)) {
	echo "<p style='font-size:18px; color:red'> Error: The arrival time has to be before the departure time.<p><hr>";
	exit();
}

$query = "SELECT arrival_time AS start_time, departure_time AS end_time
	FROM SelectedAttraction
	WHERE itinerary_id=";
$query .= $itinID." AND day_num=";
$query .= $dayNum." UNION
		SELECT enter_time AS start_time, exit_time AS end_time
		FROM SelectedRestaurant
		WHERE itinerary_id=";
$query .= $itinID." AND day_num=";
$query .= $dayNum.";";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

WHILE ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
	// Check the time conflict
	if ((strtotime($row['start_time']) <= strtotime($departure)) && (strtotime($arrival) <= strtotime($row['end_time']))) {
		echo "<p style='font-size:18px; color:red'> Error: There is a conflicting schedule in the itinerary. Check the current itinerary schedule to avoid adding attraction on the time period that is already scheduled for other attraction.</br></br><a style='font-size:18px; font-weight:bold; color:red' href='displayItin.php'>Click here</a>
to see the current itinerary schedule.<p><hr>";
		exit();
	}
}

mysqli_free_result($result);

$query = "INSERT INTO SelectedAttraction (itinerary_id, attraction_id, day_num, arrival_time, departure_time, city)
	VALUES ('";
$query .= $itinID . "', '";
$query .= $attrID . "', '";
$query .= $dayNum . "', '";
$query .= $arrival . "', '";
$query .= $departure . "', '";
$query .= $city . "');";
?>

<?php
if (mysqli_query($conn, $query)) {
	echo "<p style='font-size:18px;'> A new attraction is successfully added on the selected itinerary.<p>";
}
else {
	echo "<p style='font-size:18px; color:red'> Error: ".mysqli_error($conn).".<p>";
}
mysqli_close($conn);
?>
<hr>

</body>
</html>

