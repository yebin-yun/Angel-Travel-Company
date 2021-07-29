<?php

$path = $_GET['path'];
include $path . '../../connectionData.php';

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');

?>

<html>
<head>
  <title>Angel Travel</title>
</head>
    
<body bgcolor="white">
  
<hr></br>
<?php
  
$itinName = $_POST['itindropdown'];
$itinName = mysqli_real_escape_string($conn, $itinName);

$query = "SELECT itinerary_id FROM Itinerary WHERE itinerary_name=";
$query .= "'".$itinName."';";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

$row = mysqli_fetch_array($result, MYSQLI_BOTH);
$itinID = $row[itinerary_id];
	
echo "<div style='font-size: 25px; font-weight:bold;'>The result of calculating the revenues from the itinerary '".$itinName."'.</div>";
	
mysqli_free_result($result);
?>

</br><hr>

<?php
$query = "SELECT itinerary_id, total_agency_cost, total_attraction_ticket_cost, total_restaurant_cost, (total_agency_cost + total_attraction_ticket_cost + total_restaurant_cost) AS total_cost
FROM (SELECT itinerary_id, duration*agency_fee_per_day AS total_agency_cost 
	FROM Itinerary
	WHERE itinerary_id = ";
$query .= $itinID.") totalAgencyCost
			JOIN (SELECT itinerary_id, (childCount*child_cost + adultCount*adult_cost + seniorCount*senior_cost) AS total_attraction_ticket_cost
				FROM (SELECT itinerary_id, COUNT(CASE WHEN (age <= 15 AND age >= 1) THEN cust_id ELSE null END) AS childCount,
					COUNT(CASE WHEN (age <= 60 AND age >= 16) THEN cust_id ELSE null END) AS adultCount,
					COUNT(CASE WHEN (age > 60) THEN cust_id ELSE null END) AS seniorCount
					FROM Reservation JOIN (SELECT cust1.cust_id, cust1.fname AS cust_fname, cust1.lname AS cust_lname, cust2.fname, cust2.lname, cust2.age 
								FROM Customer cust1 JOIN Customer cust2 USING(cust_id)
								UNION
								SELECT cust.cust_id, cust.fname AS cust_fname, cust.lname AS cust_lname, comp.fname, comp.lname, comp.age
								FROM Customer cust JOIN Companion comp USING(cust_id)) allPeople USING(cust_id)
					WHERE itinerary_id = ";
$query .= $itinID."\n\t\t\t\t\tGROUP BY itinerary_id) countByAge 
				JOIN (SELECT itinerary_id, SUM(child_cost) AS child_cost, SUM(adult_cost) AS adult_cost, SUM(senior_cost) AS senior_cost
					FROM SelectedAttraction LEFT JOIN Attraction USING(attraction_id)
					WHERE itinerary_id = ";
$query .= $itinID."\n\t\t\t\t\tGROUP BY itinerary_id) itinCost USING(itinerary_id)) totalAttractionTicketCost USING(itinerary_id)
			JOIN (SELECT itinerary_id, SUM(food_expenses) AS total_restaurant_cost
				FROM (SELECT itinerary_id, (numPeopleReserved*foodExpenses) AS food_expenses
				FROM Reservation JOIN (SELECT cust_id, (COUNT(cust_id) + 1) AS numPeopleReserved
							FROM Reservation r LEFT JOIN Customer cust USING(cust_id)
								LEFT JOIN Companion comp USING(cust_id)
							GROUP BY cust_id) countPeople USING (cust_id)
					JOIN (SELECT itinerary_id, SUM(cost_per_person) AS foodExpenses
						FROM SelectedRestaurant LEFT JOIN Restaurant USING(restaurant_id)
						WHERE itinerary_id = ";
$query .= $itinID."\n\t\t\t\t\t\tGROUP BY itinerary_id) restaurantCost USING(itinerary_id)
			WHERE itinerary_id = ";
$query .= $itinID.") costPerCust
			GROUP BY itinerary_id) totalRestaurantCost USING(itinerary_id)";
?>

<?php
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

echo "<table border='1'>

<tr>

<th>Agency Processing Fees</th>

<th>Attraction Ticket Fees</th>

<th>Restaurant Fees</th>

<th>Total Revenues</th>

</tr>";

while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){

	echo "<tr>";

	echo "<td>"."$".$row[total_agency_cost]."</td>";

	echo "<td>"."$".$row[total_attraction_ticket_cost]."</td>";

	echo "<td>"."$".$row[total_restaurant_cost]."</td>";

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
