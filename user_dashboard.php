<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

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

// Fetch guide name
$guide_query = "SELECT name FROM users_guide WHERE username = '$username'";
$guide_result = mysqli_query($conn, $guide_query);
$guide_row = mysqli_fetch_assoc($guide_result);
$guide_name = $guide_row['name'];

// Fetch events assigned to the guide
$events_query = "SELECT * FROM calendar WHERE guide_name = '$guide_name'";
$events_result = mysqli_query($conn, $events_query);
$events = array();

// Prepare data for notification of events 2 days later
$twoDaysLater = date('Y-m-d', strtotime('+2 days'));
$hasEventsTwoDaysLater = false;

if (mysqli_num_rows($events_result) > 0) {
    while ($row = mysqli_fetch_assoc($events_result)) {
        $events[] = array(
            'id' => $row['ID'],
            'destination' => $row['destination'],
            'start' => $row['start'],
            'end' => $row['end'],
            'guests' => $row['guests'],
            'guide' => $row['guide'],
            'porter' => $row['porter'],
        );

        // Check if there are events exactly 2 days after today
        if ($row['start'] == $twoDaysLater) {
            $hasEventsTwoDaysLater = true;
        }
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Guide Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <style>
        .content {
            margin-left: 200px;
            padding: 20px;
        }
        .sidebar {
            height: 100%;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #111;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .sidebar .logo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 80%;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="hrt.png" alt="HRT Logo" class="logo">
        <a href="User_dashboard.php">Dashboard</a>
        <a href="guide_settings.php">Settings</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main content -->
    <div class="content">
        <h1>Welcome, <?php echo $guide_name; ?></h1>
        <h2>Assigned Events</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Destination</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Guests</th>
                    <th>Total Guides</th>
                    <th>Total Porters</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event) { ?>
                    <tr>
                        <td><?php echo $event['id']; ?></td>
                        <td><?php echo $event['destination']; ?></td>
                        <td><?php echo $event['start']; ?></td>
                        <td><?php echo $event['end']; ?></td>
                        <td><?php echo $event['guests']; ?></td>
                        <td><?php echo $event['guide']; ?></td>
                        <td><?php echo $event['porter']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- JavaScript for notification -->
    <script>
        window.onload = function() {
            <?php if ($hasEventsTwoDaysLater): ?>
                alert("You have events scheduled for <?php echo $twoDaysLater; ?>.");
            <?php endif; ?>
        };
    </script>
</body>
</html>
