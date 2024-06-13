<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if(isset($_POST['submit'])){
    // Code to add a new event
    // Establish database connection
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'summer';
    $conn = mysqli_connect($host, $user, $pass, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $destination=$_POST["destination"];
    $guests=$_POST["guests"];
    $guide=$_POST["guide"];
    $guide_name=$_POST["guide_name"];
    $porter=$_POST["porter"];
    $start=$_POST["start"];
    $end=$_POST["end"];

       // Server-side validation
       $today = date("Y-m-d H:i");
       if ($start < $today) {
           echo '<script>alert("Start date cannot be before today\'s date."); window.location.href="calendar.php";</script>';
       } elseif ($end < $start) {
           echo '<script>alert("End date cannot be before start date."); window.location.href="calendar.php";</script>';
       } elseif ($guests < 1 ) {
           echo '<script>alert("cannot continue due to no guests"); window.location.href="calendar.php";</script>';
       } 
       elseif ( $guide < 1 ) {
        echo '<script>alert("There must be 1 guide"); window.location.href="calendar.php";</script>';
    }elseif($porter<0){
        echo '<script>alert("no negative value"); window.location.href="calendar.php";</script>';
    }
        else {
        $sql = "INSERT INTO calendar (destination, guests, guide, guide_name, porter, start, end) VALUES ('$destination', '$guests', '$guide', '$guide_name', '$porter', '$start', '$end')";

           if (mysqli_query($conn, $sql)) {
               echo '<script>alert("Event added successfully!"); window.location.href="calendar.php";</script>';
           } else {
               echo "Error adding record: " . mysqli_error($conn);
           }
       }
   
       mysqli_close($conn);
   }
//fetch events from database;
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'summer';

$events = array(); // Define an empty array to hold events
$conn = mysqli_connect($host, $user, $pass, $dbname);
$sql = "SELECT * FROM calendar";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = array(
            'id' => $row['ID'],
            'title' => $row['destination'],
            'start' => $row['start'],
            'end' => $row['end'],
            'guests' => $row['guests'],
            'guide_name' => $row['guide_name'],
            'guide' => $row['guide'],
            'porter' => $row['porter'],

        );
    }
}
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'summer';
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch guide names from the database
$guides_query = "SELECT name FROM users_guide";
$guides_result = mysqli_query($conn, $guides_query);
$guides = array();
while ($row = mysqli_fetch_assoc($guides_result)) {
    $guides[] = $row['name'];
}

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'summer';
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch guide names from the database
$destination_query = "SELECT name FROM destinations";
$destination_result = mysqli_query($conn, $destination_query);
$destination = array();
while ($row = mysqli_fetch_assoc($destination_result)) {
    $destination[] = $row['name'];
}
mysqli_close($conn);


?>
<!DOCTYPE html>
<html>
<head>
    <title>Hard Rock Treks & Expedition</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
    <link rel="stylesheet" href="calendar.css">
</head>
<style>
.update_btn{
background-color:#ebe9ef !important;
color:black;
}
.update_btn:hover{
background-color: blue !important;
color:white;
}
.delete_btn{
background-color:#ebe9ef !important;
color:black;
}
.delete_btn:hover{
background-color: red !important;
color:white;
}
.logout{
margin-top:310px;
}

</style>


<body>
    <!-- Sidebar -->
    <div class="sidebar">
    <img src="hrt.png" alt="HRT Logo" class="logo">
    <a href="dashboard.php" class="dash">Dashboard</a>
        <a href="calendar.php" class="cal">Calendar</a>
        <a href="guides.php" >Guides</a>
        <a href="destination.php" >Destination</a>
        <a href="todo.php" >Todo</a>
        <a href="logout.php" class="logout">Logout</a>
        
    </div>

    <!-- Main content -->
    <div class="content">
    <button class="addevent-button" onclick="openModal()">Add Event</button>
        <div id="calendar"></div>
    </div>

    <!-- Modal for adding event -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Event no: <?php echo count($events) + 1; ?></h3>
            <form id="eventForm" action="#" method="POST" onsubmit="return validateForm()">

                <label for="destination">Destination:</label>
