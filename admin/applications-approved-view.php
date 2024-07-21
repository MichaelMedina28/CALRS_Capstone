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

// Check existence of id parameter before processing further
require_once "../db_connection.php";

if (isset($_POST['update'])) {
    $loanId = $_GET['id'];


    // Prepare the SQL UPDATE statement
    $sql = "UPDATE app_approved_tbl SET 
    ca_status = ?
    WHERE loan_id = ?";


    // Prepare the statement
    if ($stmt = mysqli_prepare($conn, $sql)) {

        // Bind the updated values
        $ca_status = $_POST["ca-status"];

        $stmt->bind_param("si", $ca_status, $loanId);
    }


    header("Location: applications-approved-update.php?id=" . $loanId);
    // Execute the prepared statement
    mysqli_stmt_execute($stmt);
}



$sql = "SELECT * FROM app_approved_tbl WHERE loan_id = ?";

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
            $user_id = $row["user_id"];
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
            $amount_text = $row["amount_text"];
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

            $pn_date_created = $row["pn_date_created"];
            $pn_loan_amount = $row["pn_loan_amount"];
            $pn_rate = $row["pn_rate"];
            $pn_rate_installment = $row["pn_rate_installment"];
            $pn_amount_installment = $row["pn_amount_installment"];
            $pn_date_payable = $row["pn_date_payable"];
            $pn_rate_payment = $row["pn_rate_payment"];
            $pn_collateral = $row["pn_collateral"];

            $pn_pesos_amount = $row["pn_pesos_amount"];
            $pn_maker_name = $row["pn_maker_name"];
            $pn_maker_address = $row["pn_maker_address"];
            $pn_spouse_name = $row["pn_spouse_name"];
            $pn_spouse_address = $row["pn_spouse_address"];
            $pn_borrower_name = $row["pn_cmaker_name1"];
            $pn_borrower_address = $row["pn_cmaker_address1"];
            $pn_borrower_name2 = $row["pn_cmaker_name2"];
            $pn_borrower_address2 = $row["pn_cmaker_address2"];

            //DISCLOSURE STATEMENT
            $da_borrower_name = $row["da_brw_name"];
            $da_borrower_address = $row["da_brw_address"];
            $da_loan_kind = $row["da_loan_kind"];
            $da_lgranted_amount = $row["da_lgranted_amount"];
            $da_interest = $row["da_interest"];
            $da_payable_annum = $row["da_payable_annum"];
            $da_start_date = $row["da_startdate"];
            $da_end_date = $row["da_enddate"];
            $da_deduction_amount = $row["da_deduction_amount"];
            $da_sfee_amount = $row["da_sfee_amount"];
            $da_cbu_amount = $row["da_cbu_amount"];
            $da_insurance_amount = $row["da_insurance_amount"];
            $da_other_amount = $row["da_other_amount"];
            $da_tdeduction_amount = $row["da_tdeduction_amount"];
            $da_nproceed_amount = $row["da_nproceed_amount"];

            $da_tinstallment_number = $row["da_tinstallment_number"];
            $da_tinstallment_amount = $row["da_tinstallment_amount"];

            $da_amortization_date1 = $row["da_amortization_date1"];
            $da_amortization_amount1 = $row["da_amortization_amount1"];
            $da_amortization_date2 = $row["da_amortization_date2"];
            $da_amortization_amount2 = $row["da_amortization_amount2"];
            $da_amortization_date3 = $row["da_amortization_date3"];
            $da_amortization_amount3 = $row["da_amortization_amount3"];

            $da_amortization_date4 = $row["da_amortization_date4"];
            $da_amortization_amount4 = $row["da_amortization_amount4"];
            $da_amortization_date5 = $row["da_amortization_date5"];
            $da_amortization_amount5 = $row["da_amortization_amount5"];
            $da_amortization_date6 = $row["da_amortization_date6"];
            $da_amortization_amount6 = $row["da_amortization_amount6"];


            $da_lofficer_name = $row["da_lofficer_name"];
            $da_brw_name_confirm = $row["da_brw_name_confirm"];
            $da_disclosure_date = $row["da_disclosure_date"];

            $ca_status = $row["ca_status"];
        } else {
        }
    }
}
if (isset($_POST['release'])) {
    $loanId = $_GET['id'];
    $sql = "INSERT INTO loan_active_tbl SELECT * FROM app_approved_tbl where loan_id = ?";

    // Prepare the statements
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "i", $loanId);

    // Execute the statements
    mysqli_stmt_execute($stmt);

    $description = "The CA, PN and DS of user ($loanId) is now complete.";
    $adminUsername = $_SESSION["admin_username"];
    $logSql = "INSERT INTO login_logs (user_id, description) VALUES ('$adminUsername', '$description')";
    $conn->query($logSql); // Assuming $conn is your database connection

    $description2 = "The disbursement of your loan ($loanId) has been scheduled. Please proceed to the office of CRG-MPC";
    $adminUsername = $_SESSION["admin_username"];
    $logSql = "INSERT INTO notif_logs (user_id, notif_description, approver) VALUES ('$user_id', '$description2', '$adminUsername')";
    $conn->query($logSql); // Assuming $conn is your database connection


    
    $updateCaStatusSql = "UPDATE app_approved_tbl SET ca_status = ? WHERE loan_id = ?";
    if ($stmtupdatecastatus = mysqli_prepare($conn, $updateCaStatusSql)) {
        $ca_status = "Released";
        $stmtupdatecastatus->bind_param("si",$ca_status, $loanId);
    }
    mysqli_stmt_execute($stmtupdatecastatus);
    mysqli_stmt_close($stmtupdatecastatus);

    // Close the prepared statements
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($conn);

    // Redirect to the applications-forapproval.php page
    header("Location: applications-approved.php");
}
// Close statement
mysqli_stmt_close($stmt);

