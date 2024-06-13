<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch destinations from the destinations table
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'summer';

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM destinations"; // Assume table name is 'destinations'
$result = mysqli_query($conn, $sql);

$destinations = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $destinations[] = $row;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hard Rock Treks & Expedition</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
    <link rel="stylesheet" href="destination.css">
    <style>
       
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="hrt.png" alt="HRT Logo" class="logo">
        <a href="dashboard.php">Dashboard</a>
        <a href="calendar.php">Calendar</a>
        <a href="guides.php">Guides</a>
        <a href="guides.php" class="dash">Destination</a>
        <a href="todo.php" >Todo</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="content">
        <button class="destination_add_btn">Add Destination</button>
        <table>
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Destination Name</th>
                    <th>Duration</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                foreach ($destinations as $destination) {
                    echo "<tr>";
                    echo "<td>{$counter}</td>";
                    echo "<td>{$destination['name']}</td>";
                    echo "<td>{$destination['duration']}</td>";
                    echo "<td>{$destination['grade']}</td>";
                    echo "</tr>";
                    $counter++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="destinationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Destination</h2>
            <form action="add_destination.php" method="post" enctype="multipart/form-data">
                <label for="name">Destination Name:</label><br>
                <input type="text" id="name" name="name" required><br>
                <label for="duration">Duration:</label><br>
                <input type="number" id="duration" name="duration" required><br>
                <label for="grade">Grade:</label><br>
                <input type="text" id="grade" name="grade" required><br><br>
                <input type="submit" value="Add Destination">
            </form>
        </div>
    </div>

    <script>
       
        // Open modal
        document.querySelector('.destination_add_btn').addEventListener('click', function() {
            document.getElementById('destinationModal').style.display = 'block';
        });

        // Close modal
        document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('destinationModal').style.display = 'none';
        });

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == document.getElementById('destinationModal')) {
                document.getElementById('destinationModal').style.display = 'none';
            }
        };
    </script>
</body>
</html>
