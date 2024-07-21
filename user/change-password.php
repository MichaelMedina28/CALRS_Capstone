<?php
session_start();
$userLoginId = $_GET['id'];
require_once '../db_connection.php'; // Include your database connection file
$changepasserror = "";
$changepasssuccess = "";

$sql = "SELECT * FROM user_tbl WHERE user_id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    // Set parameters
    $param_id = trim($_GET["id"]);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            /* Fetch result row as an associative array. Since the result set
            contains only one row, we don't need to use while loop */
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            // Retrieve individual field value
            $user_pass = $loan_id = $row["user_pass"];
        } 
    }
}

if (isset($_POST['update'])) {

    $oldPassword = $_POST['old-password'];
    $inputnewpassword = $_POST['new-password'];
    $inputconfirmpassword = $_POST['confirm-password'];

    $newPassword = password_hash($_POST['new-password'], PASSWORD_DEFAULT);
    $confirmPassword = password_hash($_POST['confirm-password'], PASSWORD_DEFAULT);

    if (password_verify($oldPassword, $user_pass) && $inputnewpassword == $inputconfirmpassword){
        $sqlupdatepass = "UPDATE user_tbl SET user_pass = ?, user_pass_confirm = ? WHERE user_id = ?";

        $stmt = mysqli_prepare($conn, $sqlupdatepass);
        mysqli_stmt_bind_param($stmt, "ssi", $newPassword, $confirmPassword, $param_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $changepasssuccess = "Your password successfully changed.";
    }else{
        $changepasserror = "Invalid old password.";
    }
}
echo $_SESSION['userid'];

// Check if the user already has an active session
if(((int)$_SESSION['userid'] !== (int)$param_id)) {

    // Unset specific user-related session variables
    unset($_SESSION["userloggedin"]);
    unset($_SESSION["userid"]);
    unset($_SESSION["userfname"]);
    unset($_SESSION["userlname"]);
    
    // Redirect or handle the case where the user is already logged in
    header("Location: ../index.php"); 
}

?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Change Password</title>
    <meta name="description" content="Profile">
    <link rel="icon" type="image/png" sizes="396x396" href="../assets/img/logo/logo.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/css/bs-theme-overrides.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <nav class="navbar navbar-expand-md bg-body shadow py-3" id="navbar-main">
    <div class="container"><a class="navbar-brand d-flex align-items-center" ><img id="login-img" src="../assets/img/logo/logo.png"><span><strong>CRG-MPC</strong></span></a></div>
            
    </nav>
        <section class="d-lg-flex position-relative py-5 py-xl-5">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-9 col-lg-7 col-xl-6">
                        <div class="card mb-5">
                            <div class="card-body d-flex flex-column align-items-center">
                                <div class="my-3"><img id="login-logo" src="../assets/img/logo/logo.png"></div>
                                <h4 class="mb-3">Calamba Rice Growers Multi-Purpose Cooperative Loan System</h4>
                                <div class="card-body d-flex flex-column align-items-left">
                                    <h4 class="mb-3">Change Password</h4>
                                </div>
                                <form method="post">
                                <div class="form-floating mb-3">
                                    <input class="form-control form-control" type="password" id="old-password" placeholder=" " required="" name="old-password">
                                    <label class="form-label" for="floatingInput">Old Password</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control form-control" type="password" id="new-password" placeholder=" " required="" name="new-password">
                                    <label class="form-label" for="floatingInput">New Password</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control form-control" type="password" id="confirm-password" placeholder=" " required="" name="confirm-password">
                                    <label class="form-label" for="floatingInput">Confirm Password</label>
                                </div>
                                <div class="align-items-center">
                                    <p class="error-message" style="text-align: center;"><?php echo $changepasserror; ?></p>
                                    <p class="error-message" style="text-align: center;"><?php echo $changepasssuccess; ?></p>
                                </div>
                                    
                                
                                
                                <div class="my-3">
                                    <a class="btn btn-secondary btn-icon-split" id="login-btn" href="profile.php?id=<?php echo $userLoginId; ?>">
                                        <span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span>
                                        <span class="text-white text">Cancel</span>
                                    </a>
                                    <button class="btn btn-primary btn-icon-split" name="update" id="login-btn" type="submit">
                                        <span class="text-white-50 icon"><i class="fas fa-check-circle"></i></span>
                                        <span class="text-white text">Update Password</span>
                                    </button>
                                </div>

                                </form>

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