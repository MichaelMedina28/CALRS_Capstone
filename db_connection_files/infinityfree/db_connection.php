<?php
$servername = "sql312.infinityfree.com";
$username = "if0_35182772";
$password = "6ClH1RkGIgaD";
$dbname = "if0_35182772_calrs_db";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
