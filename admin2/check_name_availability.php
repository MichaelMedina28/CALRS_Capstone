<?php
include_once '../db_connection.php';

$postFirstName = $_POST['fname'] ?? '';
$postMiddleName = $_POST['mname'] ?? '';
$postLastName = $_POST['lname'] ?? '';
$postSuffixName = $_POST['sfxname'] ?? '';

$trimFirstName = trim($postFirstName);
$trimMiddleName = trim($postMiddleName);
$trimLastName = trim($postLastName);
$trimSuffixName = trim($postSuffixName);

$sql = "SELECT COUNT(*) as count FROM user_tbl WHERE 
        (user_fname = ? AND user_mname = ? AND user_lname = ?) OR 
        (user_fname != ? AND user_mname = ? AND user_lname = ?) AND
        (user_fname = ? AND user_mname = ? AND user_lname != ?) AND
        (user_fname = ? AND user_mname != ? AND user_lname = ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die('Error in preparing statement: ' . $conn->error);
}

$stmt->bind_param("ssssssssssss", $trimFirstName, $trimMiddleName, $trimLastName, 
                  $trimFirstName, $trimMiddleName, $trimLastName, 
                  $trimFirstName, $trimMiddleName, $trimLastName,
                  $trimFirstName, $trimMiddleName, $trimLastName);

if (!$stmt->execute()) {
    die('Error in executing statement: ' . $stmt->error);
}

$result = $stmt->get_result();

if (!$result) {
    die('Error in getting result: ' . $stmt->error);
}

$row = $result->fetch_assoc();
$count = $row['count'];

if ($count > 0) {
    echo 'exists';
} else {
    echo 'available';
}
?>