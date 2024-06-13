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

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $completed = $_POST['completed'];

    $sql = "UPDATE calendar SET completed = ? WHERE ID = ?"; // Adjust the table and column names as needed
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $completed, $id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
