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
// Prepare a select statement

if (isset($_POST['update'])) {

    $loanId = $_GET['id'];
    // Prepare the SQL UPDATE statement
    $sql = "UPDATE loan_active_tbl SET 
    al_amortization_date1 = ?, al_amortization_amount1 = ?, al_amortization_date2 = ?, al_amortization_amount2 = ?, al_amount_balance = ? 
    WHERE loan_id = ?";

    // Prepare the statement
    if ($stmt = mysqli_prepare($conn, $sql)) {

        // Bind the updated values

        $amort_date1 = $_POST["amort-date1"];
        $amort_amount1 = $_POST["amort-amount1"];
        $amort_date2 = $_POST["amort-date2"];
        $amort_amount2 = $_POST["amort-amount2"];
        $amount_balance = $_POST["amount-balance"];

        $stmt->bind_param(
            "sssssi",
            $amort_date1,
            $amort_amount1,
            $amort_date2,
            $amort_amount2,
            $amount_balance,
            $loanId
        );
    }
    // Execute the prepared statement
    mysqli_stmt_execute($stmt);
}

if (isset($_POST['approve'])) {
    $loanId = $_GET['id'];
    $sql = "INSERT INTO loan_completed_tbl SELECT * FROM loan_active_tbl where loan_id = ?";
    $sql2 = "DELETE from loan_active_tbl where loan_id = ?";



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
    header("Location: loans-active.php");
}

$sql = "SELECT * FROM loan_active_tbl WHERE loan_id = ?";

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
            $loan_id = $row["loan_id"];
            $user_id = $row["user_id"];
            $lname = $row["lname"];
            $fname = $row["fname"];
            $mname = $row["mname"];
            $sfxname = $row["sfxname"];
            $mname = $row["position"];
            $allocation_type1 = $row["allocation_type1"];
            $amount_number = $row["amount_number"];
            $pn_rate = $row["pn_rate"];
            $pn_rate_installment = $row["pn_rate_installment"];
            $term_start = $row["term_start"];
            $term_end = $row["term_end"];
            $pn_amount_installment = $row["pn_amount_installment"];
            $da_additional_charges = $row["da_addtional_charges"];

            $da_amortization_date1 = $row["da_amortization_date1"];
            $da_amortization_amount1 = $row["da_amortization_amount1"];
            $da_amortization_date2 = $row["da_amortization_date2"];
            $da_amortization_amount2 = $row["da_amortization_amount2"];

            $pn_collateral = $row["pn_collateral"];
            $da_tdeduction_amount = $row["da_tdeduction_amount"];
            $al_amortization_date1 = $row["al_amortization_date1"];
            $al_amortization_amount1 = $row["al_amortization_amount1"];
            $al_amortization_date2 = $row["al_amortization_date2"];
            $al_amortization_amount2 = $row["al_amortization_amount2"];
        } else {
        }
    }
}



