<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve start and end months from the form
    $start_month = $_POST["start_month"];
    $end_month = $_POST["end_month"];

    // Validate input (you can add more validation as needed)
    if (empty($start_month) || empty($end_month)) {
        echo "Please select both start and end months.";
        exit;
    }

    // Store the start and end months in session variables
    $_SESSION["start_month"] = $start_month;
    $_SESSION["end_month"] = $end_month;

    // Redirect to the main page
    header("Location: dashboard.php");
    exit;
}
?>
