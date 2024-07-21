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
$sql = "SELECT * FROM lo_app_pending_tbl WHERE loan_id = ?";
    
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

        }
    }
}


//Lending partner Names
// Fetching user data from your database
$query = "SELECT user_fname, user_lname, user_mname FROM user_tbl ORDER BY user_fname"; // Replace 'user_tbl' with your actual table name
$resultlname = mysqli_query($conn, $query);

// Generating options for the select tag
$options = '';
while ($rowname = mysqli_fetch_assoc($resultlname)) {
    // Check if the current row corresponds to the user's name
    if ($rowname['user_fname'] == $fname && $rowname['user_lname'] == $lname && $rowname['user_mname'] == $mname) {
        continue; // Skip this row
    }

    $fullName = $rowname['user_fname'] . ' ' . $rowname['user_mname'] . ' ' . $rowname['user_lname'];
    $options .= "<option value='$fullName'>$fullName</option>";
}


//account picture
$otherTableSql = "SELECT * FROM user_tbl WHERE user_id = ?";

if ($otherStmt = mysqli_prepare($conn, $otherTableSql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($otherStmt, "i", $param_user_id);

    // Set parameters for the new statement
    $param_user_id = $user_id; // Assuming $user_id is the value you want to use in the new statement

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($otherStmt)) {
        $otherResult = mysqli_stmt_get_result($otherStmt);

        // Process the result as needed
        while ($otherRow = mysqli_fetch_array($otherResult, MYSQLI_ASSOC)) {
            // Access data from 'other_table' row
            $account_pictures = $otherRow["account_pictures"];
            $share_investment = $otherRow["share_investment"];
            // ... continue as needed
        }

        // Close the result set
        mysqli_free_result($otherResult);
    } else {
        echo "Error executing the other statement: " . mysqli_error($conn);
    }

}

