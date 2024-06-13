<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "summer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $destination = $_POST['destination'];
    $guests = $_POST['guests'];
    $guide_name=$_POST['guide_name'];
    $guide = $_POST['guide'];
    $porter = $_POST['porter'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    $sql = "UPDATE calendar SET 
            destination='$destination', 
            guests='$guests', 
            guide_name='$guide_name',
            guide='$guide', 
            porter='$porter', 
            start='$start', 
            end='$end' 
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Event updated successfully!');
                window.location.href = 'calendar.php';
              </script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>