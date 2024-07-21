<?php
include_once '../db_connection.php';
session_start();
if(!isset($_SESSION['userloggedin']) || $_SESSION['userloggedin'] !== true){
    header("Location: ../index.php");
    exit;
}
$userLoginId = $_GET['id'];

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
            
            // Retrieve individual field value
            
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
            
        }
        
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form inputs
    
    //name
    $user_id = $_POST['ca-user-id'];
    $loan_id = $_POST['ca-loan-id'];
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $sfxname = $_POST['sfxname'];

    //address
    $address_room = $_POST['address-room'];
    $address_house = $_POST['address-house'];
    $address_street = $_POST['address-street'];
    $address_subd = $_POST['address-subd'];
    $address_brgy = $_POST['address-brgy'];

    //additional information
    $position = $_POST['position'];
    $amount_number = $_POST['amount-number'];
    $amount_number_decimal = number_format($amount_number, 2);
    $rate_installment = $_POST['rate-installment'];

    //allocation of loan
    $allocation_type1 = $_POST['allocation1'];
    

    //allocation type
    $sallocation_type1 = $_POST['sallocation1'];

    $date_agreement = $_POST['date-agreement'];
    $spouse_name = $_POST['spouse-name'];
    $lpartner_name = $_POST['lpartner-name'];

    $loan_status = $_POST['loan-status'];

    
    //Payment Method
    if ($rate_installment === "Monthly") {
        $amount_number = floatval($amount_number);
        $amount_installment = $amount_number / 12;
        $amount_installment = number_format($amount_installment, 2);
    } elseif ($rate_installment === "Quarterly") {
        $amount_number = floatval($amount_number);
        $amount_installment = $amount_number / 4;
        $amount_installment = number_format($amount_installment, 2);
    }elseif ($rate_installment === "Semi Annually") {
        $amount_number = floatval($amount_number);
        $amount_installment = $amount_number / 2;
    } elseif ($rate_installment === "Annually") {
        $amount_installment = $amount_number;
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
    $float_amount_number = floatval($amount_number);
    $penalty_amount = $float_amount_number * 0.2;
    $penalty_amount = number_format($penalty_amount, 2);

    //Maker Name
    $maker_name = "$fname $mname $lname $sfxname";
    $maker_address = "$address_room $address_house $address_street $address_subd $address_brgy Calamba City, Laguna";

    //Deduction from Proceeds
    $deduction_amount = $paymentratedecimal * $amount_number;
    $deduction_amountfinal = number_format($deduction_amount, 2);

    //Non-Finance Charges
    $sfee = "200.00";
    $cbu = "200.00";
    $insurance = $amount_number * 0.1250 * 0.05;
    $insurance = number_format($insurance, 2);

    //Total Deduction
    $totalDeduction = $cbu + $sfee + $deduction_amountfinal + $insurance;
    //Net Proceed
    $netProceed = $amount_number - $totalDeduction;
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




    // Prepare the SQL statement to insert the data into app_pending_tbl
    $sql = "INSERT INTO lo_app_pending_tbl 
    (user_id, lname, fname, mname, sfxname, 
    address_room, address_house, address_street, address_subd, address_brgy,
    position, amount_number,
    allocation_type1, sallocation_type1,
    date_agreement, spouse_name, lpartner_name, recommender_name, amount_halaga, pn_loan_amount, pn_rate, pn_rate_installment, pn_amount_installment, pn_rate_payment, pn_pesos_amount,
    pn_maker_name, pn_maker_address, da_brw_name, da_brw_address, da_lgranted_amount, da_deduction_amount, da_sfee_amount, da_cbu_amount, da_insurance_amount, da_tdeduction_amount, da_nproceed_amount, da_amortization_amount1, ca_status, loan_status) 
    
    
    VALUES ('$user_id','$lname', '$fname', '$mname', '$sfxname', 
    '$address_room', '$address_house', '$address_street', '$address_subd', '$address_brgy',
    '$position', '$amount_number_decimal',
    '$allocation_type1', '$sallocation_type1',
    '$date_agreement', '$spouse_name', '$lpartner_name', '$lpartner_name', '$amount_number', '$amount_number', '$paymentratepercent', '$rate_installment' , '$amount_installment', '$paymentMonth', '$penalty_amount',
    '$maker_name', '$maker_address', '$maker_name', '$maker_address', '$amount_number', '$deduction_amountfinal', '$sfee','$cbu', '$insurance','$totalDeduction', '$netProceed', '$amortamount', '$caStatus', '$loan_status')";

    //For Activity Logs
    $description = "Submitted Application Form";
    $logSql = "INSERT INTO login_logs (user_id, notif_description) VALUES ('$userLoginId', '$description')";
    $conn->query($logSql); // Assuming $conn is your database connection

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        // Data inserted successfully
        
        
    } else {
        // Error inserting data
        echo "Error inserting data into login_logs: " . $conn->error;
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

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>User - Applications Create CA</title>
    <meta name="description" content="Applications Create CA">
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
                            <div><i class="far fa-calendar-alt text-primary me-1" id="nav-datetime-icon"></i><span class="text-nowrap" id="current-date" class="current-datetime" style="font-size: 12px;">Sun | January 1, 2023</span></div>
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
                    <h3 class="fw-bold text-dark">Applications</h3>
                    <form>
                        <div class="card shadow">
                            <div class="modal fade" role="dialog" tabindex="-1" id="terms-modal">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Terms and Conditions</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="p-text"><strong><span style="color: rgb(78, 115, 223);">Loan Approval</span></strong>: The cooperative reserves the right to approve or deny this loan application at its sole discretion.<br><br><strong><span style="color: rgb(78, 115, 223);">Repayment Schedule</span></strong>: The borrower agrees to repay the loan according to the specified repayment plan. Any changes to the repayment plan must be agreed upon in writing by both parties.<br><br><strong><span style="color: rgb(78, 115, 223);">Late Payments</span></strong>: In the event of a late payment, the borrower may be subject to late fees as specified in the cooperative's policies.<br><br><strong><span style="color: rgb(78, 115, 223);">Security</span></strong>: The cooperative may require collateral or a co-signer as security for this loan, as per its lending policies.<br><br><strong><span style="color: rgb(78, 115, 223);">Use of Funds</span></strong>: The borrower agrees to use the loan funds solely for the purpose specified in this application.<br><br><strong><span style="color: rgb(78, 115, 223);">Default</span></strong>: The borrower will be considered in default if they fail to make payments as per the agreed-upon schedule or violate any other terms of this agreement. In case of default, the cooperative may take legal action to recover the outstanding balance.<br><br><strong><span style="color: rgb(78, 115, 223);">Credit Reporting</span></strong>: The cooperative may report the borrower's payment history to credit bureaus, which may impact the borrower's credit score.<br><br><strong><span style="color: rgb(78, 115, 223);">Privacy</span></strong>: The cooperative will handle the borrower's personal information in accordance with its privacy policy and applicable laws.<br><br><strong>By clicking the checkbox, the borrower acknowledges that they have read, understood, and agreed to the terms and conditions outlined in this credit application form. The Applicant authorizes the Lender to obtain credit reports, financial information, and references necessary to evaluate the credit application. The Applicant also acknowledges that the information provided is accurate and complete to the best of their knowledge</strong>.</p>
                                        </div>
                                        <div class="modal-footer"><button class="btn btn-secondary btn-icon-split" id="terms-btn-close" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-times-circle"></i></span><span class="text-white text">Close</span></button></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" role="dialog" tabindex="-1" id="submit-modal">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Submit Credit Application?</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p id="p-text">Do you want to submit your Credit Application? This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer"><button class="btn btn-secondary btn-icon-split" id="submit-btn-close" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-times-circle"></i></span><span class="text-white text">Cancel</span></button><button class="btn btn-primary btn-icon-split m-1" id="submit-btn" type="submit" data-bs-target="#submit-btn-modal" data-bs-toggle="modal"><span class="text-white-50 icon"><i class="fas fa-envelope"></i></span><span class="text-white text">Submit</span></button></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" role="dialog" tabindex="-1" id="clear-modal">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Clear CA Fields?</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="p-text">The  Credit Application Form SUCCESSFULLY submitted.</p>
                                        </div>
                                        <div class="modal-footer"><button class="btn btn-secondary btn-icon-split" id="clear-btn-close-1" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-times-circle"></i></span><span class="text-white text">Cancel</span></button><button class="btn btn-danger btn-icon-split m-1" id="clear-btn-confirm-1" type="reset" data-bs-target="#clear-btn-modal" data-bs-toggle="modal"><span class="text-white-50 icon"><i class="fas fa-eraser"></i></span><span class="text-white text">Clear</span></button></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header py-3">
                                <p class="text-primary m-0 fw-bold">Create Application</p>
                            </div>
                            <div class="card-body">
                                <p class="p-text">The Credit Application Form is successfully <strong><span style="color: rgb(78, 115, 223);">SUBMITTED </span></strong>to the cooperative.</p>
                            </div>
                            <div class="card-footer d-xl-flex justify-content-xl-end">
                                <a class="btn btn-secondary btn-icon-split m-1" id="clear-btn" href="applications-create.php?id=<?php echo $userLoginId; ?>"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Back</span></a>
                                <!--<button class="btn btn-success btn-icon-split m-1" id="submit-btn" type="button"><span class="text-white-50 icon"><i class="fas fa-envelope"></i></span><span class="text-white text">Submit</span></button>-->
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
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="../assets/js/features/disable-for-approval.js"></script>
    <script src="../assets/js/features/scripts.js"></script>
    <script src="../assets/js/index.js"></script>
    <script src="../assets/js/theme.js"></script>
</body>

</html>