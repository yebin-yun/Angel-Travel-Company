# Angel Travel Company
> **Author: Ellie Yun, yyun.ellie@gmail.com**

> project URL: <http://ix.cs.uoregon.edu/~yyun/cis451/project/AngelTravel.html>

## Overview

Angel Travel Company offers its customers travel arrangements exclusively in Florida.

Angel has pre-set itineraries that customers can choose from and offers those itineraries

featuring theme parks, restaurants, and attractions. The itineraries do not include hotels but

Angel does book hotels for customers according to the customer’s preference and request. All

fees for all tickets, restaurants, hotels, and Angel processing fees are due upon reservation.

Attractions include Disneyworld parks, Universal Studios parks, and related parks in Orlando,

museums and theaters in Jacksonville, historic sites in St. Augustine, among others. Restaurants

are included in itineraries in the spirit of offering variety in types of food. While Angel can offer

those features to its customers, the database is designed to be used by Angel employees. For

example, customers cannot make reservations or check prices with this project, but it is

intended for employees to use the data to view previous or existing customers’ reservations,

offered attractions, visited restaurants and information about those among others.

\2. ***Logical Design***





Figure 1: ER Diagram displaying the database structure of ‘mydb’ for the Angel Travel Company.

\3. ***Physical Design***

**Table**

**Primary Key(s)**

**Foreign Key(s)**

**Additional**

**Attributes**

Customer

cust\_id

None

fname, lname, age

lname, age

Companion

Reservation

fname, cust\_id

reservation\_id

cust\_id

cust\_id,

reserved\_date, itinerary\_date

itinerary\_id





Hotel

hotel\_id,

None

hotel\_name, cost\_per\_room,

addr, city

room\_type

SelectedHotel

reservation\_id,

hotel\_id,

reservation\_id,

hotel\_id,

num\_room

room\_type,

day\_num

room\_type

Itinerary

itinerary\_id

None

itinerary\_name, duration,

agency\_fee\_per\_day,

city\_restriction

SelectedAttraction Itinerary\_id,

attraction\_id

Itinerary\_id,

attraction\_id

Day\_num, arrival\_time,

departure\_time, city

Attraction

attraction\_id

none

attraction\_name, child\_cost,

adult\_cost, senior\_cost, addr,

city

Restaurant

restaurant\_id

none

restaurant\_name,

cost\_per\_person, addr, city,

foodType

SelectedRestaurant Itinerary\_id,

restaurant\_id,

Itinerary\_id,

which\_meal, enter\_time,

exit\_time

restaurant\_id





day\_num

The restaurants and attractions are real; restaurants were researched based on area

and type of food. The addresses of attractions and restaurants included in the tables are the

true addresses. The prices of hotels are also about the average price, with possible exceptions

due to COVID-19 of closures or price drops. The addresses were considered upon arranging

itineraries and scheduling enough time between attractions or restaurants to reach the site,

with exceptions of overlaps when a restaurant is within a theme park.

*4. **List of Applications***

Applications are to calculate revenue, to modify an existing itinerary, and to display the

itinerary schedule. Calculating revenue is broken down into two categories: by customer and by

itinerary. Modifying an existing itinerary is broken into two categories: adding or deleting.

Displaying is demonstrating all itineraries, their attractions, and their restaurants.

Calculating revenue by itinerary helps employees to know which itinerary sets are most

popular and lucrative. Results are shown in a table below the query, which break the itinerary

revenue down into agency processing fees, attraction ticket fees, restaurant fees, and total

revenue per selected itinerary. This information helps the company decide how to modify

offered itineraries especially in accordance with seasons, holidays, etc. Tables affected are

Itinerary, SelectedAttraction, Attraction, SelectedRestaurant, Restaurant, Companion, and

Customer.





Calculating revenue by customer enables employees’ access to the total spending on all

reservations, including hotels, per customer overall. For customers who book multiple

reservations, all those costs’ of each reservation are summed together. Most customers have

registered companions, so all those companions are assumed to join the primary customer on

every reserved itinerary. The result is broken down into restaurant fees, hotel costs, attraction

ticket fees, and agency processing fees, and totaled to give the total the customer has ever

spent overall with Angel, including costs for all his or her registered companions on every

reservation. While hotel room sizes accommodate the customer and all companions, it is

possible that more than one adult might decide to share one hotel room with other adults

among his companions during a trip to save on costs. Tables affected are Itinerary,

SelectedAttraction, Attraction, SelectedHotel, Hotel, SelectedRestaurant, Restaurant,

Companion, and Customer.

An existing itinerary can be modified to add not-yet-existent attractions or remove any

attraction. While the editing is done by an employee, a customer may request an additional

attraction be added into his or her itinerary. Other reasons an itinerary may be edited include

attraction proving unprofitable, attraction closed, area unsafe or inaccessible, attraction

recently opened, attraction’s significant gain or loss in popularity due to holiday or season.

If a user tries to add an attraction, they must first select the itinerary to add to in the

dropdown list. The dropdown list will show only the attractions that are not included in the

selected itinerary. Also, the only options in the dropdown list will be in the same city as that

itinerary if applicable. If the tour does not stay in the same city, for example the University





Tour, then the city of the attraction to be added, need not be in the same city as any other on

