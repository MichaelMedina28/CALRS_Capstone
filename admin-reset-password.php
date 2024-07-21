<?php
require 'PHPMailer-6.9.1/src/Exception.php';
require 'PHPMailer-6.9.1/src/PHPMailer.php';
require 'PHPMailer-6.9.1/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoloader
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; // Sanitize and validate the email

    // Check if the email exists in your database
    // Perform your database query to check email existence
    include_once 'db_connection.php'; // Include your database connection file

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the email exists in your user_tbl table
    $stmt = $conn->prepare('SELECT * FROM admin_tbl WHERE admin_email = ?');

    // Check for errors in preparing the SQL statement
    if (!$stmt) {
        die('Error preparing statement: ' . $conn->error);
    }

    // Bind the parameter and execute the query
    if (!$stmt->bind_param('s', $email)) {
        die('Error binding parameters: ' . $stmt->error);
    }

    if (!$stmt->execute()) {
        die('Error executing query: ' . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Send the new password to the user's email
        $resetToken = bin2hex(random_bytes(16));
        $mail = new PHPMailer(true);

        $sqlUpdateToken = "UPDATE admin_tbl SET reset_token = ? WHERE admin_email = ?";
        $stmtUpdateToken = $conn->prepare($sqlUpdateToken);
        $stmtUpdateToken->bind_param('ss', $resetToken, $email);
        $stmtUpdateToken->execute();

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp-relay.brevo.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mbmedina@ccc.edu.ph';
            $mail->Password   = 'akxBvSg8ZrXO6qz5';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('crgmpc@gmail.com', 'CRG-MPC');
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Reset Password';
            $mail->Body    = 'To reset your password, click this link: <a href="https://crg-mpc-calrs.rf.gd/admin-pass-reset.php?token=' . $resetToken . '">Reset Password</a>';

            $mail->send();
            // ... (your mail configuration remains the same)
            echo "Reset Password Link successfully sent.";
        } catch (Exception $e) {
            echo "Failed to send new password. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found. Please enter a registered email address.";
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>
    