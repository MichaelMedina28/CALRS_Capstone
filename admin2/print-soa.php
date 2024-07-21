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

$sql = "SELECT * FROM loan_completed_tbl WHERE loan_id = ?";
    
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
            
            $term_end = $row["term_end"];
            $pn_amount_installment = $row["pn_amount_installment"];
            $da_additional_charges = $row["da_addtional_charges"];
            $pn_collateral = $row["pn_collateral"];
            $da_tdeduction_amount = $row["da_tdeduction_amount"];
            $al_loan_status = $row["al_loan_status"];
            $al_amortization_date1 = $row["al_amortization_date1"];
            $al_amortization_amount1 = $row["al_amortization_amount1"];
            $al_amortization_date2 = $row["al_amortization_date2"];
            $al_amortization_amount2 = $row["al_amortization_amount2"];
            $al_amount_balance = $row["al_amount_balance"];

            $term_start = $row["term_start"];
            $seq_nos = $row["seq_nos"];
            $amount_number = $row["amount_number"];
            $pn_rate = $row["pn_rate"];
            $pn_amount_installment = $row["pn_amount_installment"];
            $da_cbu_amount = $row["da_cbu_amount"];
            
            $al_amortization_amount1 = $row["al_amortization_amount1"];
            $al_amortization_amount2 = $row["al_amortization_amount2"];

            $al_amortization_amount1 = intval($al_amortization_amount1);
            $al_amortization_amount2 = intval($al_amortization_amount2);
            
            $sum = $al_amortization_amount1 + $al_amortization_amount2;

            $gmanager_name = $row["gmanager_name"];

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
    <title>CRG-MPC Statement of Account</title>
    <link rel="icon" type="image/png" sizes="396x396" href="../assets/img/logo/logo.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/Nunito.css">
    <link rel="stylesheet" href="../assets/css/Poppins.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="../assets/css/bs-theme-overrides.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <div class="container-fluid p-3 print-container">
        <div class="col"><img src="../assets/img/logo/logo.png" style="width: 80px;position: absolute;height: 80px;">
            <div class="row tbl-row-header">
                <div class="col">
                    <p class="p-text">CALAMBA RICE GROWERS MULTI PURPOSE COOPERATIVE<br>97-A, San Jose, Calamba City<br>CDA Reg. No. 9520 -04000380 CIN -0103040125, TIN: 235-802-362-001<br>email add: crgmpc@technologist.com, Tel no.: (049) 502-3694</p>
                </div>
            </div>
            <h6 class="text-center">Statement of Account</h6>
        </div>
        <div class="row tbl-row">
            <div class="col col-5"><label class="col-form-label tbl-label py-0">DATE:<span id="soa-date" class="ms-3" name="soa-date"><?php echo  $row['term_start'] ;?></span></label></div>
        </div>
        <div class="row tbl-row">
            <div class="col col-5"><label class="col-form-label tbl-label py-0">G./GNG:<span id="soa-name" class="ms-3" name="soa-name"><?php echo  $row['fname'] . " " . $row['lname'] . " " . $row['sfxname'] ; ?></span></label></div>
        </div>
        <div class="row tbl-row">
            <div class="col col-5"><label class="col-form-label tbl-label py-0">Loan ID:<span id="acct-nos" class="ms-3" name="acct-nos"><?php echo  $row['loan_id'] ;?></span></label></div>
        </div>
        <br>
        <div class="row mb-0">
            <div class="col">
                <div>
                    <table class="table table-sm table-bordered" style="font-size: 0.7rem;">
                        <thead>
                            <tr class="print-tbl-row">
                                <th>DATE</th>
                                <th>REF NOS.</th>
                                <th>ACTIVITY</th>
                                <th>PRINCIPAL AMOUNT</th>
                                <th>INT<br>1.5% or 2% /MO</th>
                                <th>INTEREST<br>AMOUNT</th>
                                <th>CBU<br>2%</th>
                                <th>Amount Balance</th>
                                <th>Remark</th>
                                <th>TOTAL<br>AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                                            // Assuming you have a database connection established
                                            include_once '../db_connection.php';

                                            // Retrieve data from the database
                                            $sqlquery = "SELECT * FROM payment_logs WHERE loan_id = '$loan_id' ORDER BY pl_date DESC";
                                            $result = mysqli_query($conn, $sqlquery);

                                            // Display data in the table
                                            if (mysqli_num_rows($result) > 0) {
                                                $queryResult = mysqli_num_rows($result);
                                                while ($rowref = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $rowref['pl_date'] . "</td>";
                                                    echo "<td>" . $rowref['ref_no'] . "</td>";
                                                    echo "<td>" . "Payment" . "</td>";
                                                    echo "<td>" . $row['amount_number'] . "</td>";
                                                    echo "<td>" . $row['pn_rate'] . "</td>";
                                                    echo "<td>" . $row['da_deduction_amount'] . "</td>";
                                                    echo "<td>" . $row['da_cbu_amount'] . "</td>";
                                                    echo "<td>" . $rowref['pl_balance'] . "</td>";
                                                    echo "<td>" . $rowref['pl_remarks'] . "</td>";
                                                    echo "<td>" . $rowref['pl_payment'] . "</td>";
                                                    echo "</tr>"; 
                                                }
                                            } 

                                            ?>
                            <tr class="print-total-text">
                                <td colspan="2">TOTAL AMOUNT DUE</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><?php echo "<td>" . $row['da_nproceed_amount'] .   "</td>";?></td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="p-text">Mangyari po lamang na makipag-ugnayan sa ating Credit and Collection Committee Chairman<br>kung mayroon pong dapat linawin. Kung sakaling nabayaran na po ninyo ito tanggapin po ninyo<br>ang aming lugod na pasasalamat.</p>
            </div>
        </div>
        <div class="row">
            <div class="col col-4">
                <div class="row mb-0">
                    <div class="col"><label class="col-form-label py-0">Prepared By:</label></div>
                </div>
                <div></div>
                <div class="row mb-0 tbl-row-footer-label">
                    <div class="col"><span id="soa-crecom-name" name="soa-crecom-name"><br><?php echo $row["ccommitee_name"];?></span></div>
                </div>
                <hr class="my-0 mx-3">
                <div class="row mb-0 tbl-row-footer-label">
                    <div class="col">Credit Committee<span></span></div>
                </div>
            </div>
            <div class="col col-4">
                <div class="row mb-0">
                    <div class="col"><label class="col-form-label py-0">Checked By:</label></div>
                </div>
                <div class="row mb-0 tbl-row-footer-label">
                    <div class="col"><span id="soa-gmanager-name" name="soa-gmanager-name"><br><?php echo $row["gmanager_name"];?></span></div>
                </div>
                <hr class="my-0 mx-3">
                <div class="row mb-0 tbl-row-footer-label">
                    <div class="col"><span>GENERAL MANAGER</span></div>
                </div>
            </div>
            <div class="col col-4">
                <div class="row mb-0">
                    <div class="col"><label class="col-form-label py-0">&nbsp;</label></div>
                </div>
                <div class="row mb-0 tbl-row-footer-label">
                    <div class="col"><span id="soa-treasurer-name" name="soa-treasurer-name"><br>Treasurer Name</span></div>
                </div>
                <hr class="my-0 mx-3">
                <div class="row mb-0 tbl-row-footer-label">
                    <div class="col"><span>TREASURER</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row d-xl-flex">
        <div class="col d-flex justify-content-end bg-white">
            <a class="btn btn-secondary btn-icon-split m-1" href="loans-completed-view.php?id=<?php echo $row["loan_id"];?>""><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Back</span></a>
            <button class="btn btn-primary btn-icon-split m-1" id="soa-print-btn" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-print"></i></span><span class="text-white text">Print</span></button>
        </div>
    </div>
    

    <script>
    window.onload = function() {
        var printButton = document.getElementById('soa-print-btn');
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
        printContent = printContent.replace(/<button[^>]*soa-print-btn[^>]*>.*?<\/button>/gi, '');
        printContent = printContent.replace(/<button[^>]*type=['"]?submit['"]?[^>]*>.*?<\/button>/gi, '');
        printContent = printContent.replace(/<a[^>]*href=['"]?loans-completed-view\.php[^>]*>.*?<\/a>/gi, '');
        return printContent;
    }
</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/features/disable-for-approval.js"></script>
    <script src="assets/js/features/scripts.js"></script>
    <script src="assets/js/index.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>