the tour. The information about the city restriction is specified in the Itinerary table. If the

attractions have to be in a specific city, the attribute ‘city\_restriction’ will have the city name. If

it’s not the case, then it will have N/A. If a user tries to add an attraction, they must pass

validity checks for whatever they type in the text box. Day number has to be int, arrival time

and departure time has to be in 24-hour format and hours and minutes separated by colon.

Also, the arrival time has to be before departure time. A further validity check is that there

must be enough time in the selected itinerary at the time-slot on the day that the user tries to

add the attraction. If any of those validity checks failed, it will print out the error message on

the browser and terminate executing the php file. Tables affected are Itinerary, Attraction,

SelectedAttraction, Restaurant, SelectedRestaurant.

If a user tries to delete an attraction, they must similarly enter the itinerary to edit. The

only choices a user will have to choose from for deletion are attractions that exist in the

selected itineraries. Having only relevant options to choose from ensures that the user makes a

valid selection to delete. Tables affected are Itinerary, Attraction, SelectedAttraction,

Restaurant, SelectedRestaurant.

Displaying the itinerary schedule shows all itineraries, their attractions, their

restaurants, and the time window planned to enter and exit each, with exceptions of overlap

for restaurants within parks that don’t require leaving the park to visit the restaurant. These are

displayed aesthetically in tables based on itineraries. Hotels are not included because hotels are





selected separately by customers. Tables affected are Itinerary, SelectedAttraction, Attraction,

SelectedRestaurant, Restaurant.

\5. ***User's guide***

On the main page <ix.cs.uoregon.edu/~yyun/cis451/project/AngelTravel.html>, there

are three options to “Click here.” The first one is for seeing revenues by itinerary or customer.

Then the next page <ix.cs.uoregon.edu/~yyun/cis451/project/revenue.html> breaks out into

“Click here to see the revenues by customer” and “click here to see the revenues by itinerary”.

First, by customer, the next page leads to a textbox where the user can type in first name, last

name, then press Submit. When the user presses submit, then the query and table will show

with revenue calculated. Second, by itinerary, the next page will lead to a drop-down list. That

drop-down list is dynamic so if more itineraries are added to the database, those new

itineraries will be added to the drop-down list. If the user selects an itinerary and presses

Submit, the next page will show the query and table for calculated revenue by itinerary.

The second option to “Click here” on the main page is for modifying existing itineraries.

The next page gives a dynamic dropdown list of the itineraries and an option to add or delete in

a dropdown list. Once a selection is made in those lists, then please press Submit.

If the user chooses “Add” as the modifying option, then presses Submit, he will see

more dropdowns and text boxes on the next page. The first dropdown list contains the

attractions that are not already in the itinerary and that are in the same city as the tour if

applicable, so the user should select one of those that are reasonable for the itinerary’s

location. The next textboxes are for the day number within the tour, arrival time at the new





attraction, and departure time. There are formatting instructions for how to fill in those boxes,

which if not followed, will produce error messages. If the entry conflicts with the tour, the user

will see a descriptive error message. Also a link to the itinerary schedule will be provided to

help users find a time that suits the attraction to be added. If the attraction is reasonable to add

at the arrival time entered without pre-arranged conflict on the day number entered, the

attraction will be added to the itinerary.

If the user chooses “Delete” as the modifying option, then presses Submit, then the next

page will have a dropdown list with only attractions that appear in the selected itinerary.

The third option to “Click here” on the main page is for displaying existing itineraries’

schedules. First a dynamic dropdown list appears, from which the user can select an itinerary to

display. If the user selects an itinerary and presses Submit, the next page will show a table of

the itinerary schedule, ordered by time. This table has a section for each day of the itinerary

with the Activities (restaurant or attraction), times, and addresses of those activities.

*6. **Contents of tables***

The contents of tables are accessible by a link on the main page. On the URL

<ix.cs.uoregon.edu/~yyun/cis451/project/AngelTravel.html>, the 5th link is called Contents of

tables. The entire URL to the contents of tables is

<ix.cs.uoregon.edu/~yyun/cis451/project/table.php>. From this page, one can select a table

from the database “mydb” to display its contents on the <table.php> file. After selecting a table

from the drop-down list, please press “submit” to see all the rows of that table.





*7. **Implementation code***

The code is accessible on every page at the bottom of the page in a link for the

“Contents of this page.” Those links for “Contents” show the implementation.

*8. **Conclusion***

We have created a database to be used by Angel Travel that stores all the data about

reservations for itineraries of traveling tours within Florida. The five main functionalities are to

view revenue earned based on customer, view revenue earned by itinerary, add to an itinerary

(with validation checks), delete from an itinerary (with validation restrictions), and to display all

itineraries available. As is, all companions registered with a customer are assumed to travel

with that customer in all his reservations. We would enable different companions to be added

to a different customer’s trip. We would also add hotels to itineraries, implying that Angel

contracts with hotels to reserve a set number of rooms every night to make those available to

Angel customers. All attractions, hotels, restaurants listed are only in Florida. While Florida has

great theme parks, the services Angel offers could expand around more attractions across

Florida, the southeast, nationwide, or even worldwide. Outside of COVID-19 airplane

groundings, we could also include flights to offer customers transportation to their sites of

interest outside their home regions.


