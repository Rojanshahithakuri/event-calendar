<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'summer';

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $duration = $_POST['duration'];
    $grade = $_POST['grade'];

    $sql = "INSERT INTO destinations (name, duration, grade) VALUES ('$name', '$duration', '$grade')";
    if (mysqli_query($conn, $sql)) {
        header("Location: destination.php"); // Redirect back to the destinations page
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
