<?php
session_start();
if (!isset($_SESSION['adminloggedin']) || $_SESSION['admin_role'] !== 'admin3') {
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="../assets/css/bs-theme-overrides.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <div class="container-fluid p-3 print-container">
        <div class="col"><img src="../assets/img/logo/logo.png" style="width: 80px;position: absolute;height: 80px;">
            <div class="row tbl-row-header mb-0">
                <div class="col">
                    <p class="p-text">CALAMBA RICE GROWERS MULTI PURPOSE COOPERATIVE<br>97-A, San Jose, Calamba City<br>CDA Reg. No. 9520 -04000380 CIN -0103040125, TIN: 235-802-362-001<br>email add: crgmpc@technologist.com, Tel no.: (049) 502-3694</p>
                </div>
            </div>
            <h6 class="text-center mb-1">APPLICATION FORM</h6>
        </div>
        <div class="mb-3">
            <div class="row my-0 mx-0">
                <div class="col col-2 col-border col-bold"><label class="col-form-label py-0">PANGALAN</label></div>
                <div class="col col-10 col-border"><span name="ca-name"><?php echo  $row['fname'] . " " . $row['lname'] . " " . $row['sfxname'] ; ?></span></div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col col-2 col-border col-bold"><label class="col-form-label py-0">TIRAHAN</label></div>
                <div class="col col-6 col-border"><span name="ca-address"><?php echo  $row['address_room'] . " " . $row['address_house'] . " " . $row['address_street'] . " " . $row['address_subd'] . " " . $row['address_brgy']; ?>Calamba City, Laguna</span></div>
                <div class="col col-2 col-border col-bold"><label class="col-form-label py-0">SEQ. NOS.</label></div>
                <div class="col col-2 col-border"><span name="ca-seq-nos"></span><?php echo  $row['seq_nos'] ;?></div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col d-flex flex-row col-2 col-border"><input type="checkbox" class="me-1" name="ca-box-kamayari" <?php if ($position === "Kamay-ari") echo "checked"; ?>><label class="form-label py-0 my-0">Kamay-ari</label></div>
                <div class="col d-flex flex-row col-2 col-border"><input type="checkbox" id="user--2" class="me-1" <?php if ($position === "Regular") echo "checked"; ?>><label class="form-label py-0 my-0">Regular</label></div>
                <div class="col d-flex flex-row col-2 col-border"><input type="checkbox" class="me-1" name="ca-box-associate" <?php if ($position === "Associate") echo "checked"; ?>><label class="form-label py-0 my-0">Associate</label></div>
                <div class="col col-3 col-border col-bold"><label class="col-form-label py-0">DATE</label></div>
                <div class="col col-3 col-border"><span name="ca-date-created"><?php echo  $row['date_agreement'] ;?></span></div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col d-flex flex-row col-2 col-border"><input type="checkbox" class="me-1" name="ca-box-other"><label class="form-label py-0 my-0">&nbsp;</label></div>
                <div class="col d-flex flex-row col-2 col-border"><input type="checkbox" class="me-1" name="ca-box-farmer" <?php if ($position === "Farmer") echo "checked"; ?>><label class="form-label py-0 my-0">Farmer</label></div>
                <div class="col d-flex flex-row col-2 col-border"><input type="checkbox" class="me-1" name="ca-box-nonfarmer" <?php if ($position === "Non-Farmer") echo "checked"; ?>><label class="form-label py-0 my-0">Non-Farmer</label></div>
                <div class="col col-bold col-3 col-border"><label class="col-form-label py-0">PB/Lot Nos.</label></div>
                <div class="col col-3 col-border"><span name="ca-pb-lot-nos"><?php echo  $row['lot_nos'] ;?></span></div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col d-flex flex-row">
                    <p class="p-text my-2">Ako po ay humihiling ng halagang <span class="fw-bold text-decoration-underline mx-1" name="ca-amount-text"> ₱ <?php echo  $row['amount_number'] ;?></span>piso na aking ipinangangakong babayaran / huhulugan kada (kinsenas, buwanan, o matapos ang pag-aani ng aking sakahan) kasama ang mga kaukulang porsyento at patubo, serbis fee, at iba pa. Ang uri po ng aking paglalaanan ng puhunan ay ang sumusunod:</p>
                </div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col d-flex flex-row col-6 col-border"><input type="checkbox" class="me-1" name="ca-box-lending" <?php if ($allocation_type1 === "Lending") echo "checked"; ?>><label class="form-label py-0 my-0" >Lending</label></div>
                <div class="col d-flex flex-row col-6 col-border"><input type="checkbox" class="me-1" name="ca-box-farming" <?php if ($allocation_type1 === "Farming") echo "checked"; ?>><label class="form-label py-0 my-0" >Farming</label></div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col col-12 col-border"><span name="ca-lending-sallocation1">Specific Loan: <?php echo  $row['sallocation_type1'] ;?>&nbsp;</span></div>
            </div>
            <br>
            <div class="row my-0 mx-0">
                <div class="col d-flex flex-row">
                    <p class="p-text my-0">Aking pinatutunayan ang kawastuhan ng aking mga pahayag sa kahilingan at kasunduan sa pagbabayad at sa iba pang kapahayagang nilalaman nito.</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col col-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-date-agreement"><?php echo  $row['date_agreement'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Petsa</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>&nbsp;</span></div>
                    </div>
                </div>
                <div class="col col-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-debtor-name"><?php echo  $row['fname'] . " " . $row['lname'] . " " . $row['sfxname'] ; ?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Umuutang</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>(Print name &amp; signature)</span></div>
                    </div>
                </div>
                <div class="col col-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-spouse-name"><?php echo  $row['spouse_name'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Pahintulolot sa asawa</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>(Print name &amp; signature)</span></div>
                    </div>
                </div>
                <div class="col col-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-lpartner-name"><?php echo  $row['lpartner_name'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Katuwang sa Pag-utang</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>(Print name &amp; signature)</span></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <hr class="m-0">
                    <h6 class="text-center m-0">FOR CRG-MPC USE ONLY</h6>
                    <hr class="m-0">
                </div>
            </div>
        
            <div class="row my-2">
                <div class="col col-3">
                    <div class="row mb-0 ms-3">
                        <div class="col"><span id="print-crecom-name-13">Inirerekomenda ni: <?php echo  $row['recommender_name'] ;?></span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-date-approved"><?php echo  $row['date_approved'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>Petsa</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>&nbsp;</span></div>
                    </div>
                </div>
                <div class="col col-3">
                    <div class="row mb-0 ms-3">
                        <div class="col"><span id="print-crecom-name-15">&nbsp;</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-committee-name"><?php echo  $row['ccommitee_name'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>CREDIT COMMITEE</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>(Print name &amp; Signature)</span></div>
                    </div>
                </div>
                <div class="col col-3">
                    <div class="row mb-0 ms-3">
                        <div class="col"><span id="print-crecom-name-16">Inaprobahan nina:</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-general-manager"><?php echo  $row['gmanager_name'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>GENERAL MANAGER</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span>(Print name &amp; Signature)</span></div>
                    </div>
                </div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col d-flex flex-row">
                    <p class="p-text my-0">Sa ginanap na pagpupulong noong ika<span class="fw-bold text-decoration-underline mx-1" name="ca-meeting-date"><?php echo  $row['date_agreement_creditcom'] ;?></span>ay aming inaprobahan ang iyong kailangan sa pag-utang, maliban sa mga pagbabago gaya ng sumusunod.&nbsp;</p>
                </div>
            </div>
            <hr class="m-0">
            <div class="row my-2">
                <div class="col col-2">
                    <div class="row mb-0 ms-3">
                        <div class="col"><span id="print-crecom-name-17">Halaga:</span></div>
                    </div>
                    <div class="row mb-0 ms-3">
                        <div class="col"><span id="print-crecom-name-21">Termino:</span></div>
                    </div>
                </div>
                <div class="col col-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-amount-approved"> ₱ <?php echo  $row['amount_number'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-term-start"><?php echo  $row['term_start'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-term-end"><?php echo  $row['term_end'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                </div>
                <div class="col col-3">
                    <div class="row mb-0 ms-3">
                        <div class="col"><span class="text-nowrap" id="print-crecom-name-18">Lupon ng Patnugutan:</span></div>
                    </div>
                </div>
                <div class="col col-4">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-bdirector-name3"><?php echo  $row['bdirector_name1'] ;?><br></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-bdirector-name4"><?php echo  $row['bdirector_name2'] ;?><br></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-bdirector-name5"><?php echo  $row['bdirector_name3'] ;?><br></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                </div>
            </div>
            <div class="row my-0 mx-0">
                <div class="col d-flex flex-row col-5">
                    <p class="p-text my-0">Ang aksyon ng Komite at BOD ay itinala sa minuto. Pagpupulong petsa<span class="fw-bold text-decoration-underline mx-1" name="ca-approved-date"></span><?php echo  $row['date_meeting'] ;?></p>
                </div>
                <div class="col col-3">
                    <div class="row mb-0 ms-3">
                        <div class="col"><span id="print-crecom-name-19">Lupon ng Pagpapautang:</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span id="print-crecom-name-20">&nbsp;</span></div>
                    </div>
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span id="print-gmanager-name-12">&nbsp;</span></div>
                    </div>
                </div>
                <div class="col col-4">
                    <div class="row mb-0 tbl-row-footer-label">
                        <div class="col"><span name="ca-blender-name1"><?php echo  $row['blender_name1'] ;?></span></div>
                    </div>
                    <hr class="my-0 mx-3">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row d-xl-flex">
        <div class="col d-flex justify-content-end bg-white">
            <a class="btn btn-secondary btn-icon-split m-1" href="applications-approved-update.php?id=<?php echo $row["loan_id"];?>"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Back</span></a>
            <button class="btn btn-primary btn-icon-split m-1" id="ca-print-btn" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-print"></i></span><span class="text-white text">Print</span></button>
        </div>
    </div>
    <script>
        window.onload = function() {
        var printButton = document.getElementById('ca-print-btn');
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
        printContent = printContent.replace(/<button[^>]*ca-print-btn[^>]*>.*?<\/button>/gi, '');
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