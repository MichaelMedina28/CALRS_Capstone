<?php
// Check existence of id parameter before processing further
session_start();
$reason_delete = "";
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

require_once "../db_connection.php";
// Prepare a select statement
$sql = "SELECT * FROM app_pending_tbl WHERE loan_id = ?";

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
            $user_id = $loan_id = $row["user_id"];
            $loan_id = $row["loan_id"];
            $lname = $row["lname"];
            $fname = $row["fname"];
            $mname = $row["mname"];
            $sfxname = $row["sfxname"];
            $address_room = $row["address_room"];
            $address_house = $row["address_house"];
            $address_street = $row["address_street"];
            $address_subd = $row["address_subd"];
            $address_brgy = $row["address_brgy"];
            $position = $row["position"];
            $seq_nos = $row["seq_nos"];
            $date_created = $row["date_created"];
            $lot_nos = $row["lot_nos"];
            $amount_number = $row["amount_number"];
            $allocation_type1 = $row["allocation_type1"];
            $sallocation_type1 = $row["sallocation_type1"];
            $allocation_type2 = $row["allocation_type2"];
            $sallocation_type2 = $row["sallocation_type2"];
            $allocation_type3 = $row["allocation_type3"];
            $sallocation_type3 = $row["sallocation_type3"];
            $allocation_type4 = $row["allocation_type4"];
            $sallocation_type4 = $row["sallocation_type4"];
            $date_agreement = $row["date_agreement"];
            $debtor_name = $row["debtor_name"];
            $spouse_name = $row["spouse_name"];
            $lpartner_name = $row["lpartner_name"];
            $brw_sinvestment = $row["brw_sinvestment"];
            $brw_dbalance = $row["brw_dbalance"];
            $brw_partner = $row["brw_partner"];
            $sinvestment_amount = $row["sinvestment_amount"];
            $dbalance_amount = $row["dbalance_amount"];
            $partner_amount = $row["partner_amount"];
            $recommender_name = $row["recommender_name"];
            $approver_name = $row["approver_name"];
            $date_approved = $row["date_approved"];
            $ccommitee_name = $row["ccommitee_name"];
            $gmanager_name = $row["gmanager_name"];
            $date_agreement_creditcom = $row["date_agreement_creditcom"];
            $amount_halaga = $row["amount_halaga"];
            $term_start = $row["term_start"];
            $term_end = $row["term_end"];
            $bdirector_name1 = $row["bdirector_name1"];
            $bdirector_name2 = $row["bdirector_name2"];
            $bdirector_name3 = $row["bdirector_name3"];
            $bdirector_name4 = $row["bdirector_name4"];
            $bdirector_name5 = $row["bdirector_name5"];
            $date_meeting = $row["date_meeting"];
            $blender_name1 = $row["blender_name1"];
            $blender_name2 = $row["blender_name2"];
            $blender_name3 = $row["blender_name3"];
            $renewal_balance_amount = $row["da_other_amount"];
        } else {
        }
    }
}

if (isset($_POST['disapprove-btn'])) {
    $loanId = $_GET['id'];
    $reason_delete = $_POST['reason-delete'];
    $delete_specify = $_POST['delete-specify'];
    $delete_reason = $reason_delete . ". " . $delete_specify;

    $adminUsername = $_SESSION["admin_username"];
    $description = "$adminUsername Disapproved Application Form ($loanId)";

    $logSql = "INSERT INTO login_logs (user_id, notif_description, comment_delete) VALUES ('$user_id', '$description', '$reason_delete')";
    $conn->query($logSql); // Assuming $conn is your database connection

    // Records updated successfully. Proceed with $sql2
    $notif_description = "Your Application Form has been DISAPPROVED ($loanId)";
    $logSql1 = "INSERT INTO notif_logs (user_id, approver, notif_description, comment_delete) VALUES ('$user_id','$adminUsername', '$notif_description', '$delete_reason')";
    $conn->query($logSql1); // Assuming $conn is your database connection
    $sql = "INSERT INTO app_disapproved_tbl SELECT * FROM app_pending_tbl where loan_id = ?";
    $sql2 = "DELETE from app_pending_tbl where loan_id = ?";

  // Prepare the statements
  $stmt = mysqli_prepare($conn, $sql);
  $stmt2 = mysqli_prepare($conn, $sql2);

  // Bind the parameters
  mysqli_stmt_bind_param($stmt, "i", $loanId);
  mysqli_stmt_bind_param($stmt2, "i", $loanId);

  // Execute the statements
  mysqli_stmt_execute($stmt);
  mysqli_stmt_execute($stmt2);

  // Close the prepared statements
  mysqli_stmt_close($stmt);
  mysqli_stmt_close($stmt2);

  // Close the database connection
  mysqli_close($conn);

  // Redirect to the applications-forapproval.php page
  header("Location: applications-pending.php");
  mysqli_stmt_close($stmt);
}


