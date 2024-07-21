<?php
session_start();
if (!isset($_SESSION['adminloggedin']) || $_SESSION['admin_role'] !== 'admin2') {
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

$sql = "SELECT * FROM app_approved_tbl WHERE loan_id = ?";
    
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
            // Retrieve individual field value
            $user_id = $loan_id = $row["user_id"];
            $loan_id = $row["loan_id"];
            $lname = $row["lname"];
            $fname = $row["fname"];
            $mname = $row["mname"];
            $sfxname = $row["sfxname"];
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

        } else{
        
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
    <title>CRG-MPC Loan System</title>
    <link rel="icon" type="image/png" sizes="396x396" href="../assets/img/logo/logo.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="../assets/css/bs-theme-overrides.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
</head>

<body>
    <div class="container-fluid p-3 print-container">
        <div class="col"><img src="../assets/img/logo/logo.png" style="width: 80px;position: absolute;height: 80px;">
            <div class="row tbl-row-header mb-0">
                <div class="col">
                    <p class="p-text">CALAMBA RICE GROWERS MULTI PURPOSE COOPERATIVE<br>97-A, San Jose, Calamba City<br>CDA Reg. No. 9520 -04000380 CIN -0103040125, TIN: 235-802-362-001<br>email add: crgmpc@technologist.com, Tel no.: (049) 502-3694</p>
                </div>
            </div>
            <h6 class="text-center mb-1">PROMISSORY NOTES</h6>
        </div>
        <div class="mb-3">
            <div class="row my-2 me-2">
                <div class="col d-flex justify-content-end"><span class="fw-bold text-decoration-underline mx-1" name="pn-date-created"><?php echo  $row['date_approved'] ;?></span></div>
            </div>
            <div class="row my-0">
                <div class="col col-4">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col text-start ms-3"><span id="print-crecom-name-23">Php</span><span class="ms-2" name="pn-loan-amount"><?php echo  $row['amount_number'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                </div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col d-flex flex-row">
                    <p class="p-text my-2">For the value received, we jointly and severally, promise to pay the Calamba Rice Growers Multi-Purpose Cooperative, or order, the sum of Pesos at the Rate of<span class="fw-bold text-decoration-underline mx-1" name="pn-rate"><?php echo  $row['pn_rate'] ;?>%</span>per month, payable in<span class="fw-bold text-decoration-underline mx-1" name="pn-rate-installment"><?php echo  $row['pn_rate_installment'] ;?></span>installments of Pesos<span class="fw-bold text-decoration-underline mx-1" name="pn-amount-installment"><?php echo  $row['pn_amount_installment'] ;?></span>payment to be made on<span class="fw-bold text-decoration-underline mx-1" name="pn-date-payable"><?php echo  $row['pn_date_payable'] ;?></span>and every<span class="fw-bold text-decoration-underline mx-1" name="pn-rate-payment"><?php echo  $row['pn_rate_payment'] ;?></span>thereafter until the full amount has been paid.</p>
                </div>
            </div>
            <div class="row my-0">
                <div class="col">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col text-start ms-3"><span id="print-crecom-name-22">Collateral:</span><span class="ms-2" name="pn-collateral"><?php echo  $row['pn_collateral'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                </div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col d-flex flex-row">
                    <p class="p-text my-2">In case of any default in payments as herein agreed, the entire balance of this note shall become immediately due and payable, at the option of the cooperative.. Each party to this note whether as maker, co-maker, endorser or guarantor severally waives presentation of payment, demand, protest and notice of protest and dishonor of the same.</p>
                </div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col d-flex flex-row">
                    <p class="p-text my-2">It is further agreed by party hereto, that in case payment shall not be made at maturity, he shall pay the cost of collection, and attorney's fees in an amount equal to twenty percent of the principal and interest due on this note, but such charge in note to be less than<span class="fw-bold text-decoration-underline mx-1" name="pn-pesos-amount"><?php echo  $row['pn_pesos_amount'] ;?></span>PESOS.</p>
                </div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col d-flex flex-row">
                    <p class="p-text my-2">In case of judicial execution of this obligation or any part of it the debtor waives all his rights under the provisions of Rule 3, Section 13 and Rule 39, Section 12 of the Rules of Court.&nbsp;</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col col-5">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="pn-maker-name"><?php echo  $row['fname'] . " " . $row['lname'] . " " . $row['sfxname'] ; ?>&nbsp;</span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Signature of Maker</span></div>
                    </div>
                </div>
                <div class="col col-2"><span id="print-crecom-name-1">&nbsp;</span></div>
                <div class="col col-5">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="pn_maker_address"><?php echo  $row['pn_maker_address'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Address</span></div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col col-5">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="pn-spouse-name"><?php echo  $row['pn_spouse_name'] ;?>&nbsp;</span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Signature of Spouse</span></div>
                    </div>
                </div>
                <div class="col col-2"><span id="print-crecom-name-7">&nbsp;</span></div>
                <div class="col col-5">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="pn_spouse_address"><?php echo  $row['pn_spouse_address'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Address</span></div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col col-5">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="pn_cmaker_name1"><?php echo  $row['lpartner_name'] ;?>&nbsp;</span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Signature of Co-Maker</span></div>
                    </div>
                </div>
                <div class="col col-2"><span id="print-crecom-name-5">&nbsp;</span></div>
                <div class="col col-5">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="pn_cmaker_address1"><?php echo  $row['pn_cmaker_address1'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Address</span></div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col col-5">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="pn_cmaker_name2"><?php echo  $row['pn_cmaker_name2'] ;?>&nbsp;</span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Signature of Co-Maker</span></div>
                    </div>
                </div>
                <div class="col col-2"><span id="print-crecom-name-3">&nbsp;</span></div>
                <div class="col col-5">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="pn_cmaker_address2"><?php echo  $row['pn_cmaker_address2'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Address</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row d-xl-flex">
        <div class="col d-flex justify-content-end bg-white">
            <a class="btn btn-secondary btn-icon-split m-1" href="applications-approved-update.php?id=<?php echo $row["loan_id"];?>"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Back</span></a>
            <button class="btn btn-primary btn-icon-split m-1" id="pn-print-btn" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-print"></i></span><span class="text-white text">Print</span></button>
        </div>
    </div>
    <script>
    window.onload = function() {
        var printButton = document.getElementById('pn-print-btn');
        printButton.addEventListener('click', function() {
            var printContent = document.documentElement.innerHTML;
            var printWindow = window.open('', '', 'width=2550,height=3300');
            printWindow.document.open();
            printWindow.document.write('<html><head><title>Print</title></head><body>');
            printWindow.document.write(removeButtonsFromPrintContent(printContent));
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 500); // Delay the print action by 500 milliseconds
        });
    };

    function removeButtonsFromPrintContent(content) {
        var printContent = content;
        printContent = printContent.replace(/<button[^>]*pn-print-btn[^>]*>.*?<\/button>/gi, '');
        printContent = printContent.replace(/<button[^>]*type=['"]?submit['"]?[^>]*>.*?<\/button>/gi, '');
        printContent = printContent.replace(/<a[^>]*href=['"]?applications-approved-update\.php[^>]*>.*?<\/a>/gi, '');
        return printContent;
    }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="../assets/js/features/disable-for-approval.js"></script>
    <script src="../assets/js/features/scripts.js"></script>
    <script src="../assets/js/index.js"></script>
    <script src="../assets/js/theme.js"></script>
</body>

</html>