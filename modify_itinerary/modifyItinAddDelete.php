<?php

$path = $_GET['path'];
include $path . '../connectionData.php';

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

session_start();
$itinName = $_POST['itinerary'];
$itinName = mysqli_real_escape_string($conn, $itinName);
$_SESSION['itinName'] = $itinName;
$modOption = $_POST['modifyoption'];
$modOption = mysqli_real_escape_string($conn, $modOption);

$query = "SELECT itinerary_id FROM Itinerary WHERE itinerary_name=";
$query .= "'".$itinName."';";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

$row = mysqli_fetch_array($result, MYSQLI_BOTH);
$itinID = $row[itinerary_id];
$_SESSION['itinID'] = $itinID;

mysqli_free_result($result);

$query = "SELECT city_restriction
        FROM Itinerary
        WHERE itinerary_id=";
$query .= $itinID . ";";
$result = mysqli_query($conn, $query) or die (mysql_error($conn));
$row = mysqli_fetch_array($result, MYSQLI_BOTH);
$city = $row['city_restriction'];
$_SESSION['city'] = $city;

mysqli_free_result($result);
 
?>

<html>
<head>
       <title>Angel Travel</title>
</head>

<body bgcolor="white">

<hr>

<?php
if ($modOption == 'Add') {
?>	
	<h3 style="font-size:25px; font-weight:bold;">Modifying the exisitng itinerary</h3>
	<hr>
	<p style="font-size:18px"> Please select one of the attractions on the dropdown list, and then type in the day number, arrival time, and departure time in order.</br>The selected attraction will be added to the selected itinerary, which is '<?php echo $itinName ?>', if there is no time conflict with other attractions in the itinerary.
	<p>
	<p style="font-size:18px; font-weight:bold;"> Format: </br>* The day number has to be an integer.</br>
	    * The arrival time and departure time has to be in 24-hour format with/without leading zeros, 
	and hour and minute has to be seperated by colon (:).
	<p>
	<form action='modifyItinAddResult.php' method='POST'>
	<select style='font-size:15px;' name='attraction' required>
	<option value='' disabled selected hidden>---Select Attraction---</option>

	<?php
	$query = "SELECT attraction_name
		FROM Attraction
		WHERE attraction_name NOT IN (SELECT attraction_name 
					FROM SelectedAttraction LEFT JOIN Attraction USING(attraction_id)
					WHERE itinerary_id=";
	$query .= $itinID . ")";
	if ($city != 'N/A') {
		$query .= " AND city=(SELECT city_restriction
                                        FROM Itinerary
                                        WHERE itinerary_id=";
		$query .= $itinID . ");";
	}
	$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

	WHILE ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
		$attraction = $row[attraction_name];
		echo "<option value='".$attraction."'>".$attraction."</option>";
	}
	
	echo "</select>";
	echo str_repeat('&nbsp;', 4);
	echo "<input style='font-size:15px;' type='text' name='dayNum' placeholder='Enter the day number' required>";
	echo str_repeat('&nbsp;', 4);
	echo "<input style='font-size:15px;' type='text' name='arrivalTime' placeholder='Enter the arrival time' required>";
	echo str_repeat('&nbsp;', 4);
	echo "<input style='font-size:15px;' type='text' name='departureTime' placeholder='Enter the departure time' required>";
	echo str_repeat('&nbsp;', 4);
	echo "<input style='font-size:15px;' type='submit' value='Submit'>";
	echo "<input style='font-size:15px;' type='reset' value='Reset'>";
	echo "</form>";
	
	mysqli_free_result($result);
		
} else {
?>
	<h3 style="font-size:25px; font-weight:bold;">Deleting the attraction from the exisitng itinerary</h3>
	<hr>
	<p style="font-size:18px"> Please select one of the attractions on the dropdown list.</br>
	The selected attraction will be deleted from the selected itinerary, which is '<?php echo $itinName ?>'.
	<p>
	
	<form action='modifyItinDeleteResult.php' method='POST'>
        <select style='font-size:15px;' name='attraction' required>
        <option value='' disabled selected hidden>---Select Attraction---</option>
	
	<?php
	$query = "SELECT attraction_name
                FROM Attraction
                WHERE attraction_name IN (SELECT attraction_name
                                        FROM SelectedAttraction LEFT JOIN Attraction USING(attraction_id)
                                        WHERE itinerary_id=";
        $query .= $itinID . ")";
	$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

        WHILE ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
                $attraction = $row[attraction_name];
                echo "<option value='".$attraction."'>".$attraction."</option>";
        }
	?>
	</select>
	<input style='font-size:15px;' type='submit' value='Submit'>
        <input style='font-size:15px;' type='reset' value='Reset'>
        </form>
	
	<?php 
	mysqli_free_result($result);
	mysqli_close($conn);
}
?>
<hr>

</body>
</html>

