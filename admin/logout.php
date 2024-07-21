<?php
// Initialize the session
session_start();

// Include the database connection file
include_once '../db_connection.php';

if (isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"] === true) {
    // Log the logout activity
    $userId = $_SESSION["id"];
    $description = "Logged out";
    $logSql = "INSERT INTO login_logs (user_id, notif_description) VALUES ('$userId', '$description')";
    $conn->query($logSql); // Assuming $conn is your database connection

    // Unset specific user-related session variables
    unset($_SESSION["adminloggedin"]);
    unset($_SESSION["id"]);
    unset($_SESSION["fname"]);
    unset($_SESSION["lname"]);
    unset($_SESSION['admin_role']);
}

session_destroy();
// Redirect to login page
header("location: ../index.php");
exit;
?>