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

// Fetch user information
$user_query = "SELECT * FROM users_guide WHERE username = '$username'";
$user_result = mysqli_query($conn, $user_query);
$user_info = mysqli_fetch_assoc($user_result);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guide Settings</title>
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
        <a href="user_dashboard.php">Dashboard</a>
        <a href="guide_settings.php">Settings</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main content -->
    <div class="content">
        <h1>User Settings</h1>
        <table class="table">
            <tr>
                <th>Id</th>
                <td><?php echo $user_info['id']; ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo $user_info['name']; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $user_info['email']; ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo $user_info['phone_no']; ?></td>
            </tr>
            <tr>
                <th>username</th>
                <td><?php echo $user_info['username']; ?></td>
            </tr>
            <tr>
                <th>License No</th>
                <td><?php echo $user_info['license_no']; ?></td>
            </tr>
            <!-- Add more fields as necessary -->
        </table>
    </div>
</body>
</html>
