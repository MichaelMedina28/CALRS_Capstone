<?php
session_start();
if (!isset($_SESSION['userloggedin']) || $_SESSION['userloggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}
$userLoginId = $_GET['id'];
include_once '../db_connection.php';

$submitCaLimitError = "";

$inputsDisabled = false; // Flag to determine input field state

// Check if the user exists in app_pending_tbl
$sql_pending = "SELECT user_id FROM app_pending_tbl WHERE user_id = $userLoginId";
$result_pending = $conn->query($sql_pending);

$sql_lopending = "SELECT user_id FROM lo_app_pending_tbl WHERE user_id = $userLoginId";
$result_lopending = $conn->query($sql_lopending);

/*
// Check if the user exists in active_tbl
$sql_active = "SELECT user_id FROM loan_active_tbl WHERE user_id = $userLoginId";
$result_active = $conn->query($sql_active);
*/


$sql = "SELECT loan_id FROM lo_app_pending_tbl ORDER BY loan_id DESC LIMIT 1";
$result = $conn->query($sql);


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
            $position = $row["position"];
            $spouse_name = $row["spouse_name"];
            $share_investment = $row["share_investment"];
            $user_status = $row["user_status"];
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
}
//Lending partner Names
// Fetching user data from your database
$query = "SELECT user_fname, user_lname, user_mname FROM user_tbl ORDER BY user_fname"; // Replace 'user_tbl' with your actual table name
$resultlname = mysqli_query($conn, $query);

// Generating options for the select tag
$options = '';
while ($row = mysqli_fetch_assoc($resultlname)) {
    // Check if the current row corresponds to the user's name
    if ($row['user_fname'] == $user_fname && $row['user_lname'] == $user_lname && $row['user_mname'] == $user_mname) {
        continue; // Skip this row
    }

    $fullName = $row['user_fname'] . ' ' . $row['user_mname'] . ' ' . $row['user_lname'];
    $options .= "<option value='$fullName'>$fullName</option>";
}


//$result_active->num_rows > 0

// If user exists in any of the tables, disable the input fields
if ($result_pending->num_rows > 0 || $result_lopending->num_rows > 0 || $share_investment < "20000") {
    $inputsDisabled = true;

    $submitCaLimitError = "You currently have an ongoing Loan Process!";
}
//chech the share on investment
if ($share_investment < "20000") {
    $inputsDisabled = true;

    $submitCaLimitError = "You are not qualified to apply for a loan!";
}

$seventypercent = $share_investment * 0.75;

if ($user_status === "Inactive") {
    $inputsDisabled = true;

    $submitCaLimitError = "You are Inactive User!";
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
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-fname"><?php echo $_SESSION["userfname"]; ?></span><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-lname"><?php echo $_SESSION["userlname"]; ?></span></div>
                                            </div>
                                            <div class="row" id="nav-profile">
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-acc">USER</span></div>
                                            </div>
                                        </div><img class="border rounded-circle img-profile" id="nav-profile-img" src="../admin/<?php echo $account_pictures ?>" alt="../assets/img/profile/profile-default.png" name="nav-profile-img">
                                    </a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a class="dropdown-item" href="profile.php?id=<?php echo $userLoginId; ?>"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="user-notification.php?id=<?php echo $userLoginId; ?>" id="notificationLink">
                                            <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Notifications</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <h3 class="fw-bold text-dark">Applications</h3>
                    <div id="liveAlertPlaceholder"></div>
                    <form method="post" action="applications-create-submit-confirm.php?id=<?php echo $userLoginId; ?>">
                        <div class="card shadow">
                            <div class="modal fade" role="dialog" tabindex="-1" id="clear-modal">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Clear CA Fields?</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-text">
                                            <p class="p-text">Do you want to clear all the fields of your Credit Application?</p>
                                        </div>
                                        <div class="modal-footer"><button class="btn btn-secondary btn-icon-split" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-times-circle"></i></span><span class="text-white text">Cancel</span></button><button class="btn btn-danger btn-icon-split m-1" type="reset" data-bs-target="#clear-btn-modal" data-bs-toggle="modal"><span class="text-white-50 icon"><i class="fas fa-eraser"></i></span><span class="text-white text">Clear</span></button></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header py-3">
                                <p class="text-primary m-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Create CA</p>
                            </div>

                            <!-- Application Form Content -->
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

                                    <!-- Application Form User Content -->
                                    <p id="submitCaLimitError" style="color: red;" class="my-3"><?php echo $submitCaLimitError; ?></p>
                                    <input class="form-control d-lg-flex" type="hidden" id="loan-id" autocomplete="on" name="ca-loan-id" readonly value="<?php echo $next_id ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?> required>
                                    <input class="form-control d-lg-flex" type="hidden" id="user-id" autocomplete="on" name="ca-user-id" value="<?php echo $userLoginId ?>" required>
                                    <p class="mb-3"><strong>Name</strong></p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Last Name</label><input class="form-control d-lg-flex" type="text" id="lname-1" autocomplete="on" name="lname" readonly value="<?php echo $user_lname ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?> required></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">First Name</label><input class="form-control d-lg-flex" type="text" id="fname-1" required="" name="fname" readonly value="<?php echo $user_fname ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?> required></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Middle Name</label><input class="form-control d-lg-flex" type="text" id="mname-1" name="mname" readonly value="<?php echo $user_mname ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?>></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">Suffix</label>
                                            <input class="form-control d-lg-flex" type="text" id="sfxname-1" name="sfxname" readonly value="<?php echo $user_sfxname ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?>>
                                        </div>
                                    </div>
                                    <p><strong>Address</strong></p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-5 col-style"><label class="form-label">Room / Floor / Unit No. &amp; Building Name</label><input class="form-control d-lg-flex" type="text" id="address-room" name="address-room" readonly value="<?php echo $user_address_room ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?>></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-3 col-style"><label class="form-label">House / Lot &amp; Block No.</label><input class="form-control d-lg-flex" type="text" id="address-house" required name="address-house" readonly value="<?php echo $user_address_house ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?>></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Street</label><input class="form-control d-lg-flex" type="text" id="address-street" name="address-street" readonly value="<?php echo $user_address_street ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?> required></div>
                                    </div>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-6"><label class="form-label">Subdivision</label><input class="form-control d-lg-flex" type="text" id="address-subd" name="address-subd" readonly value="<?php echo $user_address_subd ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?>></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-6"><label class="form-label">Barangay</label>
                                            <input class="form-control d-lg-flex" type="text" id="address-brgy" name="address-brgy" readonly value="<?php echo $user_address_brgy ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?>>
                                        </div>
                                    </div>
                                    <p><strong>Additional Information</strong></p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Position</label>

                                            <input class="form-control d-lg-flex" type="text" id="position" name="position" readonly value="<?php echo $position ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?>>
                                        </div>
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">New or Renew<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <select class="form-select d-lg-flex" id="position" required <?php echo ($inputsDisabled ? 'disabled' : ''); ?> name="loan-status">
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
                                                <input class="form-control" type="number" id="amount-number" name="amount-number" min="5000" max="<?php echo $seventypercent ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?> required onchange="amountLimit()">
                                                <script>
                                                    function amountLimit() {
                                                        var amountInput = document.getElementById("amount-number");
                                                        var amountValue = parseInt(amountInput.value);

                                                        if (amountValue < 5000) {
                                                            amountInput.value = 5000;
                                                        } else if (amountValue > <?php echo $seventypercent ?>) {
                                                            amountInput.value = <?php echo $seventypercent ?>;
                                                        }

                                                        // calculateInterest();
                                                        // calculateInstallment();
                                                    }
                                                </script>

                                            </div>
                                        </div>
                                        <div class="col d-flex flex-column justify-content-end col-md-6 col-style"><label class="form-label">Installment<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select" name="rate-installment" <?php echo ($inputsDisabled ? 'disabled' : ''); ?> required>
                                                    <option id="monthly" value="Monthly">Monthly</option>
                                                    <option id="quarterly"value="Quarterly">Quarterly</option>
                                                    <option id="semi-annually" value="Semi Annually">Semi Annually</option>
                                                    <option id="annually" value="Annually">Annually</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="fw-bold p-text">na aking ipinangangakong babayaran / huhulugan kada (kinsenas, buwanan, o matapos ang pag aani ng aking sakahan) kasama ang mga kaukulang porsyento ng patubo, serbis fee at iba pa. Ang uri po ng aking paglalaanan ng puhunan ay ang mga sumusunod:</p>
                                    <div class="row">

                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Allocation of Loan<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <select class="form-select d-lg-flex" id="allocation-type" required <?php echo ($inputsDisabled ? 'disabled' : ''); ?> name="allocation1">
                                                <option id="lending" value="Lending" data-rate="0.015">Lending</option>
                                                <option id="farming" value="Farming" data-rate="0.02">Farming</option>
                                            </select>
                                        </div>
                                        <div class="col d-flex flex-column justify-content-end col-md-8 col-style"><label class="form-label">Specify Allocation<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <select class="form-control d-lg-flex" id="specific-allocation" required name="sallocation1" <?php echo ($inputsDisabled ? 'disabled' : ''); ?>>
                                                <option value="Providential">Providential</option>
                                                <option value="Emergency">Emergency</option>
                                                <option value="Farm Production">Farm Production</option>
                                            </select>
                                        </div>

                                    </div>

                                    <p class="fw-bold p-text">Ang aking pinatunayan ang kawastuhan ng aking mga pahayag sa kahilingan at kasunduan sa pagbabayad at sa iba pang kapahayagang nilalaman nito.</p>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Date<span style="color: rgb(231, 74, 59);">*</span></label><input class="form-control" id="date-agreement" type="date" required name="date-agreement" <?php echo ($inputsDisabled ? 'disabled' : ''); ?> readonly></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Spouse</label><input class="form-control d-lg-flex" type="text" id="spouse-name" name="spouse-name" value="<?php echo $spouse_name ?>" <?php echo ($inputsDisabled ? 'disabled' : ''); ?> readonly></div>
                                        <div class="col d-flex flex-column justify-content-end col-md-4 col-style"><label class="form-label">Lending Partner<span style="color: rgb(231, 74, 59);">*</span></label>
                                            <select class="form-control d-lg-flex" type="text" id="lpartner-name" name="lpartner-name" required <?php echo ($inputsDisabled ? 'disabled' : ''); ?>>
                                                <?php echo $options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row row-cols-1 row-cols-sm-1">
                                        <div class="col d-lg-flex align-items-lg-center" id="col-style">
                                            <div class="form-check"><input class="form-check-input" type="checkbox" required id="app-checkbox" <?php echo ($inputsDisabled ? 'disabled' : ''); ?>><label class="form-check-label" for="formCheck">I have read and agree to the&nbsp;</label></div><a class="text-decoration-none" href="#" data-bs-target="#terms-modal" data-bs-toggle="modal"><strong>Terms and Conditions</strong><span style="color: rgb(231, 74, 59);">*</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-xl-flex justify-content-xl-end">

                                
                                <button class="btn btn-primary btn-icon-split m-1" id="submit-btn" type="submit"><span class="text-white-50 icon"><i class="fas fa-envelope"></i></span><span class="text-white text" onSubmit="showAlert()">Submit</span></button>

                                
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>

$(document).ready(function() {

        function calculateInterest() {
            var amount = parseFloat($('#amount-number').val());
            var lendingInstallment = (amount * 0.015);
            var farmingInstallment = (amount * 0.02);
            console.log(lendingInstallment);
            console.log(farmingInstallment);

            $('#lending').text('Lending (1.5% rate): ' + lendingInstallment.toFixed(2));
            $('#farming').text('Farming (2% rate): ' + farmingInstallment.toFixed(2));
            

        }

        // Attach the function to the change event of the dropdown and input
        $('#rate-installment, #amount-number').on('input', calculateInterest);
        
    });


    $(document).ready(function () {
        // Function to calculate and display installment amount
        function calculateInstallment() {
            var amount = parseFloat($('#amount-number').val());
            var monthlyInstallment = amount / 12;
            var quarterlyInstallment = amount / 4;
            var semiAnnuallyInstallment = amount / 2;
            var annuallyInstallment = amount / 1;

            // Display the computed installment for each option
            $('#monthly').text('Monthly Installment: ' + monthlyInstallment.toFixed(2));
            $('#quarterly').text('Quarterly Installment: ' + quarterlyInstallment.toFixed(2));
            $('#semi-annually').text('Semi Annually Installment: ' + semiAnnuallyInstallment.toFixed(2));
            $('#annually').text('Annually Installment: ' + annuallyInstallment.toFixed(2));
        }

        // Attach the function to the change event of the dropdown and input
        $('#rate-installment, #amount-number').on('input', calculateInstallment);
    });






        //Loan type and Specific Allocation Code
        document.addEventListener('DOMContentLoaded', function() {
            //Loan type and Specific Allocation Code
            // Get the select elements
            const allocationTypeSelect = document.getElementById('allocation-type');
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

        const submitBtn = document.getElementById('submit-btn');
        const appCheckbox = document.getElementById('app-checkbox');
        submitBtn.disabled = true;
        appCheckbox.addEventListener('click', function() {
            if (appCheckbox.checked) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        });

        function currentdate() {
            // Get today's date in the format yyyy-mm-dd
            let today = new Date().toISOString().slice(0, 10);

            // Set the value of the date input field to today's date
            document.getElementById('date-agreement').value = today;
        }

        currentdate();

        //alert submit
        function showAlert() {
            const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
            const appendAlert = (message, type) => {
                const wrapper = document.createElement('div')
                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                    `   <div>${message}</div>`,
                    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    '</div>'
                ].join('')

                alertPlaceholder.append(wrapper)
            }

            const alertTrigger = document.getElementById('submit-btn')
            if (alertTrigger) {
                alertTrigger.addEventListener('click', () => {
                    appendAlert('Nice, you triggered this alert message!', 'success')
                })
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