<select name="destination" required>
    <?php foreach ($destination as $guide) { ?>
        <option value="<?php echo $guide; ?>"><?php echo $guide; ?></option>
    <?php } ?>
</select>
                   
                
                <label for="guests">Total Guests:</label>
                <input type="number" name="guests" min="0" required><br><br>
                <div class="guide-porter">
                <label for="guide">Guide Name:</label><br>
<select name="guide_name" required>
    <?php foreach ($guides as $guide) { ?>
        <option value="<?php echo $guide; ?>"><?php echo $guide; ?></option>
    <?php } ?>
</select><br>
                    <div class="guide">
                        <label for="guide">Total Guide:</label>
                        <input type="number" name="guide" class="small-input" min="0" required><br>
                    </div>
                    <div class="porter">
                        <label for="porter">Porter:</label>
                        <input type="number" name="porter" class="small-input" min="0" required>
                    </div>
                </div>
                <label for="start">Start Date:</label>
                <input type="datetime-local" id="start" name="start" min="" required><br>
                <label for="end">End Date:</label>
                <input type="datetime-local" id="end" name="end" required><br><br>
                <input type="submit" name="submit" value="Save">
            </form>
        </div>
    </div>

    <!-- Modal for viewing and updating event details -->
    <div id="myModal2" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal2()">&times;</span>
            <h3>Event Details</h3>
            <table id="eventDetails" class="table">
                <tr>
                    <td><strong>ID:</strong></td>
                    <td id="ID"></td>
                </tr>
                <tr>
                    <td><strong>Destination:</strong></td>
                    <td id="destination2"></td>
                </tr>
                <tr>
                    <td><strong>Start Date:</strong></td>
                    <td id="start2"></td>
                </tr>
                <tr>
                    <td><strong>End Date:</strong></td>
                    <td id="end2"></td>
                </tr>
                <tr>
                    <td><strong>Total Guests:</strong></td>
                    <td id="guests2"></td>
                </tr>
                <tr>
                    <td><strong>Guide Name:</strong></td>
                    <td id="guide_name2"></td>
                </tr>
                <tr>
                    <td><strong>Total Guide:</strong></td>
                    <td id="guide2"></td>
                </tr>
                <tr>
                    <td><strong>Total porter:</strong></td>
                    <td id="porter2"></td>
                </tr>
            </table>
            <div>
                <button id="updateButton" class="update_btn">Update</button>
                <button id="deleteButton" class="delete_btn">Delete</button>
            </div>
            <!-- Hidden update form -->
            <form id="updateForm" style="display:none;" action="update_event.php" method="POST">
                <input type="hidden" id="updateID" name="id">
                <label for="title">Destination:</label><br>
                <select id="updateDestination"name="destination" required>
    <?php foreach ($destination as $guide) { ?>
        <option value="<?php echo $guide; ?>"><?php echo $guide; ?></option>
    <?php } ?>
</select>
                <label for="guests">Total Guests:</label><br>
                <input type="number" id="updateGuests" name="guests" min="1" required><br>
                <label for="guide">Guide Name:</label><br>
<select id="updateGuide_name"name="guide_name" required>
    <?php foreach ($guides as $guide) { ?>
        <option value="<?php echo $guide; ?>"><?php echo $guide; ?></option>
    <?php } ?>