?>


<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Admin - Applications For Approval</title>
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
                    <form id="myForm" method="post">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 fw-bold"><i class="fas fa-check-circle me-2"></i>Approved</p>
                            </div>
                            <div class="card-body">
                                <div class="accordion mb-3" role="tablist" id="Application-Form-Accordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" role="tab"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Application-Form-Accordion .item-1" aria-expanded="false" aria-controls="Application-Form-Accordion .item-1"><strong>Application Form</strong></button></h2>
                                        <div class="accordion-collapse collapse item-1" role="tabpanel" data-bs-parent="#Application-Form-Accordion">
                                            <div class="accordion-body">
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
                                                    
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Loan ID</label><input class="form-control d-lg-flex" type="text" id="user-id" autocomplete="on" name="loan-id" disabled="" value="<?php echo $row["loan_id"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">User ID</label><input class="form-control d-lg-flex" type="text" id="user-id" autocomplete="on" name="user-id" disabled="" value="<?php echo $row["user_id"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Seq Nos.</label><input class="form-control d-lg-flex" type="text" id="user-id" autocomplete="on" name="seq-nos"  value="<?php echo $row["seq_nos"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">PB Lot/Nos.</label><input class="form-control d-lg-flex" type="text" id="user-id" autocomplete="on" name="lot-nos"  value="<?php echo $row["lot_nos"] ?>" disabled></div>
                                                    </div>
                                                    <p class="mb-3"><strong>Name</strong></p>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Last Name<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="lname-1" required="" autocomplete="on" name="lname" value="<?php echo $row["lname"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">First Name<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="fname-1" required="" name="fname" value="<?php echo $row["fname"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Middle Name</label><input class="form-control d-lg-flex" type="text" id="mname-1" name="mname" value="<?php echo $row["mname"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Suffix</label><select class="form-select d-lg-flex" id="sfxname-1" name="sfxname" disabled>
                                                                <?php
                                                                echo "<option value='" . $row["sfxname"] . "'>" . $row["sfxname"] . "</option> selected";
                                                                ?>

                                                            </select></div>
                                                    </div>
                                                    <p><strong>Address</strong></p>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-5 col-style"><label class="form-label">Room / Floor / Unit No. &amp; Building Name</label><input class="form-control d-lg-flex" type="text" id="address-room" name="address-room" value="<?php echo $row["address_room"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">House / Lot &amp; Block No.<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="address-house" required="" name="address-house" value="<?php echo $row["address_house"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Street<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="address-street" name="address-street" value="<?php echo $row["address_street"] ?>" disabled></div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-6"><label class="form-label">Subdivision</label><input class="form-control d-lg-flex" type="text" id="address-subd" name="address-subd" value="<?php echo $row["address_subd"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-6"><label class="form-label">Baranggay&nbsp;<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="address-brgy" required="" name="address-brgy" value="<?php echo $row["address_brgy"] ?>" disabled></div>
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
                                                                <input class="form-control d-lg-flex" type="number" id="balance-amount" name="renewal-balance-amount" value="<?php echo $da_other_amount; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <p class="fw-bold p-text">Ako po ay humihiling na makautang ng halagang:</p>
                                                    <div class="row row-cols-1 row-cols-sm-1">

                                                        <div class="col d-flex flex-column justify-content-end col-md-6 col-style"><label class="form-label">Amount<span style="color: rgb(231, 74, 59);">*</span></label>
                                                            <div class="input-group"><span class="input-group-text">₱</span><input class="form-control" type="text" id="amount-number" name="amount-number" value="<?php echo $row["amount_number"] ?>" disabled></div>
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
                                                    <div class="row">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Allocation of Loan<span style="color: rgb(231, 74, 59);">*</span></label><select class="form-select d-lg-flex" id="allocation1" required="" name="allocation1" disabled>
                                                                <?php
                                                                echo "<option value='" . $row["allocation_type1"] . "'>" . $row["allocation_type1"] . "</option>";
                                                                ?>
                                                                <option value="Lending">Lending</option>
                                                                <option value="Farming">Farming</option>
                                                            </select></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-8 col-style"><label class="form-label">Specify Allocation<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="sallocation1" required="" name="specific-allocation1" value="<?php echo $row["sallocation_type1"] ?>" disabled></div>
                                                    </div>
                                                    
                                                    <p class="fw-bold p-text">Ang aking pinatunayan ang kawastuhan ng aking mga pahayag sa kahilingan at kasunduan sa pagbabayad at sa iba pang kapahayagang nilalaman nito.</p>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Date<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control" id="date-agreement" type="date" required="" name="date-agreement" value="<?php echo $row["date_agreement"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Spouse</label><input class="form-control d-lg-flex" type="text" id="spouse-name" name="spouse-name" value="<?php echo $row["spouse_name"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Lending Partner</label><input class="form-control d-lg-flex" type="text" id="lpartner-name" name="lpartner-name" value="<?php echo $row["lpartner_name"] ?>" disabled></div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-lg-flex align-items-lg-center" id="col-style">
                                                            <div class="form-check"><input class="form-check-input" type="checkbox" required="" checked disabled><label class="form-check-label" for="formCheck">I have read and agree to the&nbsp;</label></div><a class="text-decoration-none" href="#" data-bs-target="#terms-modal" data-bs-toggle="modal"><strong>Terms and Conditions</strong><span style="color: rgb(231, 74, 59);">*</span></a>
                                                        </div>
                                                    </div>
                                                    <p class="text-uppercase text-center my-5" id="p-text-title"><strong>for crg-mpc USE only</strong></p>
                                        
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-6 col-style"><label class="form-label">Recommended By:<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="recommender-name" required="" name="recommender-name" value="<?php echo $row["recommender_name"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-6 col-style"><label class="form-label">Approved By:<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="approver-name" required="" name="approver-name" value="<?php echo $row["approver_name"] ?>" disabled></div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Date Approved<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control" id="date-approved" type="text" required="" name="date-approved" value="<?php echo $row["date_approved"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">CREDIT COMMITEE<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="commitee-name" required="" name="ccommitee-name" value="<?php echo $row["ccommitee_name"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">GENERAL MANAGER<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="gmanager-name" required="" name="gmanager-name" value="<?php echo $row["gmanager_name"] ?>" disabled></div>
                                                    </div>
                                                    
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end justify-content-xl-start col-md-6 col-style">
                                                            <div class="row row-cols-1 row-cols-sm-1 my-3">
                                                                <div class="col d-flex flex-column justify-content-end col-md-12"><label class="form-label">Halaga<span style="color: rgb(231, 74, 59);">*</span></label>
                                                                    <div class="input-group"><span class="input-group-text">₱</span><input class="form-control" type="text" id="amount-approved" name="amount-approved" value="<?php echo $row["amount_number"] ?>" disabled></div>
                                                                </div>
                                                            </div>
                                                            <div class="row row-cols-1 row-cols-sm-1 my-3">
                                                                <div class="col d-flex flex-column justify-content-end col-md-12"><label class="form-label">Term Start<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control" id="term-start" type="text" required="" name="term-start" value="<?php echo $row["term_start"] ?>" disabled></div>
                                                            </div>
                                                            <div class="row row-cols-1 row-cols-sm-1 my-3">
                                                                <div class="col d-flex flex-column justify-content-end col-md-12"><label class="form-label">Term End<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control" id="term-end" type="text" required="" name="term-end" value="<?php echo $row["term_end"] ?>" disabled></div>
                                                            </div>
                                                        </div>
                                                        <div class="col d-flex flex-column justify-content-end justify-content-xl-start col-md-6 col-style">
                                                            <div class="row row-cols-1 row-cols-sm-1 my-3">
                                                                <div class="col d-flex flex-column justify-content-end col-md-12"><label class="form-label">Board of Directors<span style="color: rgb(231, 74, 59);">*</span></label>
                                                                    <div class="input-group mb-3"><span class="input-group-text">Name</span><input class="form-control" type="text" id="bdirector-name1" required="" name="bdirector-name1" value="<?php echo $row["bdirector_name1"] ?>" disabled></div>
                                                                    <div class="input-group mb-3"><span class="input-group-text">Name</span><input class="form-control" type="text" id="bdirector-name2" required="" name="bdirector-name2" value="<?php echo $row["bdirector_name2"] ?>" disabled></div>
                                                                    <div class="input-group mb-3"><span class="input-group-text">Name</span><input class="form-control" type="text" id="bdirector-name3" required="" name="bdirector-name3" value="<?php echo $row["bdirector_name3"] ?>" disabled></div>
                                                                    
                                                                    <div class="col d-flex flex-column justify-content-end col-md-12 mt-2"><label class="form-label">Board of Lenders<span style="color: rgb(231, 74, 59);">*</span></label>
                                                                    <div class="input-group mb-3"><span class="input-group-text">Name</span><input class="form-control" type="text" id="blender-name1" required="" name="blender-name1" value="<?php echo $row["blender_name1"] ?>" disabled></div>
                                                                </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" role="tab"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Application-Form-Accordion .item-2" aria-expanded="false" aria-controls="Application-Form-Accordion .item-2"><strong>Promissory Note</strong></button></h2>
                                        <div class="accordion-collapse collapse item-2" role="tabpanel" data-bs-parent="#Application-Form-Accordion">
                                            <div class="accordion-body">
                                                <div>
                                                    <div class="row row-cols-1 row-cols-sm-1 d-xl-flex justify-content-xl-end">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">Date<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control" id="pn-date-created" type="text" name="pn-date-created" value="<?php echo $row["date_approved"] ?>" disabled></div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">Amount<span style="color: rgb(231, 74, 59);">*</span></label>
                                                            <div class="input-group"><span class="input-group-text">₱</span><input class="form-control" type="text" id="loan-amount" name="pn-loan-amount" disabled value="<?php echo $row["amount_number"] ?>"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end">
                                                            <p class="fw-bold p-text">For value received, we jointly and severally, promise to pay to the Calamba Rice Growers Multi Purpose Cooperative, or order, the sum of Pesos at the rate of</p>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">Rate (%)<span style="color: rgb(231, 74, 59);">*</span></label><select class="form-select d-lg-flex" id="rate" name="pn-rate" disabled>
                                                                <option value="<?php echo $row["pn_rate"] ?>"><?php echo $row["pn_rate"] ?></option>
                                                            </select></div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end">
                                                            <p class="fw-bold p-text">per month, payable in</p>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">Installments<span style="color: rgb(231, 74, 59);">*</span></label>
                                                            <select class="form-select d-lg-flex" id="rate-installment" name="pn-rate-installment" disabled>
                                                                <?php
                                                                echo "<option value='" . $row["pn_rate_installment"] . "'>" . $row["pn_rate_installment"] . "</option>";
                                                                ?>
                                                            </select></div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end">
                                                            <p class="fw-bold p-text">Installments of Pesos</p>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4">
                                                            <label class="form-label">Amount<span style="color: rgb(231, 74, 59);">*</span></label>
                                                            <input class="form-control d-lg-flex" type="text" id="pn_amount-installment" name="pn-amount-installment" disabled value="<?php echo $pn_amount_installment ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end">
                                                            <p class="fw-bold p-text">payment to be made on starting</p>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">Date<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control" id="date-payable" type="text" name="pn-date-payable" readonly value="<?php echo $row["pn_date_payable"] ?>" disabled></div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end">
                                                            <p class="fw-bold p-text">and every</p>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4">
                                                            <label class="form-label">Payment Frequency</label>
                                                            <select class="form-select d-lg-flex" id="pn-rate-payment" disabled name="pn-rate-payment">
                                                                <?php
                                                                echo "<option value='" . $row["pn_rate_payment"] . "'>" . $row["pn_rate_payment"] . "</option>";
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end">
                                                            <p class="fw-bold p-text">In case of any default in payments as herein agreed, the entire balance of this note shall become immediately due and payable, at the option of the cooperative. Each party to this note whether as maker, co-maker, endorser or guarantor severally waives presentation of payment, demand, protest and notice of protest and dishonor of the same.</p>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end">
                                                            <p class="fw-bold p-text">It is further agreed by party hereto, that in case payment shall not be made at maturity, he shall pay the cost of collection, and attorney's fees in an amount equal to twenty percent of the principal and interest due on this note, but such charge in no event to be less than</p>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">PESOS<span style="color: rgb(231, 74, 59);">*</span></label>
                                                            <div class="input-group"><span class="input-group-text">₱</span><input class="form-control" type="text" id="pesos-amount" name="pn-pesos-amount" disabled value="<?php echo $row["pn_pesos_amount"] ?>"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end">
                                                            <p class="fw-bold p-text">In case of judicial execution of this obligation or any part of it the debtor waives all his rights under the provisions of Rule 3, Section 13 and Rule 39, Section 12 of the Rules of Court</p>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">Name of Maker<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="maker-name" name="pn-maker-name" disabled value="<?php echo $row["pn_maker_name"] ; ?>"></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-8"><label class="form-label">Address<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="maker-address" name="pn-maker-address" disabled value="<?php echo $row["pn_maker_address"]; ?>"></div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">Name of Spouse (optional)</label><input class="form-control d-lg-flex" type="text" id="spouse-name" name="pn-spouse-name" value="<?php echo $row["spouse_name"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-8"><label class="form-label">Address&nbsp;(optional)</label><input class="form-control d-lg-flex" type="text" id="spouse-address" name="pn-spouse-address" value="<?php echo $row["pn_spouse_address"] ?>" disabled></div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">Name of Co-maker&nbsp;</label><input class="form-control d-lg-flex" type="text" id="cmaker-name1" name="pn-cmaker-name1" disabled value="<?php echo $row["lpartner_name"] ?>" required></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-8"><label class="form-label">Address&nbsp;(optional)</label><input class="form-control d-lg-flex" type="text" id="cmaker-address1" name="pn-cmaker-address1" value="<?php echo $row["pn_cmaker_address1"] ?>" disabled></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" role="tab"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#Application-Form-Accordion .item-3" aria-expanded="false" aria-controls="Application-Form-Accordion .item-3"><strong>Disclosure Statement</strong></button></h2>
                                        <div class="accordion-collapse collapse item-3" role="tabpanel" data-bs-parent="#Application-Form-Accordion">
                                            <div class="accordion-body">
                                                <div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-8"><label class="form-label"><strong>BORROWER:</strong><span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="borrower-name" name="da-borrower-name" readonly value="<?php echo $row["da_brw_name"]; ?>" disabled></div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-8"><label class="form-label"><strong>ADDRESS:</strong><span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="borrower-address" name="da-borrower-address" readonly value="<?php echo $row["da_brw_address"]; ?>" disabled></div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-8"><label class="form-label"><strong>KIND OF LOAN:</strong><span style="color: rgb(231, 74, 59);">*</span></label>
                                                            <select class="form-select d-lg-flex" id="da-loan-kind" name="da-loan-kind" disabled>
                                                                <?php
                                                                echo "<option value='" . $row["sallocation_type1"] . "'>" . $row["sallocation_type1"] . "</option>";
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-9">
                                                            <p><strong>1.) LOAN GRANTED</strong></p>
                                                        </div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3">
                                                            <div class="input-group"><span class="input-group-text">₱</span><input class="form-control" type="text" id="da-lgranted-amount" name="da-lgranted-amount" disabled value="<?php echo $row["amount_number"] ?>"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-9">
                                                            <p><strong>2.) FINANCE CHARGES</strong></p>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-3"><label class="form-label"><strong><span style="color: rgb(78, 115, 223);">a.</span></strong> Interest (%)&nbsp;at<span style="color: rgb(231, 74, 59);">*</span></label>
                                                            <div class="input-group"><span class="input-group-text">%</span>
                                                                <select class="form-select d-lg-flex" id="interest" name="da-interest" disabled>
                                                                    <option value="<?php echo $row["pn_rate"] ?>"><?php echo $row["pn_rate"] ?>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-6">
                                                            <p><strong>Deduction from Proceeds</strong></p>
                                                        </div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3">
                                                            <div class="input-group"><span class="input-group-text">₱-</span><input class="form-control" type="number" id="da-deduction-amount" name="da-deduction-amount" value="<?php echo $row["da_deduction_amount"] ?>" disabled></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-9">
                                                            <p><strong>3.) NON-FINANCE CHARGES</strong></p>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-6">
                                                            <p id="p-text-31"><strong>a.<span style="color: rgb(133, 135, 150);">&nbsp;Service Fee</span></strong><span style="color: rgb(231, 74, 59);">*</span></p>
                                                        </div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3">
                                                            <div class="input-group"><span class="input-group-text">₱-</span><input class="form-control" type="number" id="da-sfee-amount" name="da-sfee-amount" disabled value="<?php echo $row["da_sfee_amount"] ?>"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-6">
                                                            <p id="p-text-32"><strong>b.<span style="color: rgb(133, 135, 150);">&nbsp;CBU</span></strong><span style="color: rgb(231, 74, 59);">*</span></p>
                                                        </div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3">
                                                            <div class="input-group"><span class="input-group-text">₱-</span><input class="form-control" type="number" id="da-cbu-amount" name="da-cbu-amount" disabled value="<?php echo $row["da_cbu_amount"] ?>"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-6">
                                                            <p id="p-text-32"><strong>c.<span style="color: rgb(133, 135, 150);">&nbsp;Insurance</span></strong><span style="color: rgb(231, 74, 59);">*</span></p>
                                                        </div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3">
                                                            <div class="input-group"><span class="input-group-text">₱-</span><input class="form-control" type="number" id="da-cbu-amount" name="da-cbu-amount" disabled value="<?php echo $row["da_insurance_amount"] ?>"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-6">
                                                            <p id="p-text-32"><strong>d.<span style="color: rgb(133, 135, 150);">&nbsp;Renewal Balance</span></strong><span style="color: rgb(231, 74, 59);">*</span></p>
                                                        </div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3">
                                                            <div class="input-group"><span class="input-group-text">₱-</span><input class="form-control" type="number" id="da-cbu-amount" name="da-cbu-amount" disabled value="<?php echo $row["da_other_amount"] ?>"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-9">
                                                            <p><strong>4.) TOTAL DEDUCTION FROM PROCEEDS OF LOAN</strong></p>
                                                        </div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3">
                                                            <div class="input-group"><span class="input-group-text">₱-</span><input class="form-control" type="text" id="da-tdeduction-amount" name="da-tdeduction-amount" readonly value="<?php echo $row["da_tdeduction_amount"] ?>" disabled></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-9">
                                                            <p><strong>5.) NET PROCEEDS OF LOAN</strong></p>
                                                        </div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-3">
                                                            <div class="input-group"><span class="input-group-text">₱</span><input class="form-control" type="text" id="da-nproceed-amount" name="da-nproceed-amount" value="<?php echo $row["da_nproceed_amount"] ?>" disabled></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-8">
                                                            <p><strong>6.) SCHEDULE OF PAYMENT</strong></p>
                                                        </div>
                                                    </div>
                                                    <!--<div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><div class="form-check"><input class="form-check-input" id="single-payment-radio" type="radio" name="schedule-payment"><label class="form-label"><strong><span style="color: rgb(78, 115, 223);">a.</span></strong> Single payment due on<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control" id="spayment-date" type="date" name="da-spayment-date" value="<?php echo $row["da_spayment_date"] ?>"></div></div>
                                                    </div>-->
                                                    
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-8"><label class="col-form-label"><strong><span style="color: rgb(78, 115, 223);"></span></strong> Amortization Schedule<span style="color: rgb(231, 74, 59);">*</span></label></div>
                                                    </div>


                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date1" type="text" name="da-amortization-date1" value="<?php echo $row["da_amortization_date1"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="text" id="amortization-amount1" name="da-amortization-amount1" value="<?php echo $row["da_amortization_amount1"] ?>" disabled></div>
                                                        </div>

                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date2" type="text" name="da-amortization-date2" value="<?php echo $row["da_amortization_date2"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="text" id="amortization-amount2" name="da-amortization-amount2" value="<?php echo $row["da_amortization_amount1"] ?>" disabled></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date3" type="text" name="da-amortization-date3" value="<?php echo $row["da_amortization_date3"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="text" id="amortization-amount3" name="da-amortization-amount3" value="<?php echo $row["da_amortization_amount1"] ?>" disabled></div>
                                                        </div>

                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date4" type="text" name="da-amortization-date4" value="<?php echo $row["da_amortization_date4"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="text" id="amortization-amount4" name="da-amortization-amount4" value="<?php echo $row["da_amortization_amount1"] ?>" disabled></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date5" type="text" name="da-amortization-date5" value="<?php echo $row["da_amortization_date5"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="number" id="amortization-amount5" name="da-amortization-amount5" value="<?php echo str_replace(',', '', $row["da_amortization_amount1"]) ?>" disabled></div>
                                                        </div>

                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date6" type="text" name="da-amortization-date6" value="<?php echo $row["da_amortization_date6"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="number" id="amortization-amount6" name="da-amortization-amount6" value="<?php echo str_replace(',', '', $row["da_amortization_amount1"]) ?>" disabled></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date7" type="text" name="da-amortization-date7" value="<?php echo $row["da_amortization_date7"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="number" id="amortization-amount7" name="da-amortization-amount7" value="<?php echo str_replace(',', '', $row["da_amortization_amount1"]) ?>" disabled></div>
                                                        </div>

                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date8" type="text" name="da-amortization-date8" value="<?php echo $row["da_amortization_date8"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="number" id="amortization-amount8" name="da-amortization-amount8" value="<?php echo str_replace(',', '', $row["da_amortization_amount1"]) ?>" disabled></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date9" type="text" name="da-amortization-date9" value="<?php echo $row["da_amortization_date9"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="number" id="amortization-amount9" name="da-amortization-amount9" value="<?php echo str_replace(',', '', $row["da_amortization_amount1"]) ?>" disabled></div>
                                                        </div>

                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date10" type="text" name="da-amortization-date10" value="<?php echo $row["da_amortization_date10"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="number" id="amortization-amount10" name="da-amortization-amount10" value="<?php echo str_replace(',', '', $row["da_amortization_amount1"]) ?>" disabled></div>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date11" type="text" name="da-amortization-date11" value="<?php echo $row["da_amortization_date11"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="number" id="amortization-amount11" name="da-amortization-amount11" value="<?php echo str_replace(',', '', $row["da_amortization_amount1"]) ?>" disabled></div>
                                                        </div>

                                                        <div class="col d-flex flex-column justify-content-end col-md-2"><input class="form-control" id="amortization-date12" type="text" name="da-amortization-date12" value="<?php echo $row["da_amortization_date12"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-2">
                                                            <div class="input-group"><input class="form-control" type="number" id="amortization-amount12" name="da-amortization-amount12" value="<?php echo str_replace(',', '', $row["da_amortization_amount1"]) ?>" disabled></div>
                                                        </div>
                                                    </div>
                                                    <!--
                                                    <div class="row row-cols-1 row-cols-sm-1">
                                                        <div class="col d-flex flex-column justify-content-end col-md-8">
                                                            <p><strong>7.) ADDITIONAL CHARGES</strong></p>
                                                        </div>
                                                    </div>
                                                    <div class="row row-cols-1 row-cols-sm-1 mb-3">
                                                        <div class="col d-flex flex-column justify-content-end col-md-9"><textarea class="form-control" id="addtional-charges" name="da-additional-charges"><?php echo $row["da_addtional_charges"] ?></textarea></div>
                                                    </div>-->
                                                    <br>
                                                    
                                                    <div class="row row-cols-1 row-cols-sm-1 mb-3">
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">Name of <strong>Loan Officer</strong><span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="lofficer-name" name="da-lofficer-name" value="<?php echo $row["ccommitee_name"] ?>" disabled></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">Name of <strong>Borrower</strong><span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="borrower-name-confirm" name="da-borrower-name-confirm" disabled value="<?php echo$row["da_brw_name"] ?>"></div>
                                                        <div class="col d-flex flex-column justify-content-end col-md-4"><label class="form-label">Date<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control" id="disclosure-date" type="text" name="da-disclosure-date" disabled value="<?php echo $row["date_approved"] ?>" ></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col d-xl-flex align-items-xl-center col-md-6">
                                        <div class="form-check">
                                            <label class="form-label">Disbursement&nbsp;Status</label>
                                            <select class="form-select d-lg-flex" id="ca-status" name="ca-status" disabled>
                                                <?php
                                                echo "<option value='" . $row["ca_status"] . "'>" . $row["ca_status"] . "</option>";
                                                ?>
                                                <option value="For Release">For Release</option>
                                            </select>


                                        </div>
                                    </div>
                                    <!--<div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">CA&nbsp;Status</label><select class="form-select d-lg-flex" id="ca-status" name="ca-status">
                                            <option value="Semi-Approved">Semi-Approved</option>
                                            <option value="Approved">Approved</option>
                                        </select></div>-->
                                </div>
                            </div>
                            <div class="card-footer">
                                <a class="btn btn-secondary btn-icon-split m-1" href="applications-approved.php"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Back</span></a>
                                <a class="btn btn-primary btn-icon-split m-1" id="print-ca" href="print-ca.php?id=<?php echo $row["loan_id"];?>"><span class="text-white-50 icon"><i class="fas fa-print"></i></span><span class="text-white text">Print CA</span></a>
                                <a class="btn btn-primary btn-icon-split m-1" id="print-pn" href="print-pn.php?id=<?php echo $row["loan_id"];?>"><span class="text-white-50 icon"><i class="fas fa-print"></i></span><span class="text-white text">Print PN</span></a>
                                <a class="btn btn-primary btn-icon-split m-1" id="print-ds" href="print-ds.php?id=<?php echo $row["loan_id"];?>"><span class="text-white-50 icon"><i class="fas fa-print"></i></span><span class="text-white text">Print DS</span></a>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
//amortization schedule
function amortizationDateSet() {
  const installmentNumberInput = document.getElementById('rate-installment');
  const amortizationDateInputs = [];
  const amortizationAmountInputs = [];

  // Initialize the amortization date and amount inputs
  for (let i = 1; i <= 12; i++) {
    amortizationDateInputs[i] = document.getElementById(`amortization-date${i}`);
    amortizationAmountInputs[i] = document.getElementById(`amortization-amount${i}`);
  }

  // Function to initially hide all inputs
  function hideAllInputs() {
    for (let i = 1; i <= 12; i++) {
      amortizationDateInputs[i].style.display = 'none';
      amortizationAmountInputs[i].style.display = 'none';
    }
  }

  // Function to show inputs based on installment number
  function showInputs(installmentNumber) {
    for (let i = 1; i <= 12; i++) {
      if (i <= installmentNumber) {
        amortizationDateInputs[i].style.display = 'block';
        amortizationAmountInputs[i].style.display = 'block';
      } else {
        amortizationDateInputs[i].style.display = 'none';
        amortizationAmountInputs[i].style.display = 'none';
      }
    }
  }

  // Initially hide all inputs
  hideAllInputs();

  // Add event listener to the installment number input
  installmentNumberInput.addEventListener('change', updateInputFields);

  // Function to update inputs when the installment number changes
  function updateInputFields() {
    const selectedInstallment = installmentNumberInput.value;
    let installmentNumber;

    if (selectedInstallment === 'Monthly') {
      installmentNumber = 12;
    } else if (selectedInstallment === 'Quarterly') {
      installmentNumber = 4;
    } else if (selectedInstallment === 'Semi Annually') {
      installmentNumber = 2;
    } else if (selectedInstallment === 'Annually') {
      installmentNumber = 1;
    } else {
      installmentNumber = 0;
    }

    showInputs(installmentNumber);
  }

  // Show inputs based on the default value
  const defaultInstallment = installmentNumberInput.value;
  let defaultInstallmentNumber;

  if (defaultInstallment === 'Monthly') {
    defaultInstallmentNumber = 12;
  } else if (defaultInstallment === 'Quarterly') {
    defaultInstallmentNumber = 4    ;
  }else if (defaultInstallment === 'Semi Annually') {
    defaultInstallmentNumber = 2;
  } else if (defaultInstallment === 'Annually') {
    defaultInstallmentNumber = 1;
  } else {
    defaultInstallmentNumber = 0;
  }

  showInputs(defaultInstallmentNumber);
}

// Call the amortizationDateSet() function to initialize the inputs
amortizationDateSet();

//end of amortization schedule code

    //buttons to hide 
    var caStatusvalue = document.getElementById("ca-status").value;
    var printCA = document.getElementById("print-ca");
    var printPN = document.getElementById("print-pn");
    var printDS = document.getElementById("print-ds");

    if (caStatusvalue === "For Released") {
        printCA.style.display = "none";
        printPN.style.display = "none";
        printDS.style.display = "none";
    } else if (caStatusvalue === "Released") {
        printCA.style.display = "inline-block";
        printPN.style.display = "inline-block";
        printDS.style.display = "inline-block";
    } else if (caStatusvalue === "Cancelled") {
        printCA.style.display = "inline-block";
        printPN.style.display = "inline-block";
        printDS.style.display = "inline-block";
    } else {
        printCA.style.display = "none";
        printPN.style.display = "none";
        printDS.style.display = "none";
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