$amortizationstartingdate = "";
$amortizationstartingdate2 = "";
$amortizationstartingdate3 = "";
$amortizationstartingdate4 = "";
$amortizationstartingdate5 = "";
$amortizationstartingdate6 = "";
$amortizationstartingdate7 = "";
$amortizationstartingdate8 = "";
$amortizationstartingdate9 = "";
$amortizationstartingdate10 = "";
$amortizationstartingdate11 = "";
$amortizationstartingdate12 = "";

if (isset($_POST['approve-btn'])) {
    $loanId = $_GET['id'];
    $currentDate = date("m/d/Y"); // Format the date as YYYY-MM-DD
    $oneYearAhead = date("m/d/Y", strtotime("+1 year"));
    $nextMonthDate = date("m/d/Y", strtotime("+1 month", strtotime($currentDate)));

    //get the amortization
    $selectSql = "SELECT pn_rate_payment FROM app_pending_tbl WHERE loan_id = ?";
    $stmtSelect = mysqli_prepare($conn, $selectSql);
    mysqli_stmt_bind_param($stmtSelect, "i", $loanId);
    mysqli_stmt_execute($stmtSelect);
    mysqli_stmt_bind_result($stmtSelect, $pnRatePayment);
    mysqli_stmt_fetch($stmtSelect);
    mysqli_stmt_close($stmtSelect);

    //Amortization Schedule
     // Calculate the amortization starting date based on pn_rate_payment
     if ($pnRatePayment == "12 Months") {
        $amortizationstartingdate = date("m/d/Y", strtotime("+1 year", strtotime($currentDate)));
    } elseif ($pnRatePayment == "6 Months") {
        $amortizationstartingdate = date("m/d/Y", strtotime("+6 months", strtotime($currentDate)));
        $amortizationstartingdate2 = date("m/d/Y", strtotime("+6 months", strtotime($amortizationstartingdate)));
    } elseif ($pnRatePayment == "4 Months") {
        $amortizationstartingdate = date("m/d/Y", strtotime("+4 months", strtotime($currentDate)));
        $amortizationstartingdate2 = date("m/d/Y", strtotime("+4 months", strtotime($amortizationstartingdate)));
        $amortizationstartingdate3 = date("m/d/Y", strtotime("+4 months", strtotime($amortizationstartingdate2)));
        $amortizationstartingdate4 = date("m/d/Y", strtotime("+4 months", strtotime($amortizationstartingdate3)));
    }elseif ($pnRatePayment == "1 Month") {
        $amortizationstartingdate = date("m/d/Y", strtotime("+1 month", strtotime($currentDate)));
        $amortizationstartingdate2 = date("m/d/Y", strtotime("+1 month", strtotime($amortizationstartingdate)));
        $amortizationstartingdate3 = date("m/d/Y", strtotime("+1 month", strtotime($amortizationstartingdate2)));
        $amortizationstartingdate4 = date("m/d/Y", strtotime("+1 month", strtotime($amortizationstartingdate3)));
        $amortizationstartingdate5 = date("m/d/Y", strtotime("+1 month", strtotime($amortizationstartingdate4)));
        $amortizationstartingdate6 = date("m/d/Y", strtotime("+1 month", strtotime($amortizationstartingdate5)));
        $amortizationstartingdate7 = date("m/d/Y", strtotime("+1 month", strtotime($amortizationstartingdate6)));
        $amortizationstartingdate8 = date("m/d/Y", strtotime("+1 month", strtotime($amortizationstartingdate7)));
        $amortizationstartingdate9 = date("m/d/Y", strtotime("+1 month", strtotime($amortizationstartingdate8)));
        $amortizationstartingdate10 = date("m/d/Y", strtotime("+1 month", strtotime($amortizationstartingdate9)));
        $amortizationstartingdate11 = date("m/d/Y", strtotime("+1 month", strtotime($amortizationstartingdate10)));
        $amortizationstartingdate12 = date("m/d/Y", strtotime("+1 month", strtotime($amortizationstartingdate11)));
    }



    $dateapprovedsql = "UPDATE app_pending_tbl SET date_approved = ?, term_start = ?, term_end = ?, pn_date_payable = ?, 
    da_amortization_date1 = ?, da_amortization_date2 = ? , da_amortization_date3 = ?, da_amortization_date4 = ?, da_amortization_date5 = ?, da_amortization_date6 = ?, da_amortization_date7 = ?,
    da_amortization_date8 = ?, da_amortization_date9 = ?, da_amortization_date10 = ?, da_amortization_date11 = ?, da_amortization_date12 = ? WHERE loan_id = ?";

    // Records updated successfully. Proceed with $sql2
    $description = "Approved Application Form  ($loanId)";
    $adminUsername = $_SESSION["admin_username"];
    $logSql = "INSERT INTO login_logs (user_id, notif_description) VALUES ('$adminUsername', '$description')";
    $conn->query($logSql); // Assuming $conn is your database connection

    // Records updated successfully. Proceed with $sql2
    $notif_description = "Your Application Form has been APPROVED ($loanId)";
    $logSql1 = "INSERT INTO notif_logs (user_id, notif_description, approver) VALUES ('$user_id', '$notif_description', '$adminUsername')";
    $conn->query($logSql1); // Assuming $conn is your database connection

    $sql = "INSERT INTO app_approved_tbl SELECT * FROM app_pending_tbl where loan_id = ?";
    $sql2 = "DELETE from app_pending_tbl where loan_id = ?";

    //Add a record for Approved date
    

  // Prepare the statements
  $stmt3 = mysqli_prepare($conn, $dateapprovedsql);
  $stmt = mysqli_prepare($conn, $sql);
  $stmt2 = mysqli_prepare($conn, $sql2);
  

  // Bind the parameters
  mysqli_stmt_bind_param($stmt3, "ssssssssssssssssi", $currentDate,  $currentDate, $oneYearAhead, $currentDate, 
  $amortizationstartingdate, $amortizationstartingdate2, $amortizationstartingdate3, $amortizationstartingdate4, $amortizationstartingdate5, $amortizationstartingdate6, $amortizationstartingdate7,
  $amortizationstartingdate8, $amortizationstartingdate9, $amortizationstartingdate10, $amortizationstartingdate11, $amortizationstartingdate12, $loanId);
  mysqli_stmt_bind_param($stmt, "i", $loanId);
  mysqli_stmt_bind_param($stmt2, "i", $loanId);
  

  // Execute the statements
  mysqli_stmt_execute($stmt3);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_execute($stmt2);
  

  // Close the prepared statements
  mysqli_stmt_close($stmt3);
  mysqli_stmt_close($stmt);
  mysqli_stmt_close($stmt2);
  

  // Close the database connection
  mysqli_close($conn);

  // Redirect to the applications-forapproval.php page
  header("Location: applications-pending.php");
  mysqli_stmt_close($stmt);
}

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Admin - Applications Pending</title>
    <meta name="description" content="Applications Pending">
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
                    <h3 class="fw-bold text-dark">Applications</h3>
                    <form method="post">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Pending CA</p>
                            </div>
                            <div class="card-body">
                                <div class="col">

                                    <!-- Terms Modal -->
                                    <div class="modal fade" role="dialog" tabindex="-1" id="terms-modal">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Terms and Conditions</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="p-text">
                                                        <strong><span style="color: rgb(78, 115, 223);">1. Agreement to the Terms</span></strong>:
                                                        You acknowledge that these Terms and Conditions apply to you and that you will be bound by them by accessing and using this Credit Application and Loan Recording System of CRG-MPC.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">2. Credit Application Process</span></strong>:
                                                        Through the system, users can submit credit application forms. Applications for loans are approved or denied based on the terms and circumstances that the lending institution specifies.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">3. User Accountability</span></strong>:
                                                        The accuracy of the data entered into the loan application forms is the user's responsibility. The loan application may be denied if there is any misrepresentation.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">4. Availability of the System</span></strong>:
                                                        We make every effort to keep the system available, but we cannot promise continuous access. For maintenance purposes or for any other reason, we have the right to suspend or discontinue access.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">5. Security and Privacy</span></strong>:
                                                        We take your privacy very seriously and we maintain the confidentiality of your data. For more information on how we gather, utilize, and safeguard your personal data, please see our Data Privacy Act section.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">6. Intellectual property</span></strong>:
                                                        The contents and content found in the credit application and loan recording system belong to Calamba Rice Growers Multi-Purpose Cooperative. Without previous written permission, users are not permitted to disseminate, reproduce, or use these contents in any other way.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">7. Finalization</span></strong>:
                                                        At our discretion, we reserve the right to stop providing access to the credit application and loan recording system or to suspend it altogether.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">8. Modifications to Terms</span></strong>:
                                                        These Terms of Use could change at any time. It is recommended that users periodically check these terms for any updates.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">THE DATA PRIVACY ACT</span></strong>:
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">1. Gathering of Individual Data</span></strong>:
                                                        For the purpose of assessing and handling applications and loans, we gather personal information, such as name, contact information, financial particulars, and valid documents.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">2. Utilization of Private Data</span></strong>:
                                                        The sole aim of the information gathered is to handle credit applications, loans and other associated tasks. Without your permission, CRG-MPC will never sell or disclose your personal information to outside parties.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">3. Security Procedures</span></strong>:
                                                        CRG-MPC will put in place appropriate security measures to guard against unauthorized access, disclosure, alteration, and destruction of your personal data.
                                                        <br><br>


                                                        <strong><span style="color: rgb(78, 115, 223);">4. Information Retention</span></strong>:
                                                        CRG-MPC will keep personal data for as long as it takes to achieve the goals specified in these conditions or as long as is mandated by law.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">5. Assent</span></strong>:
                                                        You authorize the loan management system to collect and process your personal information as described in these terms, and you agree to this processing by submitting a loan application.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">6. Access and Error</span></strong>:
                                                        Users are entitled to see and update their personal data. Please send written requests to CRG-MPC for access or modification.
                                                        <br><br>

                                                        <strong><span style="color: rgb(78, 115, 223);">7. Information to Contact</span></strong>:
                                                        Please contact us at our telephone: (049) 502-3694 or reach us via email on crgmpc@technologist.com if you have any questions or issues about how we handle personal information.
                                                    </p>
                                                </div>
                                                <div class="modal-footer"><button class="btn btn-secondary btn-icon-split" id="terms-btn-close" type="button" data-bs-dismiss="modal" data-bs-target="#app-btn-modal" data-bs-toggle="modal"><span class="text-white-50 icon"><i class="fas fa-times-circle"></i></span><span class="text-white text">Close</span></button></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Disapprove Confirmation Modal -->
                                    <div class="modal fade" role="dialog" tabindex="-1" id="disapprove-modal">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Disapprove Application</h4>
                                                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="p-text">Are you sure you want to disapprove this application?</p>
                                                    <!-- You can add more details or context here if needed -->
                                                    
                                                        <label class="form-label">Reason:</label>
                                                        <select class="form-select" name="reason-delete" id="reason-delete">
                                                            <option value="Invalid / Incorrect Information">Invalid / Incorrect Information</option>
                                                            <option value="Poor Credit History">Poor Credit History</option>
                                                            <option value="Insufficient Fund of CRG-MPC">Insufficient Fund of CRG-MPC</option>
                                                            <option value="Policy Violation">Policy Violation</option>
                                                        </select>
                                                        <br>
                                                        <label class="form-label">Specify (Optional):</label>
                                                        <textarea class="form-control" rows="4" name="delete-specify" id="delete-specify"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary btn-icon-split m-1" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Cancel</span></button>
                                                    <!-- You can add an action to disapprove here -->
                                                    <button class="btn btn-danger btn-icon-split m-1" type="submit" name="disapprove-btn"><span class="text-white-50 icon"><i class="fas fa-times-circle"></i></span><span class="text-white text">Disapprove</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Approve Confirmation Modal -->
                                    <div class="modal fade" role="dialog" tabindex="-1" id="approve-modal">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Approve Application</h4>
                                                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="p-text">Are you sure you want to approve this application?</p>
                                                    <!-- You can add more details or context here if needed -->
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary btn-icon-split m-1" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Cancel</span></button>
                                                    <!-- You can add an action to disapprove here -->
                                                    <button class="btn btn-success btn-icon-split m-1" type="submit" name="approve-btn"><span class="text-white-50 icon"><i class="fas fa-check-circle"></i></span><span class="text-white text">Approve</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Loan ID</label><input class="form-control d-lg-flex" type="text" id="user-id" autocomplete="on" name="loan-id" readonly value="<?php echo $row["loan_id"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">User ID</label><input class="form-control d-lg-flex" type="text" id="user-id" autocomplete="on" name="user-id" readonly value="<?php echo $row["user_id"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Seq Nos.</label><input class="form-control d-lg-flex" type="text" id="user-id" autocomplete="on" name="seq-nos"  value="<?php echo $row["seq_nos"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">PB Lot/Nos.</label><input class="form-control d-lg-flex" type="text" id="user-id" autocomplete="on" name="lot-nos"  value="<?php echo $row["lot_nos"] ?>" disabled></div>
                                    </div>
                                    <p class="mb-3"><strong>Name</strong></p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Last Name</label><input class="form-control d-lg-flex" type="text" id="lname-1" required="" autocomplete="on" name="lname" readonly value="<?php echo $row["lname"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">First Name</label><input class="form-control d-lg-flex" type="text" id="fname-1" required="" name="fname" readonly value="<?php echo $row["fname"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Middle Name</label><input class="form-control d-lg-flex" type="text" id="mname-1" name="mname" readonly value="<?php echo $row["mname"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Suffix</label><select class="form-select d-lg-flex" id="sfxname-1" name="sfxname" disabled>
                                                <?php
                                                echo "<option value='" . $row["sfxname"] . "'>" . $row["sfxname"] . "</option>";
                                                ?>

                                            </select></div>
                                    </div>
                                    <p><strong>Address</strong></p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-5 col-style"><label class="form-label">Room / Floor / Unit No. &amp; Building Name</label><input class="form-control d-lg-flex" type="text" id="address-room" name="address-room" readonly value="<?php echo $row["address_room"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">House / Lot &amp; Block No.</label><input class="form-control d-lg-flex" type="text" id="address-house" required="" name="address-house" readonly value="<?php echo $row["address_house"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Street</label><input class="form-control d-lg-flex" type="text" id="address-street" name="address-street" readonly value="<?php echo $row["address_street"] ?>" disabled></div>
                                    </div>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-6"><label class="form-label">Subdivision</label><input class="form-control d-lg-flex" type="text" id="address-subd" name="address-subd" readonly value="<?php echo $row["address_subd"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-6"><label class="form-label">Barangay</label><select class="form-select d-lg-flex" id="address-brgy" required="" name="address-brgy" disabled>
                                                <?php
                                                echo "<option value='" . $row["address_brgy"] . "'>" . $row["address_brgy"] . "</option>";
                                                ?>
                                            </select></div>
                                    </div>
                                    <p><strong>Additional Information</strong></p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Position<span style="color: rgb(231, 74, 59);">*</span></label><select class="form-select d-lg-flex" id="position" required="" name="position" disabled>
                                                <?php
                                                echo "<option value='" . $row["position"] . "'>" . $row["position"] . "</option>";
                                                ?>
                                                <option value="Kamay-ari">Kamay-ari</option>
                                                <option value="Regular">Regular</option>
                                                <option value="Farmer">Farmer</option>
                                                <option value="Associate">Associate</option>
                                                <option value="Non-Farmer">Non-Farmer</option>
                                            </select></div>
                                            <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Loan Status<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <select class="form-select d-lg-flex" id="loan-status" required name="loan-status" disabled>
                                                <?php 
                                                echo "<option value='" . $row["loan_status"] . "'>" . $row["loan_status"] . "</option>";
                                                ?>
                                                <option value="New">New</option>
                                                <option value="Renew">Renew</option>
                                            </select>
                                        </div>
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Renewal Balance<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <input class="form-control d-lg-flex" type="number" id="balance-amount" name="renewal-balance-amount" value="<?php echo $renewal_balance_amount; ?>" disabled>
                                    </div>
                                    </div>
                                    <p class="fw-bold p-text">Ako po ay humihiling na makautang ng halagang:</p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-6 col-style"><label class="form-label">Amount</label>
                                            <div class="input-group"><span class="input-group-text">₱</span><input class="form-control" type="text" id="amount-number" name="amount-number" readonly value="<?php echo $row["amount_number"] ?>" disabled></div>
                                        </div>
                                        <div class="col d-flex flex-column justify-content-end col-md-6 col-style"><label class="form-label">Installment<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select" id="rate-installment" name="rate-installment" required disabled>
                                                    <?php 
                                                    echo "<option value='" . $row["pn_rate_installment"] . "'>" . $row["pn_rate_installment"] . "</option>";
                                                    ?>
                                                    <option value="Monthly">Monthly</option>  
                                                    <option value="Semi Annually">Semi Annually</option>
                                                    <option value="Annually">Annually</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="fw-bold p-text">na aking ipinangangakong babayaran / huhulugan kada (kinsenas, buwanan, o matapos ang pag aani ng aking sakahan) kasama ang mga kaukulang porsyento ng patubo, serbis fee at iba pa. Ang uri po ng aking paglalaanan ng puhunan ay ang mga sumusunod:</p>
                                    <!--Allocation of Loan 1-->
                                    <div class="row">
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Allocation of Loan<span style="color: rgb(231, 74, 59);">*</span></label><select class="form-select d-lg-flex" id="allocation1" required="" name="allocation1" disabled>
                                                <?php
                                                echo "<option value='" . $row["allocation_type1"] . "'>" . $row["allocation_type1"] . "</option>";
                                                ?>
                                                <option value="Lending">Lending</option>
                                                <option value="Farming">Farming</option>
                                            </select>
                                        </div>
                                        <div class="col d-flex flex-column justify-content-end col-md-8 col-style"><label class="form-label">Specify Allocation<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="sallocation1" required="" name="sallocation1" value="<?php echo $row["sallocation_type1"] ?>" disabled></div>
                                    </div>

                                    

                                    <p class="fw-bold p-text">Ang aking pinatunayan ang kawastuhan ng aking mga pahayag sa kahilingan at kasunduan sa pagbabayad at sa iba pang kapahayagang nilalaman nito.</p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Date<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control" id="date-agreement" type="date" name="date-agreement" readonly value="<?php echo $row["date_agreement"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Spouse</label><input class="form-control d-lg-flex" type="text" id="spouse-name" name="spouse-name" value="<?php echo $row["spouse_name"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Lending Partner</label><input class="form-control d-lg-flex" type="text" id="lpartner-name" name="lpartner-name" value="<?php echo $row["lpartner_name"] ?>" disabled></div>
                                    </div>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-lg-flex align-items-lg-center" id="col-style">
                                            <div class="form-check"><input class="form-check-input" type="checkbox" required="" checked disabled><label class="form-check-label" for="formCheck">I have read and agree to the&nbsp;</label></div><a class="text-decoration-none" href="#" data-bs-target="#terms-modal" data-bs-toggle="modal"><strong>Terms and Conditions</strong><span style="color: rgb(231, 74, 59);">*</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a class="btn btn-secondary btn-icon-split m-1" href="applications-pending.php"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Back</span></a>
                                <a class="btn btn-danger btn-icon-split m-1" data-bs-target="#disapprove-modal" data-bs-toggle="modal"><span class="text-white-50 icon"><i class="fas fa-times-circle"></i></span><span class="text-white text">Disapprove</span></a>
                                
                                <a class="btn btn-success btn-icon-split m-1" data-bs-target="#approve-modal" data-bs-toggle="modal"><span class="text-white-50 icon"><i class="fas fa-check-circle"></i></span><span class="text-white text">Approve</span></a>
                                
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
        let today = new Date().toISOString().slice(0, 10);

        // Set the value of the date input field to today's date
        document.getElementById('date-approved').value = today;

        //validate Loan Amount
        function validateLoanAmount() {
            // Get the input element
            var numberInput = document.getElementById("amount-number");

            // Ensure the input value is within the specified range
            if (numberInput.value < 5000) {
                numberInput.value = 5000;
            }

            if (numberInput.value > 50000) {
                numberInput.value = 50000;
            }
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

        //Validate Date Input Other
        document.addEventListener('DOMContentLoaded', function() {
            // Get today's date in the format YYYY-MM-DD
            const today = new Date().toISOString().split('T')[0];

            // Calculate the date for two months from today
            const maxDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 2);
            const twoMonthsLater = maxDate.toISOString().split('T')[0];

            // Calculate the date for two months from today
            const maxDate2 = new Date();
            maxDate2.setMonth(maxDate2.getMonth() + 12);
            const termEnd = maxDate2.toISOString().split('T')[0];

            // Set the minimum date for the input to today
            document.getElementById('date-meeting').min = today;
            document.getElementById('date-meeting').max = twoMonthsLater;

            document.getElementById('term-start').min = today;
            document.getElementById('term-start').max = twoMonthsLater;

            document.getElementById('date-recorded').min = today;
            document.getElementById('date-recorded').max = twoMonthsLater;

        });

        

        //Term End Automate
        document.addEventListener('DOMContentLoaded', function() {
            // Get today's date in the format YYYY-MM-DD
            const today = new Date().toISOString().split('T')[0];

            // Set the minimum date for the start date input to today
            document.getElementById('term-start').min = today;

            // Add an event listener to the start date input
            document.getElementById('term-start').addEventListener('input', function() {
                // Get the selected date from the start date input
                const selectedDate = new Date(this.value);

                // Calculate the date for one year from the selected date
                const oneYearLater = new Date(selectedDate);
                oneYearLater.setFullYear(oneYearLater.getFullYear() + 1);

                // Format the date to the YYYY-MM-DD format
                const endDateValue = oneYearLater.toISOString().split('T')[0];

                // Set the value and minimum date for the end date input
                document.getElementById('term-end').value = endDateValue;
                document.getElementById('term-end').min = endDateValue;

            });
        });

        document.getElementById('date-meeting').addEventListener('input', function() {
                var dateMeeting = document.getElementById('date-meeting').value;
                

                // Set the value and minimum date for the end date input
                document.getElementById('date-recorded').value = dateMeeting;

            });

       
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