// Close statement
mysqli_stmt_close($stmt);

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Admin - Loans Active</title>
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
                    <h3 class="fw-bold text-dark">Approve</h3>
                    <form method="post">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 fw-bold">Active Loan</p>
                            </div>
                            <div class="card-body">
                                <div>
                                    <div class="row">
                                        <div class="col col-md-4"><label class="form-label">Name</label><input class="form-control" type="text" id="name" name="name" value="<?php echo $row["fname"] . " " . $row["mname"] . " " . $row["lname"] . " " . $row["sfxname"] ?>" disabled></div>
                                        <div class="col col-md-4"><label class="form-label">Loan ID</label><input class="form-control" type="text" id="loan_id" name="loan-id" value="<?php echo $row["loan_id"] ?>" disabled></div>
                                        <div class="col col-md-4"><label class="form-label">User ID</label><input class="form-control" type="text" id="user_id" name="user-id" value="<?php echo $row["user_id"] ?>" disabled></div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-md-6"><label class="form-label">Position</label><select class="form-select form-select" id="position" for="floatinginput" placeholder="HGsOFT" required="" name="position" disabled>
                                                <?php
                                                echo "<option value='" . $row["position"] . "'>" . $row["position"] . "</option>";
                                                ?>
                                                <option value="N/A">N/A</option>
                                                <option value="Kamay-ari">Kamay-ari</option>
                                                <option value="Regular">Regular</option>
                                                <option value="Farmer">Farmer</option>
                                                <option value="Associate">Associate</option>
                                                <option value="Non-farmer">Non-farmer</option>
                                            </select></div>
                                        <div class="col col-md-6"><label class="form-label">Allocation</label><select class="form-select form-select" id="allocation_type" for="floatinginput" placeholder="HGsOFT" required="" name="allocation" disabled>
                                                <?php
                                                echo "<option value='" . $row["allocation_type1"] . "'>" . $row["allocation_type1"] . "</option>";
                                                ?>
                                                <option value="N/A">N/A</option>
                                                <option value="Lending">Lending</option>
                                                <option value="Farming">Farming</option>>
                                            </select></div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-md-4"><label class="form-label">Amount</label><input class="form-control" type="number" id="loan_amount" name="amount" value="<?php echo $row["da_nproceed_amount"] ?>" disabled></div>
                                        <div class="col col-md-4"><label class="form-label">Rate (%)</label><input class="form-control" type="number" id="rate" name="rate" value="<?php echo $row["pn_rate"] ?>" disabled></div>
                                        <div class="col col-md-4"><label class="form-label">Installments</label><select class="form-select form-select" id="installment" for="floatinginput" placeholder="HGsOFT" required="" name="installment" disabled>
                                                <?php
                                                echo "<option value='" . $row["pn_rate_installment"] . "'>" . $row["pn_rate_installment"] . "</option>";
                                                ?>
                                                <option value="N/A">N/A</option>
                                                <option value="Annual">Annual</option>
                                                <option value="Semi-annual">Semi-annual</option>
                                            </select></div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-md-6"><label class="form-label">Term Start</label><input class="form-control" id="term_start" type="date" required="" name="term-start" value="<?php echo $row["term_start"] ?>" disabled></div>
                                        <div class="col col-md-6"><label class="form-label">Term End</label><input class="form-control" id="term_end" type="date" required="" name="term-end" value="<?php echo $row["term_end"] ?>" disabled></div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-md-6"><label class="form-label">Amount for Installment</label><input class="form-control" type="number" id="amount-installment" name="amount-installment" value="<?php echo $row["da_amortization_amount1"] ?>" disabled></div>
                                        <div class="col col-md-6"><label class="form-label">Others</label><input class="form-control" type="text" id="additional_charges" name="addtional-charges" value="<?php echo $row["da_other_amount"] ?>" disabled></div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-md-6"><label class="form-label">Collateral</label><input class="form-control" type="text" id="collateral" name="collateral" value="<?php echo $row["pn_collateral"] ?>" disabled></div>
                                        <div class="col col-md-3"><label class="form-label">Deduction</label><input class="form-control" type="text" id="deduction" name="deduction" value="<?php echo $row["da_tdeduction_amount"] ?>" disabled></div>
                                        <!-- <div class="col col-md-3"><label class="form-label">Loan Status</label><select class="form-select form-select" id="loan_status" for="floatinginput" placeholder=""  name="loan-status">
                                                <?php
                                                echo "<option value='" . $row["al_loan_status"] . "'>" . $row["al_loan_status"] . "</option>";
                                                ?>
                                                <option value="">N/A</option>
                                                <option value="New">New</option>
                                                <option value="Renew">Renew</option>
                                            </select></div> -->
                                    </div>
                                    <div class="row">
                                        <div class="col"><label class="col-form-label">Amortization Schedule:</label></div>
                                    </div>
                                    <!--first row -->
                                    <div class="row">
                                        <div class="col col-md-5"><label class="form-label">Date</label><input class="form-control" id="amortization_date1" type="date" required="" name="amort-date1" value="<?php echo $row["da_amortization_date1"] ?>" required></div>
                                        <div class="col col-md-5"><label class="form-label">Amount</label><input class="form-control" type="number" id="da-amortization-amount1" name="amort-amount1" value="<?php echo $row["al_amortization_amount1"] ?>" required readonly></div>
                                        <div class="col col-md-2 align-self-end"><label class="form-label"></label><button type="button" id="amount1-btn" class="btn btn-info btn-icon-split" onclick="updateAmount()"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Insert Value</span></button></div>
                                    </div>
                                    <!--second row -->
                                    <div class="row">
                                        <div class="col col-md-5"><label class="form-label">Date</label><input class="form-control" id="amortization_date2" type="date" name="amort-date2" value="<?php echo $row["da_amortization_date2"] ?>" required></div>
                                        <div class="col col-md-5"><label class="form-label">Amount</label><input class="form-control" type="number" id="da-amortization-amount2" name="amort-amount2" value="<?php echo $row["al_amortization_amount2"] ?>" required readonly></div>
                                        <div class="col col-md-2 align-self-end"><label class="form-label"></label><button type="button" id="amount2-btn" class="btn btn-info btn-icon-split" onclick="updateAmount2()"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Insert Value</span></button></div>
                                    </div>
                                    <div class="row">
                                        <div class="col col-md-6"><label class="form-label">Amount Balance</label><input class="form-control" type="number" id="da-amount-balance" name="amount-balance" value="<?php echo $row["al_amount_balance"] ?>" readonly required></div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <a class="btn btn-secondary btn-icon-split m-1" href="loans-active.php"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Back</span></a>
                                <button class="btn btn-info btn-icon-split m-1" name="update" id="update-btn-confirm" type="submit"><span class="text-white-50 icon"><i class="fas fa-arrow-alt-circle-up text-light"></i></span><span class="text-white text">Update</span></button>
                                <button class="btn btn-success btn-icon-split m-1" name="approve" id="complete-btn-confirm" type="submit" disabled><span class="text-white-50 icon"><i class="fas fa-check-circle"></i></span><span class="text-white text">Complete</span></button>
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
        function updateAmount() {
            // Get the current values
            var installmentAmount = document.getElementById("amount-installment");
            var amortizationAmount = document.getElementById("da-amortization-amount1");
            var balanceAmount = document.getElementById("da-amount-balance");

            // Parse input values to ensure they are valid numbers
            var newAmortizationAmount = parseFloat(installmentAmount.value) || 0;
            var currentBalanceAmount = parseFloat(balanceAmount.value) || 0;

            // Perform the calculations
            var newBalanceAmount = currentBalanceAmount - newAmortizationAmount;

            // Update the values
            amortizationAmount.value = newAmortizationAmount;
            balanceAmount.value = newBalanceAmount;

            // Disable the button
            document.getElementById("amount1-btn").disabled = true;

            checkBalance();
        }

        function updateAmount2() {
            // Get the current values
            var installmentAmount = document.getElementById("amount-installment");
            var amortizationAmount = document.getElementById("da-amortization-amount2");
            var balanceAmount = document.getElementById("da-amount-balance");

            // Parse input values to ensure they are valid numbers
            var newAmortizationAmount = parseFloat(installmentAmount.value) || 0;
            var currentBalanceAmount = parseFloat(balanceAmount.value) || 0;

            // Perform the calculations
            var newBalanceAmount = currentBalanceAmount - newAmortizationAmount;

            // Update the values
            amortizationAmount.value = newAmortizationAmount;
            balanceAmount.value = newBalanceAmount;

            // Disable the button
            document.getElementById("amount2-btn").disabled = true;

            checkBalance();
        }

            function checkBalance() {
            // Get the value of the input
            var balanceInput = document.getElementById("da-amount-balance");
            var balanceValue = parseFloat(balanceInput.value);

            // Get the button
            var confirmButton = document.getElementById("complete-btn-confirm");

            // Check if the value is not equal to 0
            if (balanceValue !== 0) {
                // Enable the button
                confirmButton.disabled = true;
            } else {
                // Disable the button
                confirmButton.disabled = false;
            }
        }
        checkBalance();

//complete btn disabled
 
    </script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="../assets/js/features/disable-for-approval.js"></script>
    <script src="../assets/js/index.js"></script>
    <script src="../assets/js/theme.js"></script>
</body>

</html>