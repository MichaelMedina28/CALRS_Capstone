<?php
// Initialize the session
session_start();

// Include the database connection file
include_once '../db_connection.php';

if (isset($_SESSION["userloggedin"]) && $_SESSION["userloggedin"] === true) {
    // Log the logout activity
    $userId = $_SESSION["userid"];
    $description = "Logged out";
    $logSql = "INSERT INTO login_logs (user_id, notif_description) VALUES ('$userId', '$description')";
    $conn->query($logSql); // Assuming $conn is your database connection

    // Unset specific user-related session variables
    unset($_SESSION["userloggedin"]);
    unset($_SESSION["userid"]);
    unset($_SESSION["userfname"]);
    unset($_SESSION["userlname"]);
}
session_destroy();
// Redirect to login page
header("location: ../index.php");
exit;
?>