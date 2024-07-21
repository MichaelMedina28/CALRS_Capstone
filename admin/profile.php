<?php
// Include config file
require_once "../db_connection.php";
session_start();
if (!isset($_SESSION['adminloggedin']) || $_SESSION['admin_role'] !== 'admin') {
    // Redirect to login page or show an error message
    header("Location: ../index.php");
    exit();
}

// Check if there is already an active admin session
if (isset($_SESSION['active_admin']) && $_SESSION['active_admin'] !== session_id()) {
    // Another admin is already logged in, log them out
    session_write_close(); // Close the existing session to avoid session data conflicts
    session_id($_SESSION['active_admin']); // Set the session ID to the existing admin's session
    session_start();
    session_regenerate_id(); // Regenerate session ID for security
    session_unset(); // Unset all session data
    session_destroy(); // Destroy the existing session
    session_write_close(); // Close the session

    // Now create a new session for the current admin
    session_id(session_create_id()); // Generate a new session ID
    session_start();
}

$_SESSION['active_admin'] = session_id();


// Check if admin is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login page
    header("Location: ../index.php");
    exit;
}

// Retrieve admin's ID from session
$admin_id = $_SESSION['id'];

// Attempt select query execution
$sql = "SELECT * FROM user_tbl WHERE user_id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    // Bind the admin's ID as a parameter
    mysqli_stmt_bind_param($stmt, "i", $admin_id);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Check if a row is found
    if (mysqli_num_rows($result) > 0) {
        // Fetch the admin's profile data
        $row = mysqli_fetch_assoc($result);

        // Display the profile information
        $admin_id = $row["user_id"];
        $admin_username = $row["user_lname"];
        $user_pass = $row["user_pass"];
        $user_pass_confirm = $row["user_pass_confirm"];
        $user_lname = $row["user_lname"];
        $user_fname = $row["user_fname"];
        $user_mname = $row["user_mname"];
        $user_sfxname = $row["user_sfxname"];
        $user_mnumber = $row["user_mnumber"];
        $user_email = $row["user_email"];
        // Display other profile fields

        // Free the result
        mysqli_free_result($result);
    } else {
    }
}
if (isset($_POST['update-btn'])) {
    $sql = "UPDATE admin_tbl SET admin_username = ?,
    admin_pass = ?, admin_pass_confirm = ?, admin_lname = ?, admin_fname = ?, admin_mname = ?, admin_sfxname = ?, admin_mnumber = ?, admin_email = ?
    WHERE admin_id = $admin_id";
    $stmt = $conn->prepare($sql);

    $admin_username = $_POST['admin-username'];
    $admin_pass = password_hash($_POST['admin-pass'], PASSWORD_DEFAULT);
    $admin_pass_confirm = $_POST['admin-pass-confirm'];
    $admin_lname = $_POST['admin-lname'];
    $admin_fname = $_POST['admin-fname'];
    $admin_mname = $_POST['admin-mname'];
    $admin_sfxname = $_POST['admin-sfxname'];
    $admin_mnumber = $_POST['admin-mnumber'];
    $admin_email = $_POST['admin-email'];


    $stmt->bind_param("sssssssss", $admin_username, $admin_pass, $admin_pass_confirm, $admin_lname, $admin_fname, $admin_mname, $admin_sfxname, $admin_mnumber, $admin_email);

    // Execute the statement
    $stmt->execute();

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();
    header("Location: profile.php");
}
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Admin - Profile</title>
    <meta name="description" content="Admin Profile">
    <link rel="icon" type="image/png" sizes="396x396" href="../assets/img/logo/logo.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/css/bs-theme-overrides.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0 navbar-dark">
            <div class="container d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="dashboard.php">
                    <div class="sidebar-brand-icon"><img class="border rounded-circle" id="nav-img" src="../assets/img/logo/logo.png"></div>
                    <div class="sidebar-brand-text mx-3"><span>CRG-MPC</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-1" href="#collapse-1" role="button"><i class="fas fa-file-alt"></i>&nbsp;<span>Applications</span></a>
                            <div class="collapse" id="collapse-1">
                            <div class="bg-white border rounded py-2 collapse-inner">
                                    <h6 class="collapse-header">APPLICATIONS</h6>
                                    <a class="collapse-item" href="applications-pending.php">Pending CA</a>
                                    <a class="collapse-item" href="applications-approved.php">Approved</a>
                                    <a class="collapse-item" href="applications-disapproved.php">Disapproved</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-2" href="#collapse-2" role="button"><i class="fas fa-folder"></i>&nbsp;<span>Loans</span></a>
                            <div class="collapse" id="collapse-2">
                                <div class="bg-white border rounded py-2 collapse-inner">
                                    <h6 class="collapse-header">LOANS</h6><a class="collapse-item" href="loans-active.php">Active Loans</a><a class="collapse-item" href="loans-completed.php">Completed Loans</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-3" href="#collapse-3" role="button"><i class="fas fa-user-friends"></i>&nbsp;<span>Users</span></a>
                            <div class="collapse" id="collapse-3">
                                <div class="bg-white border rounded py-2 collapse-inner">
                                    <h6 class="collapse-header">users</h6><a class="collapse-item" href="user-register.php">Register Users</a><a class="collapse-item" href="user-manage.php">Manage Users</a>
                                </div>
                            </div>
                        </div>
                    </li> -->
                    <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-4" href="#collapse-4" role="button"><i class="fas fa-trash"></i>&nbsp;<span>Archived</span></a>
                            <div class="collapse" id="collapse-4">
                                <div class="bg-white border rounded py-2 collapse-inner">
                                    <h6 class="collapse-header">Archived</h6><a class="collapse-item" href="archived-approved-applications.php">Approved</a><a class="collapse-item" href="archived-activeloan.php">Active Loans</a><a class="collapse-item" href="archived-completedloan.php">Completed Loans</a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <div class="d-flex d-xl-flex flex-column align-items-start justify-content-xl-start">
                            <div><i class="far fa-calendar-alt text-primary me-1" id="nav-datetime-icon"></i><span id="current-date" class="current-datetime">Sun | January 1, 2023</span></div>
                            <div><i class="far fa-clock text-primary me-1" id="nav-datetime-icon"></i><span id="current-time" class="current-datetime">1:00:00 AM</span></div>
                        </div>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#">
                                        <div class="d-flex flex-column align-items-lg-end me-2">
                                            <div class="row" id="nav-profile">
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-fname"><?php echo $_SESSION["admin_fname"]; ?></span><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-lname"><?php echo $_SESSION["admin_lname"]; ?></span></div>
                                            </div>
                                            <div class="row" id="nav-profile">
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-acc">ADMIN</span></div>
                                            </div>
                                        </div><img class="border rounded-circle img-profile" id="nav-profile-img" src="../assets/img/profile/profile-default.png" name="nav-profile-img">
                                    </a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a class="dropdown-item" href="profile.php"><i class="fas fa-user fa-sm fa-fw" id="profile-icon"></i>Profile</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="activity-log.php">
                                            <i class="fas fa-list-alt fa-sm fa-fw" id="profile-icon"></i>Activity Log</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw" id="profile-icon"></i>Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <h3 class="fw-bold text-dark">Profile</h3>
                    <form method="post">
                        <div class="card shadow mb-3">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Admin Information</p>
                            </div>
                            <div class="card-body">
                                <div class="col">
                                    <p><strong>Admin Identification</strong><br><span style="color: rgb(231, 74, 59);">Admin ID is auto-generated</span></p>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="admin-id" placeholder=" " disabled="" required="" autocomplete="off" name="admin-id" value="<?php echo $row["user_id"] ?>"><label class="form-label" for="floatingInput">Admin ID</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="admin-id" placeholder=" " required="" autocomplete="off" name="admin-username" value="<?php echo $row["user_lname"] ?>"><label class="form-label" for="floatingInput">Admin Name</label></div>
                                    <p><strong>Preferred Password</strong><br><span style="color: rgb(231, 74, 59);">8-20 alphanumeric characters/different from user ID/one lowercase/one uppercase/one digit.</span></p>
                                    <div class="form-floating mb-3"><input class="form-control" type="password" id="admin-pass" placeholder=" " required="" minlength="8" maxlength="20" name="admin-pass" oninput="validatePassword()" value="<?php echo $row["user_pass_confirm"] ?>"><label class="form-label" for="floatingInput">Password</label></div>
                                    <p id="passwordError" style="color: red;"></p>
                                    <div class="form-floating mb-3"><input class="form-control" type="password" id="admin-pass-confirm" placeholder=" " required="" minlength="8" maxlength="20" name="admin-pass-confirm" value="<?php echo $row["user_pass_confirm"] ?>"><label class="form-label" for="floatingInput">Confirm Password</label></div>
                                    <p><strong>Name</strong><br><span style="color: rgb(231, 74, 59);">Use of special characters are not allowed/first letter should be capitalized.</span></p>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="admin-lname" placeholder=" " autocomplete="on" required="" name="admin-lname" value="<?php echo $row["user_lname"] ?>"><label class="form-label" for="floatingInput">Surname / Last Name</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="admin-fname" placeholder=" " required="" name="admin-fname" value="<?php echo $row["user_fname"] ?>"><label class="form-label" for="floatingInput">Given Name / First Name</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="admin-mname" placeholder=" " name="admin-mname" value="<?php echo $row["user_mname"] ?>"><label class="form-label" for="floatingInput">Middle Name</label></div>
                                    <div class="form-floating mb-3"><select class="form-select form-select" id="admin-sfxname" for="floatinginput" name="admin-sfxname">
                                            <?php
                                            echo "<option value='" . $row["user_sfxname"] . "'>" . $row["user_sfxname"] . "</option>";
                                            ?>
                                            <option value="">N/A</option>
                                            <option value="Jr.">Jr.</option>
                                            <option value="Sr.">Sr.</option>
                                            <option value="II">II</option>
                                            <option value="III">III</option>
                                            <option value="IV">IV</option>
                                            <option value="V">V</option>
                                            <option value="VI">VI</option>
                                        </select><label class="form-label" for="floatinginput">Suffix</label></div>

                                    <p><strong>Mobile Number</strong><br><span style="color: rgb(231, 74, 59);">Please provide&nbsp; an active mobile number</span></p>
                                    <div class="form-floating mb-3"><input class="form-control" type="text" id="admin-mnumber" required="" placeholder=" " name="admin-mnumber" value="<?php echo $row["user_mnumber"] ?>"><label class="form-label" for="floatingInput">Contact Number</label></div>
                                    <p><strong>Email</strong><br></p>
                                    <div class="form-floating mb-3"><input class="form-control" type="email" id="admin-email" placeholder=" " autocomplete="on" name="admin-email" value="<?php echo $row["user_email"] ?>"><label class="form-label" for="floatingInput">Email Address</label></div>
                                </div>
                            </div>
                            <div class="card-footer d-xl-flex justify-content-xl-end"><button class="btn btn-success btn-icon-split m-1" id="update-btn" type="submit" name="update-btn"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-up"></i></span><span class="text-white text">Update</span></button></div>
                        </div>
                    </form>
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © City College of Calamba 2023</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" data-bs-toggle="tooltip" data-bss-tooltip="" id="page-top-btn" href="#page-top" title="Return to Top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script>
        //same password(password and confirm password)
        const adminpasswordInput = document.getElementById('admin-pass');
        const adminconfirmPasswordInput = document.getElementById('admin-pass-confirm');

        adminconfirmPasswordInput.addEventListener('input', function() {

            if (adminpasswordInput.value !== adminconfirmPasswordInput.value) {
                adminconfirmPasswordInput.setCustomValidity('Passwords do not match');

            } else {
                adminconfirmPasswordInput.setCustomValidity('');

            }
        });

        //Validate Password
        function validatePassword() {
            var password = document.getElementById("admin-pass").value;
            var passwordError = document.getElementById("passwordError");
            var isValidPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$/.test(password);

            if (!isValidPassword) {
                passwordError.textContent = "Password must be 8-20 characters long and include at least one lowercase letter, one uppercase letter, and one digit.";
                return;
            }
            passwordError.textContent = '';
        }

        //Validate Name
        function capitalizeFirstLetter(id) {
            var inputElement = document.getElementById(id);
            var inputValue = inputElement.value;

            // Capitalize the first letter and add the rest of the string
            var capitalizedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1);

            // Update the input field with the capitalized value
            inputElement.value = capitalizedValue;
        }

        //Validate Mobile
        document.getElementById('user-mnumber').addEventListener('input', function() {
            validateMobileNumber();
        });

        function validateMobileNumber() {
            var mobileNumber = document.getElementById('admin-mnumber').value;
            var errorMessage = document.getElementById('error-message');

            // Remove non-digit characters
            var numericMobileNumber = mobileNumber.replace(/\D/g, '');

            if (numericMobileNumber.length === 11) {
                errorMessage.textContent = ''; // Clear error message
            } else {
                errorMessage.textContent = 'Please enter a valid 11-digit mobile number.';
            }
        }

    </script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="../assets/js/features/disable-for-approval.js"></script>
    <script src="../assets/js/features/scripts.js"></script>
    <script src="../assets/js/index.js"></script>
    <script src="../assets/js/theme.js"></script>
</body>

</html>