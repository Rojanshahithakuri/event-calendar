<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $guideName = $_POST["guideName"];
    $licenseNo = $_POST["licenseNo"];
    $phoneNo = $_POST["phoneNo"];

    // Handle photo upload
    $guidePhoto = '';
    if (isset($_FILES['guidePhoto']) && $_FILES['guidePhoto']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $guidePhoto = $targetDir . basename($_FILES["guidePhoto"]["name"]);
        if (!move_uploaded_file($_FILES["guidePhoto"]["tmp_name"], $guidePhoto)) {
            die("Failed to upload file.");
        }
    }

    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'summer';

    $conn = mysqli_connect($host, $user, $pass, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO guides (guide_name, license_no, phone_no, guide_photo) VALUES ('$guideName', '$licenseNo', '$phoneNo', '$guidePhoto')";
    if (mysqli_query($conn, $sql)) {
        echo "Guide added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
    header("Location: guides.php");
    exit();
}
?>