</select><br>
                <div class="guide-porter">
                    <div class="guide">
                        <label for="guide">Total Guide:</label><br>
                        <input type="number" id="updateGuide" name="guide" class="small-input" min="0" required><br>
                    </div>
                    <div class="porter">
                        <label for="porter">Porter:</label><br>
                        <input type="number" id="updatePorter" name="porter" class="small-input" min="0" required><br>
                    </div>
                </div>
                <label for="start">Start Date:</label><br>
                <input type="datetime-local" id="updateStart" name="start" required><br>
                <label for="end">End Date:</label><br>
                <input type="datetime-local" id="updateEnd" name="end" required><br><br>
                <input type="submit" value="Update">
            </form>
            <!-- Hidden delete form -->
            <form id="deleteForm" style="display:none;" action="delete_event.php" method="POST">
                <input type="hidden" id="deleteID" name="id">
                <input type="submit" value="Delete">
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script>
        // Open the modal when the Add Event button is clicked
        function openModal() {
            document.getElementById("myModal").style.display = "block";
            setMinDate();
        }

        // Close the modal when the close button or outside the modal is clicked
        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        // Open the modal for viewing event details
        function openModal2() {
            document.getElementById("myModal2").style.display = "block";
        }

        // Close the modal for viewing event details
        function closeModal2() {
            document.getElementById("myModal2").style.display = "none";
        }

        function setMinDate() {
            var today = new Date();
            var day = ("0" + today.getDate()).slice(-2);
            var month = ("0" + (today.getMonth() + 1)).slice(-2);
            var year = today.getFullYear();
            var hours = ("0" + today.getHours()).slice(-2);
            var minutes = ("0" + today.getMinutes()).slice(-2);
            var minDateTime = year + "-" + month + "-" + day + "T" + hours + ":" + minutes;
            document.getElementById("start").min = minDateTime;
            document.getElementById("end").min = minDateTime;
        }

        function validateForm() {
            var start = new Date(document.getElementById("start").value);
            var end = new Date(document.getElementById("end").value);
            var today = new Date();
            
            if (start < today) {
                alert("Start date cannot be before today's date.");
                return false;
            }

            if (end < start) {
                alert("End date cannot be before start date.");
                return false;
            }

            var guests = document.getElementsByName("guests")[0].value;
            var guide = document.getElementsByName("guide")[0].value;
            var porter = document.getElementsByName("porter")[0].value;

            if (guests < 1) {
                alert("Guest value cannot be less than one ");
                return false;
            }
            if ( guide < 1 ) {
                alert("guide must be present");
                return false;
            }
            if (porter<0) {
                alert("no negative values.");
                return false;
            }
            return true;
        }

        $(document).ready(function(){
            $('#calendar').fullCalendar({
                selectable:true,
                selectHelper:true,
                events: <?php echo json_encode($events); ?>,
                header:{
                    left:'month,agendaWeek,agendaDay,list',
                    center:'title',
                    right:'prev,today,next',
                },
                buttonText:{
                    today:'Today',
                    month:'Month',
                    week:'Week',
                    list:'List',
                    day:'Day'
                },
                dayRender: function(date, cell) {
                    var today = $.fullCalendar.moment();
                    if (date.isSame(today, 'day')) {
                        cell.css("background", "lightgrey");
                    }
                },
                eventClick: function(event) {
                    $('#ID').text(event.id);
                    $('#destination2').text(event.title);
                    $('#start2').text(event.start.format('YYYY-MM-DD HH:mm'));
                    $('#end2').text(event.end.format('YYYY-MM-DD HH:mm'));
                    $('#guests2').text(event.guests);
                    $('#guide2').text(event.guide);
                    $('#guide_name2').text(event.guide_name);
                    $('#porter2').text(event.porter);
                    openModal2();
                }
            });

            // Update button click handler
            $('#updateButton').click(function() {
                // Fill the update form with current event data
                $('#updateID').val($('#ID').text());
                $('#updateDestination').val($('#destination2').text());
                $('#updateGuests').val($('#guests2').text());
                $('#updateGuide_name').val($('#guide_name2').text());
                $('#updateGuide').val($('#guide2').text());
                $('#updatePorter').val($('#porter2').text());
                $('#updateStart').val($('#start2').text().replace(' ', 'T'));
                $('#updateEnd').val($('#end2').text().replace(' ', 'T'));

                // Show the update form
                $('#updateForm').show();
            });

            // Delete button click handler
            $('#deleteButton').click(function() {
                // Show confirmation dialog
                var confirmDelete = confirm("Do you want to delete the event: " + $('#destination2').text() + "?");
                if (confirmDelete) {
                    // Fill the delete form with the event ID and submit it
                    $('#deleteID').val($('#ID').text());
                    $('#deleteForm').submit();
                }
            });
        });
    </script>
</body>
</html>