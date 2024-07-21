<?php
session_start();



if(!isset($_SESSION['userloggedin']) || $_SESSION['userloggedin'] !== true){
    header("Location: ../index.php");
    exit;
}
$userLoginId = $_GET['id'];
// Check existence of id parameter before processing further

require_once "../db_connection.php";
// Prepare a select statement
$sql = "SELECT * FROM user_tbl WHERE user_id = ?";
    
if($stmt = mysqli_prepare($conn, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);
    
    // Set parameters
    $param_id = trim($_GET["id"]);
    
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) == 1){
            /* Fetch result row as an associative array. Since the result set
            contains only one row, we don't need to use while loop */
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $user_id = $row["user_id"];
            $user_pass = $row["user_pass"];
            $user_pass_confirm = $row["user_pass_confirm"];
            $user_lname = $row["user_lname"];
            $user_fname = $row["user_fname"];
            $user_mname = $row["user_mname"];
            $user_sfxname = $row["user_sfxname"];
            $user_birthdate = $row["user_birthdate"];
            $user_address_room = $row["user_address_room"];
            $user_address_house = $row["user_address_house"];
            $user_address_street = $row["user_address_street"];
            $user_address_subd = $row["user_address_subd"];
            $user_address_brgy = $row["user_address_brgy"];
            $account_pictures = $row["account_pictures"];
            $user_mnumber = $row["user_mnumber"];
            $user_email = $row["user_email"];
            $user_status = $row["user_status"];
            
           
            

        } else{
            
        }
        
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}