if (isset($_POST['save-btn'])) {
    $loanId = $_GET['id'];
    // Prepare the SQL UPDATE statement
    $sqlupdate = "UPDATE lo_app_pending_tbl SET position = ?, amount_number = ?, allocation_type1 = ?, sallocation_type1 = ?, spouse_name = ?, lpartner_name = ?, pn_rate = ?, pn_rate_installment = ?, pn_amount_installment = ?,
    pn_rate_payment = ?, pn_pesos_amount = ?, da_deduction_amount = ?, da_tdeduction_amount = ?, da_nproceed_amount = ?, da_amortization_amount1 = ? , loan_status = ? WHERE loan_id = ?";

    if ($stmt1 = mysqli_prepare($conn, $sqlupdate)) {
        //additional information
        $position = $_POST['position'];
        $amount_number = $_POST['amount-number'];
        $rate_installment = $_POST['rate-installment'];

        //Allocation of Loan
        $allocation_type1 = $_POST['allocation1'];
        
        //Specific Allocation
        $sallocation_type1 = $_POST['sallocation1'];

        $spouse_name = $_POST['spouse-name'];
        $lpartner_name = $_POST['lpartner-name'];
        $rate_installment = $_POST['rate-installment'];

        $loan_status = $_POST['loan-status'];
        //Payment Method
        if ($rate_installment === "Monthly") {
            $amount_number = str_replace(',', '', $amount_number); // Remove the comma
            $amount_number = floatval($amount_number); // Convert to float
            $amount_number = intval($amount_number); // Convert to integer
            $amount_installment = $amount_number / 12;
            $amount_installment = number_format($amount_installment, 2);
        } elseif ($rate_installment === "Quarterly") {
            $amount_number = str_replace(',', '', $amount_number); // Remove the comma
            $amount_number = floatval($amount_number); // Convert to float
            $amount_number = intval($amount_number); // Convert to integer
            $amount_installment = $amount_number / 4;
            $amount_installment = number_format($amount_installment, 2);
        }elseif ($rate_installment === "Semi Annually") {
            $amount_number = str_replace(',', '', $amount_number); // Remove the comma
            $amount_number = floatval($amount_number); // Convert to float
            $amount_number = intval($amount_number); // Convert to integer
            $amount_installment = $amount_number / 2;
            $amount_installment = number_format($amount_installment, 2);
        } elseif ($rate_installment === "Annually") {
            $amount_number = str_replace(',', '', $amount_number); // Remove the comma
            $amount_number = floatval($amount_number); // Convert to float
            $amount_installment = intval($amount_number);
            $amount_installment = $amount_installment;
        }

        //Percent Rate
        if ($allocation_type1 === "Farming") {
            $paymentrate = "2";
            $paymentratepercent = "$paymentrate%";
            $paymentratedecimal = "0.02";
        } elseif ($allocation_type1 === "Lending") {
            $paymentrate = "1.5";
            $paymentratepercent = "$paymentrate%";
            $paymentratedecimal = "0.015";
        }

        //Rate Payment In Months
        if ($rate_installment === "Monthly") {
            $paymentMonth = "1 Month";
        }elseif ($rate_installment === "Quarterly") {
            $paymentMonth = "4 Months";
        } elseif ($rate_installment === "Semi Annually") {
            $paymentMonth = "6 Months";
        } elseif ($rate_installment === "Annually") {
            $paymentMonth = "12 Months";
        }

        //Penalty Amount Pesos
        $wholeamount_number = str_replace(',', '', $amount_number); // Remove the comma
        $float_amount_number = floatval($wholeamount_number);
        $penalty_amount = $float_amount_number * 0.2;
        $penalty_amount = number_format($penalty_amount, 2);

        //Deduction from Proceeds
        $deduction_amount = $paymentratedecimal * $wholeamount_number;
        $deduction_amountfinal = number_format($deduction_amount, 2);

        //Non-Finance Charges
        $sfee = "200.00";
        $cbu = "200.00";

        //Total Deduction
        $totalDeduction = $cbu + $sfee + $deduction_amountfinal;
        $totalDeduction = number_format($totalDeduction, 2);
        //Net Proceed
        $netProceed = $wholeamount_number - $totalDeduction;
        $caStatus = "Approved";

        if($paymentMonth === "12 Months"){
            $halfnetProceed = $netProceed;
            $halfnetProceed = number_format($halfnetProceed, 2);
            $amortamount = $halfnetProceed;
        } elseif($paymentMonth === "6 Months"){
            $halfnetProceed = $netProceed / 2;
            $halfnetProceed = number_format($halfnetProceed, 2);
            $amortamount = $halfnetProceed;
        } elseif ($paymentMonth === "4 Months"){
            $monthlynetProceed = $netProceed / 4;
            $monthlynetProceed = number_format($monthlynetProceed, 2);
            $amortamount = $monthlynetProceed;
        }elseif ($paymentMonth === "1 Month"){
            $monthlynetProceed = $netProceed / 12;
            $monthlynetProceed = number_format($monthlynetProceed, 2);
            $amortamount = $monthlynetProceed;
        }
        $amount_number = floatval($amount_number); // Convert to float
        $amount_number = number_format($amount_number, 2);
        echo "Error: " . mysqli_error($conn);

        //For Activity Logs
        $description = "Editted Application Form ($userLoginId).";
        $logSql = "INSERT INTO login_logs (user_id, notif_description) VALUES ('$user_id', '$description')";
        $conn->query($logSql); // Assuming $conn is your database connection


        // Updated bind_param with correct types and order
        mysqli_stmt_bind_param($stmt1, "ssssssssssssssssi", $position, $amount_number, $allocation_type1, $sallocation_type1, $spouse_name, $lpartner_name, $paymentratepercent, $rate_installment, $amount_installment,
        $paymentMonth, $penalty_amount, $deduction_amountfinal, $totalDeduction, $netProceed, $amortamount, $loan_status, $loanId);

        if (mysqli_stmt_execute($stmt1)) {
            header("Location: applications-manage-view.php?id=" . $loan_id);
            exit(); // Ensure code execution stops after redirection
        } else {
            echo "Error executing statement: " . mysqli_stmt_error($stmt1); // Display error for debugging
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn); // Display error for debugging
    }
}

