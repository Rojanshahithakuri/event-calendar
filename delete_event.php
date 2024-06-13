<?php
if(isset($_POST['id'])){
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

    $id = $_POST['id'];

    // SQL query to delete the event
    $sql = "DELETE FROM calendar WHERE ID='$id'";
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Event deleted successfully!"); window.location.href="calendar.php";</script>';
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

