<?php
session_start();
if(!isset($_SESSION['userloggedin']) || $_SESSION['userloggedin'] !== true){
    header("Location: ../index.php");
    exit;
}
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
            //DISCLOSURE STATEMENT
            $user_id = $loan_id = $row["user_id"];
            $loan_id = $row["loan_id"];
            $pn_rate_installment = $row["pn_rate_installment"];
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
            $da_spayment_date = $row["da_spayment_date"];
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

            $da_addtional_charges = $row["da_addtional_charges"];
            $da_lofficer_name = $row["da_lofficer_name"];
            $da_brw_name_confirm = $row["da_brw_name_confirm"];
            $da_disclosure_date = $row["da_disclosure_date"];

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
            <h6 class="text-center mb-1">DISCLOSURE STATEMENT</h6>
        </div>
        <div class="mb-3">
            <div class="row tbl-row">
                <div class="col col-5"><label class="col-form-label tbl-label py-0">BORROWER:<span class="ms-3" name="ds-borrower-name"><?php echo  $row['fname'] . " " . $row['lname'] . " " . $row['sfxname'] ; ?></span></label></div>
            </div>
            <div class="row tbl-row">
                <div class="col col-5"><label class="col-form-label tbl-label py-0">ADDRESS:<span class="ms-3" name="ds-address"><?php echo  $row['da_brw_address'] ;?></span></label></div>
            </div>
            <div class="row tbl-row">
                <div class="col col-5"><label class="col-form-label tbl-label py-0">KIND OF LOAN:<span class="ms-3" name="ds-loan-type"><?php echo  $row['sallocation_type1'] ;?></span></label></div>
            </div>
            <div class="row mt-3 ms-3">
                <div class="col col-3">
                    <div class="row mb-0">
                        <div class="col"><label class="col-form-label py-0">1.) LOAN GRANTED:</label></div>
                        
                    </div>
                </div>
                <div class="col col-6"><span>&nbsp;</span></div>
                <div class="col col-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ds-loan-granted"><?php echo  $row['amount_number'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                </div>
            </div>
            <div class="row mt-3 ms-3">
                <div class="col col-5">
                    <div class="row mb-0">
                        <div class="col"><label class="col-form-label py-0">2.) FINANCE CHARGES:</label></div>
                    </div>
                    <div class="row mb-0">
                        <div class="col">
                            <p class="p-text my-0 text-nowrap">a. Interest at<span class="mx-1 fw-bold text-decoration-underline" name="ds-interest"><?php echo  $row['pn_rate'] ;?></span> PA for<span class="mx-1 fw-bold text-decoration-underline" name="ds-pa-months">1 year</span></p>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col">
                            <p class="p-text my-0 text-nowrap">From<span class="mx-1 fw-bold text-decoration-underline" name="ds-start-date"><?php echo  $row['term_start'] ;?></span>to<span class="mx-1 fw-bold text-decoration-underline" name="ds-end-date"><?php echo  $row['term_end'] ;?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col col-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="soa-treasurer-name">Deduction from Proceeds</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>&nbsp;</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ds-deduction-proceeds"><?php echo  $row['da_deduction_amount'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                </div>
            </div>
            <div class="row mt-3 ms-3">
                <div class="col col-5">
                    <div class="row mb-0">
                        <div class="col"><label class="col-form-label py-0">3.) NON-FINANCE CHARGES:</label></div>
                    </div>
                    <div class="row mb-0">
                        <div class="col">
                            <p class="p-text my-0 text-nowrap">a. Service Fee</p>
                            <p class="p-text my-0 text-nowrap">b. CBU</p>
                            <p class="p-text my-0 text-nowrap">c. Insurance</p>
                        </div>
                    </div>
                </div>
                <div class="col col-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>&nbsp;</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ds-sfee"><?php echo  $row['da_sfee_amount'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ds-cbu"><?php echo  $row['da_cbu_amount'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ds-insurance"><?php echo  $row['da_insurance_amount'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                </div>
            </div>
            <div class="row mt-3 ms-3">
                <div class="col col-4">
                    <div class="row mb-0">
                        <div class="col"><label class="col-form-label py-0">4.) TOTAL DEDUCTION FROM PROCEEDS OF LOAN:</label></div>
                    </div>
                </div>
                <div class="col col-5"><span>&nbsp;</span></div>
                <div class="col col-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ds-tdeduction-proceeds"><?php echo  $row['da_tdeduction_amount'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                </div>
            </div>
            <div class="row mt-3 ms-3">
                <div class="col col-4">
                    <div class="row mb-0">
                        <div class="col"><label class="col-form-label py-0">5.) NET PROCEEDS OF LOAN:</label></div>
                    </div>
                </div>
                <div class="col col-5"><span>&nbsp;</span></div>
                <div class="col col-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ds-net-proceeds"><?php echo  $row['da_nproceed_amount'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                </div>
            </div>
            <div class="row mt-3 ms-3">
                <div class="col">
                    <div class="row mb-0">
                        <div class="col"><label class="col-form-label py-0">6.) SCHEDULE OF PAYMENT:</label></div>
                    </div>
                    <div class="row mb-0">
                        <div class="col">
                            <p class="p-text my-0 text-nowrap">a. Amortization Schedule:</p>
                        </div>
                    </div>
                    <div class="row mb-0 mt-2">
                        <div class="col col-3">
                            
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date1" name="ds-am-date1"><?php echo  $row['da_amortization_date1'] ;?></span></div>
                            </div>
                            <hr id="date-underline1" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date2" name="ds-am-date2"><?php echo  $row['da_amortization_date2'] ;?></span></div>
                            </div>
                            <hr id="date-underline2" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date3" name="ds-am-date3"><?php echo  $row['da_amortization_date3'] ;?></span></div>
                            </div>
                            <hr id="date-underline3" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date4" name="ds-am-date4"><?php echo  $row['da_amortization_date4'] ;?></span></div>
                            </div>
                            <hr id="date-underline4" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date5" name="ds-am-date5"><?php echo  $row['da_amortization_date5'] ;?></span></div>
                            </div>
                            <hr id="date-underline5" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date6" name="ds-am-date6"><?php echo  $row['da_amortization_date6'] ;?></span></div>
                            </div>
                            <hr id="date-underline6" class="my-0 mx-3">

                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date7" name="ds-am-date6"><?php echo  $row['da_amortization_date7'] ;?></span></div>
                            </div>
                            <hr id="date-underline7" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date8" name="ds-am-date6"><?php echo  $row['da_amortization_date8'] ;?></span></div>
                            </div>
                            <hr id="date-underline8" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date9" name="ds-am-date6"><?php echo  $row['da_amortization_date9'] ;?></span></div>
                            </div>
                            <hr id="date-underline9" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date10" name="ds-am-date6"><?php echo  $row['da_amortization_date10'] ;?></span></div>
                            </div>
                            <hr id="date-underline10" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date11" name="ds-am-date6"><?php echo  $row['da_amortization_date11'] ;?></span></div>
                            </div>
                            <hr id="date-underline11" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-date12" name="ds-am-date6"><?php echo  $row['da_amortization_date12'] ;?></span></div>
                            </div>
                            <hr id="date-underline12" class="my-0 mx-3">
                        </div>

                        <div class="col col-2"><span id="soa-treasurer-name-21" name="soa-treasurer-name">&nbsp;</span></div>
                        <div class="col col-3">

                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount1" name="ds-am-amount1"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline1" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount2" name="ds-am-amount2"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline2" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount3" name="ds-am-amount3"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline3" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount4" name="ds-am-amount4"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline4" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount5" name="ds-am-amount5"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline5" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount6" name="ds-am-amount6"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline6" class="my-0 mx-3">

                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount7" name="ds-am-amount6"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline7" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount8" name="ds-am-amount6"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline8" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount9" name="ds-am-amount6"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline9" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount10" name="ds-am-amount6"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline10" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount11" name="ds-am-amount6"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline11" class="my-0 mx-3">
                            <div class="row mb-0 tbl-row-footer-label">
                                <div class="col"><span id="amortization-amount12" name="ds-am-amount6"><?php echo  $row['da_amortization_amount1'] ;?></span></div>
                            </div>
                            <hr id="amount-underline12" class="my-0 mx-3">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-0 ms-3">
                <div class="col"><span name="ds-add-charges">&nbsp;</span></div>
            </div>
            <div class="row mb-0 mt-2">
                <div class="col col-4">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span id="soa-treasurer-name-19" name="soa-treasurer-name">CERTIFIED CORRECT</span></div>
                    </div>
                </div>
                <div class="col col-1"><span id="soa-treasurer-name-24" name="soa-treasurer-name">&nbsp;</span></div>
                <div class="col col-6">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col">
                            <p class="p-text my-0">I ACKNOWLEDGE RECEIPT OF A COPY OF THIS STATEMENT AND THAT I UNDERSTAND AND FULLY AGREE TO THE TERMS AND&nbsp; CONDITIONS THERE OF</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-0 mt-3">
                <div class="col col-4">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ds-loan-officer"><?php echo  $row['ccommitee_name']; ?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span id="soa-treasurer-name-20" name="soa-treasurer-name">LOAN OFFICER</span></div>
                    </div>
                </div>
                <div class="col col-3"><span id="soa-treasurer-name-22" name="soa-treasurer-name">&nbsp;</span></div>
                <div class="col col-4">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span id="soa-treasurer-name-25" name="soa-treasurer-name"><?php echo  $row['fname'] . " " . $row['lname'] . " " . $row['sfxname'] ; ?>&nbsp;</span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span id="soa-treasurer-name-26" name="soa-treasurer-name">SIGNATURE OF BORROWER</span></div>
                        <input type="hidden" id="rate-installment" value="<?php echo $pn_rate_installment; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row d-xl-flex">
        <div class="col d-flex justify-content-end bg-white">
            <a class="btn btn-secondary btn-icon-split m-1" href="applications-approved-view.php?id=<?php echo $row["loan_id"];?>"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Back</span></a>
            <button class="btn btn-primary btn-icon-split m-1" id="ds-print-btn" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-print"></i></span><span class="text-white text">Print</span></button>
        </div>
    </div>
    <script>
    window.onload = function() {
        var printButton = document.getElementById('ds-print-btn');
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
        printContent = printContent.replace(/<button[^>]*ds-print-btn[^>]*>.*?<\/button>/gi, '');
        printContent = printContent.replace(/<button[^>]*type=['"]?submit['"]?[^>]*>.*?<\/button>/gi, '');
        printContent = printContent.replace(/<a[^>]*href=['"]?applications-approved-view\.php[^>]*>.*?<\/a>/gi, '');
        return printContent;
    }


//amortization schedule
function amortizationDateSet() {
    const installmentNumberInput = document.getElementById('rate-installment');
  const amortizationDateInputs = [];
  const amortizationAmountInputs = [];
  const amortizationAmountunderline = [];
  const amortizationDateunderline = [];

  // Initialize the amortization date and amount inputs
  for (let i = 1; i <= 12; i++) {
    amortizationDateInputs[i] = document.getElementById(`amortization-date${i}`);
    amortizationAmountInputs[i] = document.getElementById(`amortization-amount${i}`);
    amortizationAmountunderline[i] = document.getElementById(`amount-underline${i}`);
    amortizationDateunderline[i] = document.getElementById(`date-underline${i}`);
  }

  // Function to initially hide all inputs
  function hideAllInputs() {
    for (let i = 1; i <= 12; i++) {
      amortizationDateInputs[i].style.display = 'none';
      amortizationAmountInputs[i].style.display = 'none';
      amortizationAmountunderline[i].style.display = 'none';
      amortizationDateunderline[i].style.display = 'none';
    }
  }

  // Function to show inputs based on installment number
  function showInputs(installmentNumber) {
    for (let i = 1; i <= 12; i++) {
      if (i <= installmentNumber) {
        amortizationDateInputs[i].style.display = 'block';
        amortizationAmountInputs[i].style.display = 'block';
        amortizationAmountunderline[i].style.display = 'block';
        amortizationDateunderline[i].style.display = 'block';
      } else {
        amortizationDateInputs[i].style.display = 'none';
        amortizationAmountInputs[i].style.display = 'none';
        amortizationAmountunderline[i].style.display = 'none';
        amortizationDateunderline[i].style.display = 'none';
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
    defaultInstallmentNumber = 4;
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