//chech the share on investment
if ($share_investment < "20000") {
    $inputsDisabled = true;

    $submitCaLimitError = "You are not qualify to apply a loan!";
}

$seventypercent = $share_investment * 0.75;

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
    <title>User - Application Manage</title>
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
            <div class="container d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="dashboard.php?id=<?php echo $userLoginId; ?>">
                    <div class="sidebar-brand-icon"><img class="border rounded-circle" id="nav-img" src="../assets/img/logo/logo.png"></div>
                    <div class="sidebar-brand-text mx-3"><span>CRG-MPC</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <!-- <ul class="navbar-nav text-light" id="accordionSidebar" >
                    <li class="nav-item"><a class="nav-link" href="dashboard.php?id=<?php echo $userLoginId; ?>"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-1" href="#collapse-1" role="button"><i class="fas fa-file-alt"></i>&nbsp;<span>Applications</span></a>
                            <div class="collapse" id="collapse-1">
                                <div class="bg-white border rounded py-2 collapse-inner">
                                    <h6 class="collapse-header">APPLICATIONS</h6>
                                    <a class="collapse-item" href="applications-create.php?id=<?php echo $userLoginId; ?>" >Create CA</a>
                                    <a class="collapse-item" href="applications-manage.php?id=<?php echo $userLoginId; ?>" >Manage CA</a>
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
                <div class="text-center d-none d-md-inline"><button class="btn" id="sidebarToggle" type="button"></button></div> -->
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
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-fname"><?php echo $_SESSION["userfname"];?></span><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-lname"><?php echo $_SESSION["userlname"];?></span></div>
                                            </div>
                                            <div class="row" id="nav-profile">
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-acc">USER</span></div>
                                            </div>
                                        </div><img class="border rounded-circle img-profile" id="nav-profile-img" src="../admin/<?php echo $account_pictures ?>" alt="../assets/img/profile/profile-default.png" name="nav-profile-img">
                                    </a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a class="dropdown-item" href="profile.php?id=<?php echo $userLoginId; ?>"><i class="fas fa-user fa-sm fa-fw" id="profile-icon"></i>Profile</a>
                                    <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="user-notification.php?id=<?php echo $userLoginId; ?>" id="notificationLink">
                                        <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Notifications</a>
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
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style">
                                            <label class="form-label">Loan ID</label>
                                            <input class="form-control d-lg-flex" type="text" id="loan-id" autocomplete="on" name="user-id" disabled="" value="<?php echo $row["loan_id"] ?>">
                                        </div>
                                    </div>
                                    <p class="mb-3"><strong>Name</strong></p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Last Name<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="lname-1" required="" autocomplete="on" name="lname" value="<?php echo $row["lname"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">First Name<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control d-lg-flex" type="text" id="fname-1" required="" name="fname" value="<?php echo $row["fname"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Middle Name</label><input class="form-control d-lg-flex" type="text" id="mname-1" name="mname" value="<?php echo $row["mname"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Suffix</label><select class="form-select d-lg-flex" id="sfxname-1" name="sfxname" disabled>
                                                <?php 
                                                echo "<option value='" . $row["sfxname"] . "'>" . $row["sfxname"] . "</option>";
                                                ?>
                                                <option value="">N/A</option>
                                                <option value="Jr.">Jr.</option>
                                                <option value="Sr.">Sr.</option>
                                                <option value="II">II</option>
                                                <option value="III">III</option>
                                                <option value="IV">IV</option>
                                                <option value="V">V</option>
                                                <option value="VI">VI</option>
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
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Position<span style="color: rgb(231, 74, 59);">*</span></label><select class="form-select d-lg-flex" id="position" required="" name="position" disabled>
                                                <?php 
                                                echo "<option value='" . $row["position"] . "'>" . $row["position"] . "</option>";
                                                ?>
                                                <option value="Kamay-ari">Kamay-ari</option>
                                                <option value="Regular">Regular</option>
                                                <option value="Farmer">Farmer</option>
                                                <option value="Associate">Associate</option>
                                                <option value="Non-Farmer">Non-Farmer</option>
                                            </select>
                                        </div>
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Loan Status<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <select class="form-select d-lg-flex" id="loan-status" required name="loan-status" disabled>
                                                <?php 
                                                echo "<option value='" . $row["loan_status"] . "'>" . $row["loan_status"] . "</option>";
                                                ?>
                                                <option value="New">New</option>
                                                <option value="Renew">Renew</option>
                                            </select>
                                        </div>
                                    </div>
                                    <p class="fw-bold p-text">Ako po ay humihiling na makautang ng halagang:</p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-6 col-style"><label class="form-label">Amount<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input class="form-control" type="text" id="amount-number" name="amount-number" min="5000" max="<?php echo $seventypercent; ?>" value="<?php echo $row["amount_number"] ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col d-flex flex-column justify-content-end col-md-6 col-style"><label class="form-label">Installment<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select" id="rate-installment" name="rate-installment" required disabled>
                                                    <?php 
                                                    echo "<option value='" . $row["pn_rate_installment"] . "'>" . $row["pn_rate_installment"] . "</option>";
                                                    ?>
                                                    <option value="Monthly">Monthly</option>  
                                                    <option value="Quarterly">Quarterly</option>  
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
                                            </select>
                                        </div>
                                        <div class="col d-flex flex-column justify-content-end col-md-8 col-style"><label class="form-label">Specify Allocation<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <select class="form-control d-lg-flex" id="specific-allocation" required name="sallocation1" disabled>
                                                <?php 
                                                echo "<option value='" . $row["sallocation_type1"] . "'>" . $row["sallocation_type1"] . "</option>";
                                                ?>
                                                <option value="Farm Production">Farm Production</option>
                                                <option value="Providential">Providential</option>
                                                <option value="Emergency">Emergency</option>
                                            </select>
                                        </div>
                                    </div>
                                    <p class="fw-bold p-text">Ang aking pinatunayan ang kawastuhan ng aking mga pahayag sa kahilingan at kasunduan sa pagbabayad at sa iba pang kapahayagang nilalaman nito.</p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Date<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control" id="date-agreement" type="date" required="" name="date-agreement" value="<?php echo $row["date_agreement"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Spouse</label><input class="form-control d-lg-flex" type="text" id="spouse-name" name="spouse-name" value="<?php echo $row["spouse_name"] ?>" disabled></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Lending Partner</label>
                                            <select class="form-control d-lg-flex" type="text" id="lpartner-name" name="lpartner-name" value="<?php echo $row["lpartner_name"] ?>" disabled>
                                            <option value="<?php echo $row["lpartner_name"] ?>"><?php echo $row["lpartner_name"] ?></option>
                                            <?php echo $options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-lg-flex align-items-lg-center" id="col-style">
                                            <div class="form-check"><input class="form-check-input" type="checkbox" required="" checked disabled><label class="form-check-label" for="formCheck">I have read and agree to the&nbsp;</label></div><a class="text-decoration-none" href="#" data-bs-target="#terms-modal" data-bs-toggle="modal"><strong>Terms and Conditions</strong><span style="color: rgb(231, 74, 59);">*</span></a>
                                        </div>
                                    </div>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                    <p class="fw-bold p-text" style="color: rgb(231, 74, 59);">You have only 1 chance to edit your application form until evaluation.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <a class="btn btn-secondary btn-icon-split m-1" href="applications-manage.php?id=<?php echo $row["user_id"]; ?>"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Back</span></a>
                                <button class="btn btn-info btn-icon-split m-1" id="edit-btn" name="edit-btn"><span class="text-white-50 icon"><i class="fas fa-edit"></i></span><span class="text-white text">Edit</span></button>
                                <button class="btn btn-primary btn-icon-split m-1" id="save-btn" name="save-btn" disabled type="submit"><span class="text-white-50 icon"><i class="fas fa-save"></i></span><span class="text-white text">Save</span></button>
                            </div>
                        </div>
                    
                </div>
            </div>
            </form>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © City College of Calamba 2023</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" data-bs-toggle="tooltip" data-bss-tooltip="" id="page-top-btn" href="#page-top" title="Return to Top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>


//start of code for 1 chance to edit 
// Get the button element by its id
$(document).ready(function() {
        let isFormEdited = localStorage.getItem('isFormEdited') === 'true';
        let editedLoanId = localStorage.getItem('editedLoanId') || '';

        if (isFormEdited && $('#loan-id').val() === editedLoanId) {
            $('#edit-btn, #save-btn').hide();
        } else if (isFormEdited) {
            $('#save-btn').prop('disabled', false);
        }

        $('#edit-btn').click(function() {
            if (!isFormEdited) {
                isFormEdited = true;
                $('#save-btn').prop('disabled', false);
                localStorage.setItem('isFormEdited', 'true');
                editedLoanId = $('#loan-id').val(); // Update editedLoanId with current loan_id
                localStorage.setItem('editedLoanId', editedLoanId);
            }
        });

        $('#save-btn').click(function() {
            const currentLoanId = $('#loan-id').val();
            if (isFormEdited && currentLoanId === editedLoanId) {
                $('#edit-btn, #save-btn').hide();
            } else {
                isFormEdited = true;
                editedLoanId = currentLoanId;
                localStorage.setItem('editedLoanId', currentLoanId);
            }
        });
    });
//end of code for 1 chance to edit 

        //Loan type and Specific Allocation Code
document.addEventListener('DOMContentLoaded', function() {
  //Loan type and Specific Allocation Code
  // Get the select elements
  const allocationTypeSelect = document.getElementById('allocation1');
  const specificAllocationSelect = document.getElementById('specific-allocation');

  // Function to create options
  function createOption(value, text) {
    const option = document.createElement('option');
    option.value = value;
    option.text = text;
    return option;
  }

  // Function to add options to specificAllocationSelect
  function addOptions(options) {
    options.forEach(function(option) {
      specificAllocationSelect.appendChild(option);
    });
  }

  // Add an event listener to the allocationTypeSelect
  allocationTypeSelect.addEventListener('change', function() {
    // Get the selected value
    const selectedValue = allocationTypeSelect.value;

    // Clear the options in specificAllocationSelect
    specificAllocationSelect.innerHTML = '';

    // Create new options based on the selected value
    if (selectedValue === 'Lending') {
      const options = [
        createOption('Providential', 'Providential'),
        createOption('Emergency', 'Emergency')
      ];
      addOptions(options);
    } else if (selectedValue === 'Farming') {
      const options = [
        createOption('Farm Production', 'Farm Production')
      ];
      addOptions(options);
    }
  });

  // Trigger the change event to set the initial options
  allocationTypeSelect.dispatchEvent(new Event('change'));
});
//End of code

    $(document).ready(function() {
    $('#edit-btn').on('click', function() {
        event.preventDefault();
        $('#save-btn').prop('disabled', false); // Enable save button
        $('#position').prop('disabled', false); // Enable input field
        $('#amount-number').prop('disabled', false); // Enable input field
        $('#allocation1').prop('disabled', false); // Enable input field
        $('#specific-allocation').prop('disabled', false); // Enable input field
        $('#lpartner-name').prop('disabled', false); // Enable input field
        $('#rate-installment').prop('disabled', false); // Enable input field
        $('#loan-status').prop('disabled', false); // Enable input field
        $('#spouse-name').prop('disabled', false).prop('readonly', true); // Disable input field and set it to readonly
        
        
    });
    });

    document.getElementById('save-btn').addEventListener('click', function(event) {
        var amountNumber = document.getElementById('amount-number').value;
        
        // Check if the amount is valid (e.g., greater than 15000)
        if (parseFloat(amountNumber) > "<?php echo $seventypercent;?>") {
            event.preventDefault(); // Prevent form submission
            document.getElementById('amount-number').classList.add('is-invalid'); // Add the 'is-invalid' class to show the input error
        }
        if (parseFloat(amountNumber) < 5000) {
            event.preventDefault();
            document.getElementById('amount-number').classList.add('is-invalid');
        }
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