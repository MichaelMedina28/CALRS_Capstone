<?php
// reset-password.php

require_once 'db_connection.php'; // Include your database connection file
require 'PHPMailer-6.9.1/src/Exception.php';
require 'PHPMailer-6.9.1/src/PHPMailer.php';
require 'PHPMailer-6.9.1/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoloader
require 'vendor/autoload.php';

$token = $_GET['token'];

if (isset($_GET['token'])) {
    // Check if the token exists in the database
    $stmt = $conn->prepare('SELECT * FROM admin_tbl WHERE reset_token = ?');
    $stmt->bind_param('s', $token);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user record
        $row = $result->fetch_assoc();
        $email = $row['admin_email'];

        // Check if the password has already been reset
        if ($row['password_reset_done'] == 0) {
            try {
                // Token exists, fetch the user record before updating
                $newPassword = bin2hex(random_bytes(6)); // Generate a random password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $mail = new PHPMailer(true);

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
                $mail->Body    = 'Your password has been reset. This is your new password ' . $newPassword . '.';

                $mail->send();
                // ... (your mail configuration remains the same)

                // Update the user's record in the database with the new hashed password and set reset_done to 1
                $updateStmt = $conn->prepare('UPDATE admin_tbl SET admin_pass = ?, admin_pass_confirm = ?, password_reset_done = 1 WHERE reset_token = ?');
                $updateStmt->bind_param('sss', $hashedPassword, $newPassword, $token);
                $updateStmt->execute();

                unset($_GET['token']);

                // Check for successful update and handle accordingly
                if ($updateStmt->affected_rows > 0) {
                    // Optionally: Invalidate or remove the reset token after password reset
                    // Update user_tbl SET reset_token = NULL WHERE reset_token = ?
                    //echo 'Password reset successfully.';
                } else {
                    echo 'Failed to update password.';
                }

                $updateStmt->close(); // Close the update statement

            } catch (Exception $e) {
                echo "Failed to send new password. Error: {$mail->ErrorInfo}";
            }
        } else {
            //echo 'Password has already been reset.';
        }
    } else {
        echo "Invalid or expired token.";
    }

    $stmt->close(); // Close the select statement
    $conn->close();
} else {
    echo "Invalid token.";
}
?>



<!DOCTYPE html>

<html data-bs-theme="light" lang="en">



<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Reset Password</title>

    <meta name="description" content="Login">

    <link rel="icon" type="image/png" sizes="396x396" href="assets/img/logo/logo.png">

    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">

    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">

    <link rel="stylesheet" href="assets/css/bs-theme-overrides.css">

    <link rel="stylesheet" href="assets/css/styles.css">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>



</head>



<body>

    <nav class="navbar navbar-expand-md bg-body shadow py-3" id="navbar-main">

    <div class="container"><a class="navbar-brand d-flex align-items-center" href="index.php"><img id="login-img" src="assets/img/logo/logo.png"><span><strong>CRG-MPC</strong></span></a></div>

            

    </nav>

        <section class="d-lg-flex position-relative py-5 py-xl-5">

            <div class="container">

                <div class="row d-flex justify-content-center">

                    <div class="col-md-9 col-lg-7 col-xl-6">

                        <div class="card mb-5">

                            <div class="card-body d-flex flex-column align-items-center">

                                <div class="my-3"><img id="login-logo" src="assets/img/logo/logo.png"></div>

                                <h4 class="mb-3">Calamba Rice Growers Multi-Purpose Cooperative Loan System</h4>

                                <br>

                                <p class="p-text" style="text-align: center;">Your password has been reset. Check your email address to view your new password.</p>

                                



                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>





    <script src="assets/js/jquery.min.js"></script>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

    <script src="assets/js/bs-init.js"></script>

    <script src="assets/js/features/disable-for-approval.js"></script>

    <script src="assets/js/features/scripts.js"></script>

    <script src="assets/js/index.js"></script>

    <script src="assets/js/theme.js"></script>

</body>



</html>