<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'summer';
    $conn = mysqli_connect($host, $user, $pass, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $guide_id = $_GET['id'];
    $sql = "DELETE FROM guides WHERE guide_id='$guide_id'";

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Guide deleted successfully!"); window.location.href="guides.php";</script>';
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