// Check if the user already has an active session
if(((int)$_SESSION['userid'] !== (int)$user_id)) {

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
    <title>User - Profile</title>
    <meta name="description" content="Profile">
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
            <div class="container d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="dashboard.php?id=<?php echo $userLoginId; ?>">
                    <div class="sidebar-brand-icon"><img class="border rounded-circle img-profile" id="nav-img" src="../assets/img/logo/logo.png"></div>
                    <div class="sidebar-brand-text mx-3"><span>CRG-MPC</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php?id=<?php echo $userLoginId; ?>"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-1" href="#collapse-1" role="button"><i class="fas fa-file-alt"></i>&nbsp;<span>Applications</span></a>
                            <div class="collapse" id="collapse-1">
                                <div class="bg-white border rounded py-2 collapse-inner">
                                    <h6 class="collapse-header">APPLICATIONS</h6>
                                    <a class="collapse-item" href="applications-create.php?id=<?php echo $userLoginId; ?>">Create CA</a>
                                    <a class="collapse-item" href="applications-manage.php?id=<?php echo $userLoginId; ?>">Manage CA</a>
                                    <a class="collapse-item" href="applications-approved.php?id=<?php echo $userLoginId; ?>">Approved</a>
                                    <a class="collapse-item" href="applications-disapproved.php?id=<?php echo $userLoginId; ?>">Disapproved</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-2" href="#collapse-2" role="button"><i class="fas fa-folder"></i>&nbsp;<span>Loans</span></a>
                            <div class="collapse" id="collapse-2">
                                <div class="bg-white border rounded py-2 collapse-inner">
                                    <h6 class="collapse-header">LOANS</h6>
                                    <a class="collapse-item" href="loans-active.php?id=<?php echo $userLoginId; ?>">Active Loans</a>
                                    <a class="collapse-item" href="loans-completed.php?id=<?php echo $userLoginId; ?>">Completed Loans</a>
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
                            <div><i class="far fa-calendar-alt text-primary me-1" id="nav-datetime-icon"></i><span class="text-nowrap" id="current-araw" class="current-datetime" style="font-size: 12px;">Sun | January 1, 2023</span></div>
                            <div><i class="far fa-clock text-primary me-1" id="nav-datetime-icon"></i><span id="current-oras" class="current-datetime">1:00:00 AM</span></div>
                        </div>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#">
                                        <div class="d-flex flex-column align-items-lg-end me-2">
                                            <div class="row" id="nav-profile">
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-fname"><?php echo $_SESSION["userfname"];?></span><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-lname"><?php echo $_SESSION["userlname"];?></span></div>
                                            </div>
                                            <div class="row" id="nav-profile">
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-acc">USER</span></div>
                                            </div>
                                        </div><img class="border rounded-circle img-profile" id="nav-profile-img" src="../admin/<?php echo $row["account_pictures"] ?>" alt="../assets/img/profile/profile-default.png" name="nav-profile-img">
                                    </a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a class="dropdown-item" href="profile.php?id=<?php echo $userLoginId; ?>"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a>
                                    <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="user-notification.php?id=<?php echo $userLoginId; ?>"><i class="fas fa-bell fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Notifications</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <h3 class="fw-bold text-dark">User</h3>
                    <form>
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Profile</p>
                            </div>
                            <div class="card-body">
                                <div class="col">
                                    <p><strong>User Identification</strong><br><span style="color: rgb(231, 74, 59);">User ID is auto-generated</span></p>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-id" placeholder=" " disabled="" required="" autocomplete="off" name="user-id" value="<?php echo $row["user_id"] ?>"><label class="form-label" for="floatingInput" >User ID</label></div>
                                    <p><strong>Preferred Password</strong><br><span style="color: rgb(231, 74, 59);">8-20 alphanumeric characters/different from user ID/one lowercase/one uppercase/one digit.</span></p>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" id="user-pass" placeholder=" " required="" minlength="8" maxlength="20" name="user-pass" disabled=""value="<?php echo $row["user_pass_confirm"] ?>"><label class="form-label" for="floatingInput">Password</label></div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" id="user-pass-confirm" placeholder=" " required="" minlength="8" maxlength="20" name="user-pass-confirm" disabled=""value="<?php echo $row["user_pass_confirm"] ?>"><label class="form-label" for="floatingInput">Confirm Password</label></div>
                                    <p><strong>Name</strong><br><span style="color: rgb(231, 74, 59);">Use of special characters are not allowed/first letter should be capitalized.</span></p>
                                    <div class="form-floating mb-3">
                                        <input class="form-control form-control" type="text" id="user-lname" placeholder=" " autocomplete="on" required="" name="user-lname" disabled=""value="<?php echo $row["user_lname"] ?>"><label class="form-label" for="floatingInput">Surname / Last Name</label></div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control form-control" type="text" id="user-fname" placeholder=" " required="" name="user-fname" disabled=""value="<?php echo $row["user_fname"] ?>"><label class="form-label" for="floatingInput">Given Name / First Name</label></div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control form-control" type="text" id="user-mname" placeholder=" " name="user-mname" disabled="" value="<?php echo $row["user_mname"] ?>"><label class="form-label" for="floatingInput">Middle Name</label></div>
                                    <div class="form-floating mb-3"><select class="form-select form-select" id="user-sfxname" for="floatinginput" name="user-sfxname" disabled="">
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
                                    <p><strong>Date of Birth</strong></p>
                                    <div class="mb-3"><input class="form-control form-control" id="user-birthdate" type="date" required="" name="user-birthdate" disabled="" value="<?php echo $row["user_birthdate"] ?>"></div>
                                    <p><strong>Address</strong></p>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-address-room" placeholder=" " name="user-address-room" disabled="" value="<?php echo $row["user_address_room"] ?>"><label class="form-label" for="floatingInput">Room / Floor / Unit No. &amp; Building Name</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-address-house" placeholder=" " name="user-address-house" disabled="" value="<?php echo $row["user_address_house"] ?>"><label class="form-label" for="floatingInput">House / Lot &amp; Block No.</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-address-street" placeholder=" " required="" name="user-address-street" disabled="" value="<?php echo $row["user_address_street"] ?>"><label class="form-label" for="floatingInput">Street</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-address-subd" placeholder=" " name="user-address-subd" disabled="" value="<?php echo $row["user_address_subd"] ?>"><label class="form-label" for="floatingInput">Subdivision</label></div>
                                    <div class="form-floating mb-3"><select class="form-select form-select" id="user-address-brgy" for="floatinginput" required="" name="user-address-brgy" disabled="">
                                            <?php 
                                            echo "<option value='" . $row["user_address_brgy"] . "'>" . $row["user_address_brgy"] . "</option>";
                                            ?>
                                            <option value="Bagong Kalsada">Bagong Kalsada</option>
                                            <option value="Bañadero">Bañadero</option>
                                            <option value="Banlic">Banlic</option>
                                            <option value="Barandal">Barandal</option>
                                            <option value="Barangay 1">Barangay 1</option>
                                            <option value="Barangay 2">Barangay 2</option>
                                            <option value="Barangay 3">Barangay 3</option>
                                            <option value="Barangay 4">Barangay 4</option>
                                            <option value="Barangay 5">Barangay 5</option>
                                            <option value="Barangay 6">Barangay 6</option>
                                            <option value="Barangay 7">Barangay 7</option>
                                            <option value="Batino">Batino</option>
                                            <option value="Bubuyan">Bubuyan</option>
                                            <option value="Bucal">Bucal</option>
                                            <option value="Bunggo">Bunggo</option>
                                            <option value="Burol">Burol</option>
                                            <option value="Camaligan">Camaligan</option>
                                            <option value="Canlubang">Canlubang</option>
                                            <option value="Halang">Halang</option>
                                            <option value="Hornalan">Hornalan</option>
                                            <option value="Kay-Anlog">Kay-Anlog</option>
                                            <option value="La Mesa">La Mesa</option>
                                            <option value="Laguerta">Laguerta</option>
                                            <option value="Lawa">Lawa</option>
                                            <option value="Lecheria">Lecheria</option>
                                            <option value="Lingga">Lingga</option>
                                            <option value="Looc">Looc</option>
                                            <option value="Mabato">Mabato</option>
                                            <option value="Majada Labas">Majada Labas</option>
                                            <option value="Makiling">Makiling</option>
                                            <option value="Mapagong">Mapagong</option>
                                            <option value="Masili">Masili</option>
                                            <option value="Maunong">Maunong</option>
                                            <option value="Mayapa">Mayapa</option>
                                            <option value="Milagrosa">Milagrosa</option>
                                            <option value="Paciano Rizal">Paciano Rizal</option>
                                            <option value="Palingon">Palingon</option>
                                            <option value="Palo-Alto">Palo-Alto</option>
                                            <option value="Pansol">Pansol</option>
                                            <option value="Parian">Parian</option>
                                            <option value="Prinza">Prinza</option>
                                            <option value="Punta">Punta</option>
                                            <option value="Puting Lupa">Puting Lupa</option>
                                            <option value="Real">Real</option>
                                            <option value="Saimsim">Saimsim</option>
                                            <option value="Sampiruhan">Sampiruhan</option>
                                            <option value="San Cristobal">San Cristobal</option>
                                            <option value="San Jose">San Jose</option>
                                            <option value="San Cristobal">San Juan</option>
                                            <option value="Sirang Lupa">Sirang Lupa</option>
                                            <option value="Sucol">Sucol</option>
                                            <option value="Turbina">Turbina</option>
                                            <option value="Ulango">Ulango</option>
                                            <option value="Uwisan">Uwisan</option>
                                        </select><label class="form-label" for="floatingInput">Baranggay</label></div>
                                    <p><strong>Account Picture</strong><br><span style="color: rgb(231, 74, 59);">Optional. Use a 1x1 aspect ratio digital copy&nbsp; of your profile picture</span></p>
                                    
                                    <!--ACCOUNT PICTURE-->
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-primary btn-icon-split" id="view-picture-btn" data-bs-target="#modal-1" data-bs-toggle="modal">
                                        <span class="text-white-50 icon"><i class="fas fa-image"></i></span><span class="text-white text">View Picture</span></a>
                                    </div>

                                    <!--START OF MODAL FOR ACCOUNT PICTURE-->
                                    <div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Account Picture</h4>
                                                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="d-xl-flex justify-content-xl-center">
                                                        <img class="img-fluid" src="../admin/<?php echo $row['account_pictures']; ?>" alt="The user does not submit an Account Picture">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a class="btn btn-secondary btn-icon-split m-1" role="button" data-bs-dismiss="modal">
                                                        <span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span>
                                                        <span class="text-white text">Back</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--END OF MODAL FOR ACCOUNT PICTURE-->
                                    <p><strong>Mobile Number</strong><br><span style="color: rgb(231, 74, 59);">Please provide&nbsp; an active mobile number</span></p>
                                    <div class="form-floating mb-3"><input class="form-control form-control-sm" type="text" id="user-mnumber" required="" placeholder=" " name="user-mnumber" disabled="" value="<?php echo $row["user_mnumber"] ?>"><label class="form-label" for="floatingInput">Contact Number</label></div>
                                    <p><strong>Email</strong><br><span style="color: rgb(231, 74, 59);">Please do not block crgmpc@gmail.com messages</span></p>
                                    <div class="form-floating mb-3"><input class="form-control form-control-sm" type="email" id="user-email" placeholder=" " autocomplete="on" name="user-email" disabled="" value="<?php echo $row["user_email"] ?>"><label class="form-label" for="floatingInput">Email Address</label></div>
                                    <p><strong>Preferred Document</strong><br><span style="color: rgb(231, 74, 59);">Document for Registration and Proof of identity/ownership</span></p>
                                    <div class="mb-3">
                                        <div class="form-floating mb-3"><select class="form-select form-select" id="user-pdocument-type" for="floatinginput" required="" name="user-pdocument-type" disabled="">
                                                <?php 
                                                echo "<option value='" . $row["user_pdocument_type"] . "'>" . $row["user_pdocument_type"] . "</option>";
                                                ?>
                                                <option value="Valid ID">Valid ID</option>
                                                <option value="Land Title">Land Title</option>
                                                <option value="Tax Declaration">Tax Declaration</option>
                                                <option value="Certificate of Land Ownership Award (CLOA)">Certificate of Land Ownership Award (CLOA)</option>
                                                <option value="Business Permit">Business Permit</option>
                                                <option value="Other">Other</option>
                                            </select><label class="form-label" for="floatinginput">Preferred Document Type</label></div>
                                    </div>

                                    <!--PDOCUMENT VIEW-->
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-primary btn-icon-split" id="view-picture-btn" data-bs-target="#modal-2" data-bs-toggle="modal">
                                        <span class="text-white-50 icon"><i class="fas fa-image"></i></span><span class="text-white text">View Document</span></a>
                                    </div>

                                    <!--START OF MODAL FOR P-DOCUMENT PICTURE-->
                                    <div class="modal fade" role="dialog" tabindex="-1" id="modal-2">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Preferred Document</h4>
                                                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="d-xl-flex justify-content-xl-center">
                                                    <img class="img-fluid" src="../admin/<?php echo $row['pdocument']; ?>" alt="The user does not submit Preferred Document">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a class="btn btn-secondary btn-icon-split m-1" role="button" data-bs-dismiss="modal">
                                                        <span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span>
                                                        <span class="text-white text">Back</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p><strong>User Status</strong><br><span style="color: rgb(231, 74, 59);">Set user status to Active or Inactive.</span></p>
                                    <div class="form-floating mb-3">
                                        <select class="form-select form-select" id="user-status" for="floatinginput" name="user-status" disabled>
                                            <option value="<?php echo $row["user_status"] ?>"><?php echo $row["user_status"] ?></option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                        <label class="form-label" for="floatingInput">User Status</label>
                                    </div>
                                    <div class="card-footer d-xl-flex justify-content-xl-end">
                                        <a class="btn btn-primary btn-icon-split m-1" id="update-btn" href="change-password.php?id=<?php echo $userLoginId; ?>">
                                            <span class="text-white-50 icon">
                                                <i class="fas fa-key"></i>
                                            </span>
                                            <span class="text-white text">Change Password</span>
                                        </a>
                                    </div>
                                    <!--END OF MODAL FOR P-DOCUMENT PICTURE-->
                                    <!-- <p class="p-text"><strong><span style="color: rgb(78, 115, 223);">Account Status</span></strong><br><strong><span style="color: rgb(28, 200, 138);">ACTIVE</span></strong><span style="color: rgb(231, 74, 59);"> </span>= Allows the user to use system features like submitting a CA<br><strong><span style="color: rgb(231, 74, 59);">INACTIVE</span></strong><span style="color: rgb(231, 74, 59);"> </span>= Disables the user and removes their access to the system</p>
                                    <div class="form-floating mb-3"><select class="form-select form-select" id="user-status" for="floatinginput" required="" disabled="">
                                            <option value="Active" selected="">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select><label class="form-label" for="floatinginput">Status</label></div> -->
                                </div>
                            </div>
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
        // Function to update the time every second
    function updateTime() {
    // Get the current time
    var currentTime = new Date();
    var time = currentTime.toLocaleTimeString();
    // Get the current date
    var currentDate = new Date();
    var options = { weekday: 'short', month: 'long', day: 'numeric' };
    var date = currentDate.toLocaleDateString(undefined, options);

    // Update the time element
    document.getElementById("current-oras").textContent = time;
    document.getElementById("current-araw").textContent = date;
    }

    // Update the time initially
    updateTime();

    // Update the time every second
    setInterval(updateTime, 1000);
        
    
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