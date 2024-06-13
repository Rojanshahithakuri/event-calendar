<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'summer';
    $conn = mysqli_connect($host, $user, $pass, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $guide_id = $_POST['guide_id'];
    $guide_name = $_POST['guide_name'];
    $license_no = $_POST['license_no'];
    $phone_no = $_POST['phone_no'];
    
    if (!empty($_FILES['guide_photo']['name'])) {
        $guide_photo = $_FILES['guide_photo']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["guide_photo"]["name"]);
        move_uploaded_file($_FILES["guide_photo"]["tmp_name"], $target_file);
        
        $sql = "UPDATE guides SET guide_name='$guide_name', license_no='$license_no', phone_no='$phone_no', guide_photo='$guide_photo' WHERE guide_id='$guide_id'";
    } else {
        $sql = "UPDATE guides SET guide_name='$guide_name', license_no='$license_no', phone_no='$phone_no' WHERE guide_id='$guide_id'";
    }

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Guide updated successfully!"); window.location.href="guides.php";</script>';
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
