<?php
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

// Include the database connection file
include_once '../db_connection.php';

$sql = "SELECT user_id FROM user_tbl ORDER BY user_id DESC LIMIT 1";
$result = $conn->query($sql);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form inputs
    $user_id = $_POST['user-id'];
    $user_pass = $_POST['user-pass'];
    $user_pass_confirm = $_POST['user-pass-confirm'];
    //name
    $user_firstName = $_POST['user-fname'];
    $user_middleName = $_POST['user-mname'];
    $user_lastName = $_POST['user-lname'];
    $user_suffixName = $_POST['user-sfxname'];
    //date
    $user_birthdate = $_POST['user-birthdate'];
    //address
    $user_address_room = $_POST['user-address-room'];
    $user_address_house = $_POST['user-address-house'];
    $user_address_street = $_POST['user-address-street'];
    $user_address_subd = $_POST['user-address-subd'];
    $user_address_brgy = $_POST['user-address-brgy'];

    //Account Picture
    $file = $_FILES["user-picture-profile"];
    // File details
    $fileName = "$user_firstName $user_lastName - " .$file["name"];
    $fileTmpName = $file["tmp_name"];
    $fileSize = $file["size"];
    $fileError = $file["error"];
    // Move the uploaded file to a desired location on the server
    $destination = "account_pictures/" . $fileName;
    move_uploaded_file($fileTmpName, $destination);

    //Preferred Document
    $document = $_FILES["user-pdocument"];
    // File details
    $documentName = "$user_firstName $user_lastName - " .$document["name"];
    $documentTmpName = $document["tmp_name"];
    $documentSize = $document["size"];
    $documentError = $document["error"];
    // Move the uploaded file to a desired location on the server
    $pdocument = "preferred_document/" . $documentName;
    move_uploaded_file($documentTmpName, $pdocument);

    //mobile number
    $user_mnumber = $_POST['user-mnumber'];
    $user_email = $_POST['user-email'];
    $user_pdocument_type = $_POST['user-pdocument-type'];

    
    $user_position = $_POST['user-position'];
    $share_investment = $_POST['share-investment'];
    $user_spouse_name = $_POST['user-spouse-name'];

    // Prepare the SQL statement to insert the data into app_pending_tbl
    $sql = "INSERT INTO user_tbl (user_id, user_pass, user_pass_confirm, 
    user_fname, user_mname, user_lname, user_sfxname,
    user_birthdate, user_address_room, user_address_house, user_address_street, user_address_subd, user_address_brgy, 
    account_pictures,
    user_mnumber, user_email, user_pdocument_type, pdocument, position, spouse_name, share_investment) 
    VALUES ('$user_id','$user_pass','$user_pass_confirm', '$user_firstName', 
    '$user_middleName', '$user_lastName', '$user_suffixName',
    '$user_birthdate', '$user_address_room', '$user_address_house', '$user_address_street', '$user_address_subd', '$user_address_brgy',
    '$destination',
    '$user_mnumber', '$user_email', '$user_pdocument_type', '$pdocument', '$user_position', '$user_spouse_name', '$share_investment')";

    
    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        // Data inserted successfully
        
    } 
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Admin - Users Register</title>
    <meta name="description" content="Register Users">
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
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-fname"><?php echo $_SESSION["admin_fname"];?></span><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-lname"><?php echo $_SESSION["admin_lname"];?></span></div>
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
                    <h3 class="fw-bold text-dark">Register</h3>
                    <form>
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 fw-bold">Register User</p>
                            </div>
                            <div class="card-body">
                                <p class="p-text">Successfully <strong><span style="color: rgb(78, 115, 223);">REGISTERED</span></strong> new user.</p>
                            </div>
                            <div class="card-footer d-xl-flex justify-content-xl-end">
                            <a class="btn btn-secondary btn-icon-split m-1" type="submit" href="user-manage.php"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Back</span></a>
                            <!--<button class="btn btn-success btn-icon-split m-1" id="register-btn" type="submit" data-bs-target="#register-modal-confirm" data-bs-toggle="modal"><span class="text-white-50 icon"><i class="fas fa-user-plus"></i></span><span class="text-white text">Register</span></button></div>-->
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
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="../assets/js/features/disable-for-approval.js"></script>
    <script src="../assets/js/features/scripts.js"></script>
    <script src="../assets/js/index.js"></script>
    <script src="../assets/js/theme.js"></script>
</body>

</html>