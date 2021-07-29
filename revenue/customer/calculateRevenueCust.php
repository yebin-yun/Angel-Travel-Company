<?php

$path = $_GET['path'];
include $path . '../../connectionData.php';

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MYSQL server.');

?>

<html>
<head>
<title>Angel Travel</title>
</head>

<body bgcolor="white">
<hr></br>

<?php
$firstName = $_POST['firstname'];
$firstName = mysqli_real_escape_string($conn, $firstName);
$lastName = $_POST['lastname'];
$lastName = mysqli_real_escape_string($conn, $lastName);
	
$query = "SELECT cust_id FROM Customer WHERE fname=";
$query .= "'".$firstName."' AND lname=";
$query .= "'".$lastName."';";
$result = mysqli_query($conn, $query) or die (mysqli_error($conn));
	
$row = mysqli_fetch_array($result, MYSQLI_BOTH);
$custID = $row['cust_id'];

echo "<div style='font-size: 25; font-weight:bold;'>The result of calculating the revenue by the customer '".$firstName." ".$lastName."'.</div>";

mysqli_free_result($result);
?>

</br><hr>

<?php
if($row == "") {
	echo "<p style='font-size:18px; color:red'> Error: There is no customer, called ".$firstName." ".$lastName.".</br></br><a style='font-size:18px; font-weight:bold; color:red' href='../../view_table/table.php'>Click here</a>
to see the current customers of the Angel Travel Company. (Select Customer in the dropdown list when you are directed to a different browser.)<p><hr>";
	exit();

}
?>

<?php
$query = "SELECT cust_id, total_agency_cost, total_attraction_ticket_cost, total_restaurant_cost, total_hotel_cost, (total_agency_cost + total_attraction_ticket_cost + total_restaurant_cost + total_hotel_cost) AS total_cost
FROM (SELECT cust_id, SUM(agency_fee_per_day*duration) AS total_agency_cost
	FROM Itinerary JOIN Reservation USING(itinerary_id)
		JOIN (SELECT cust1.cust_id, cust2.fname, cust2.lname, cust2.age
			FROM Customer cust1 JOIN Customer cust2 USING(cust_id)
			UNION
			SELECT cust.cust_id, comp.fname, comp.lname, comp.age
			FROM Customer cust JOIN Companion comp USING(cust_id)) allPeople USING(cust_id)
	WHERE cust_id = ";
$query .= $custID."\n\tGROUP BY cust_id) totalAgencyCost
	JOIN (SELECT cust_id, SUM(attraction_cost) AS total_attraction_ticket_cost
		FROM (SELECT cust_id, CASE 
					WHEN (age <= 15 AND age >= 1) THEN child_cost  
					WHEN (age <= 60 AND age >= 16) THEN adult_cost 
					WHEN (age > 60) THEN senior_cost 
					ELSE 0
					END AS attraction_cost
			FROM Itinerary JOIN (SELECT cust_id, itinerary_id, fname, lname, age
						FROM Reservation JOIN (SELECT cust1.cust_id, cust1.fname AS cust_fname, cust1.lname AS cust_lname, cust2.fname, cust2.lname, cust2.age
						FROM Customer cust1 JOIN Customer cust2 USING(cust_id)
						UNION
						SELECT cust.cust_id, cust.fname AS cust_fname, cust.lname AS cust_lname, comp.fname, comp.lname, comp.age
						FROM Customer cust JOIN Companion comp USING(cust_id)) allPeople USING(cust_id)
			WHERE cust_id = ";
$query .= $custID.") reservedPeople USING(itinerary_id)
		JOIN SelectedAttraction USING(itinerary_id)
		JOIN Attraction USING(attraction_id)) costByAge
		GROUP BY cust_id) totalAttractionCost USING(cust_id)
	JOIN(SELECT cust_id, SUM(cost_per_person) AS total_restaurant_cost
		FROM Itinerary JOIN Reservation USING(itinerary_id)
			JOIN (SELECT cust1.cust_id, cust2.fname, cust2.lname, cust2.age
				FROM Customer cust1 JOIN Customer cust2 USING(cust_id)
				UNION
				SELECT cust.cust_id, comp.fname, comp.lname, comp.age
				FROM Customer cust JOIN Companion comp USING(cust_id)) allPeople USING(cust_id)
			JOIN SelectedRestaurant USING(itinerary_id)
			JOIN Restaurant USING(restaurant_id)
		WHERE cust_id = ";
$query .= $custID."\n\t\tGROUP BY cust_id) totalRestaurantCost USING(cust_id)
	JOIN (SELECT cust_id, SUM(cost_per_room*num_room) AS total_hotel_cost
		FROM Reservation JOIN Customer USING(cust_id)
			JOIN SelectedHotel USING(reservation_id)
			JOIN Hotel USING(hotel_id, room_type)
		WHERE cust_id = ";
$query .= $custID."\n\t\tGROUP BY cust_id) totalHotelCost USING(cust_id)";
?>

<?php
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

echo "<table border='1'>

<tr>

<th>Agency Processing Fees</th>

<th>Attraction Ticket Fees</th>

<th>Restaurant Fees</th>

<th>Hotel Fees</th>

<th>Total Revenues</th>

</tr>";
while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){

	echo "<tr>";

	echo "<td>"."$".$row[total_agency_cost]."</td>";

	echo "<td>"."$".$row[total_attraction_ticket_cost]."</td>";

	echo "<td>"."$".$row[total_restaurant_cost]."</td>";

	echo "<td>"."$".$row[total_hotel_cost]."</td>";

	echo "<td>"."$".$row[total_cost]."</td>";

	echo "</tr>";
}
echo "</table>";

mysqli_free_result($result);
mysqli_close($conn);
?>
<hr>

</body>
</html>
