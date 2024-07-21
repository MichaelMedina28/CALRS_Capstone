<?php
include_once '../db_connection.php';

//FOR TOTAL AMOUNT RELEASED
$sql = "SELECT * FROM app_approved_tbl WHERE ca_status = 'Released' ";

if ($stmt = mysqli_prepare($conn, $sql)) {
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        // Initialize total released amount variable
        $totalReleasedAmount = 0;

        // Fetch each row and sum the da_nproceed_amount
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $da_nproceed_amount = str_replace(',', '', $row['da_nproceed_amount']);

            // Check if the value is numeric before adding to total
            if (is_numeric($da_nproceed_amount)) {
                $totalReleasedAmount += $da_nproceed_amount;
                $withcomma = number_format($totalReleasedAmount, 2);


            }
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
}



//FOR TOTAL EARNINGS THIS YEAR
$sql1 = "SELECT * FROM loan_completed_tbl";

if ($stmt1 = mysqli_prepare($conn, $sql1)) {
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt1)) {
        $result1 = mysqli_stmt_get_result($stmt1);

        // Initialize total released amount variable
        $totalReleasedAmount1 = 0;

        // Fetch each row and sum the da_nproceed_amount
        while ($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
            $da_nproceed_amount1 = str_replace(',', '', $row1['da_nproceed_amount']);

            // Check if the value is numeric before adding to total
            if (is_numeric($da_nproceed_amount1)) {
                $totalReleasedAmount1 += $da_nproceed_amount1;
                $withcomma1 = number_format($totalReleasedAmount1, 2);
            }
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
}
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
            <div class="row tbl-row-header mb-3">
                <div class="col">
                    <p class="p-text">CALAMBA RICE GROWERS MULTI PURPOSE COOPERATIVE<br>97-A, San Jose, Calamba City<br>CDA Reg. No. 9520 -04000380 CIN -0103040125, TIN: 235-802-362-001</p>
                </div>
            </div>
            <h6 class="text-center mb-3">AUDIT TRAILS REPORT</h6>
        </div>

        <div class="mb-3">

            <div class="row my-0 mx-0 mb-3">
                <div class="col col-6 col-border col-bold"><label class="form-label py-0">TOTAL AMOUNT RELEASED: ₱</label><span><?php echo $withcomma;?></span></div>
                <div class="col col-6 col-border col-bold"><label class="form-label py-0">ESTIMATED EARNINGS FOR THIS YEAR: ₱</label><span><?php echo $withcomma1;?></span></div>
                <div class="col col-6 col-border col-bold"><label class="col-form-label py-0">Approved By: Joselito Arnaldo Helit</label></div>
                <div class="col col-6 col-border col-bold"><label class="col-form-label py-0">Released By: Froilama D. Cepeda </label></div>
            </div>
            
             <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                                <table class="table table-hover table-bordered my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Loan ID</th>
                                            <th>Name</th>
                                            <th>Date Issued</th>
                                            <th>Position</th>
                                            <th>Amount (₱)</th>
                                            
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                            // Assuming you have a database connection established
                                            

                                            // Retrieve data from the database
                                            $query = "SELECT * FROM loan_completed_tbl";
                                            $result = mysqli_query($conn, $query);
                                            $queryResult = "";

                                            // Display data in the table
                                            if (mysqli_num_rows($result) > 0) {
                                                $queryResult = mysqli_num_rows($result);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['loan_id'] . "</td>";
                                                    
                                                    echo "<td>" . $row['fname'] . " " . $row['lname'] . " " . $row['sfxname'] . "</td>";
                                                    echo "<td>" . $row['term_start'] . "</td>";
                                                    
                                                    echo "<td>" . $row['position'] . "</td>";
                                                    echo "<td>" . $row['da_nproceed_amount'] . "</td>";
                                                    
                                                    
                                                    echo "</tr>"; 
                                                }
                                            } 

                                            ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="9"><strong>Total Completed Loans: <?php echo $queryResult; ?></strong></td>
                                        <!-- Assuming you have 10 columns in your table, adjust colspan value if needed -->
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
        </div>
        
    </div>
    <div class="row d-xl-flex">
        <div class="col d-flex justify-content-end bg-white">
            <a class="btn btn-secondary btn-icon-split m-1" href="loans-completed.php"><span class="text-white-50 icon"><i class="fas fa-chevron-circle-left"></i></span><span class="text-white text">Back</span></a>
            <button class="btn btn-primary btn-icon-split m-1" id="at-print-btn" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-print"></i></span><span class="text-white text">Print</span></button>
        </div>
    </div>

    <script>
        window.onload = function() {
        var printButton = document.getElementById('at-print-btn');
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
        printContent = printContent.replace(/<button[^>]*at-print-btn[^>]*>.*?<\/button>/gi, '');
        printContent = printContent.replace(/<button[^>]*type=['"]?button['"]?[^>]*>.*?<\/button>/gi, '');
        printContent = printContent.replace(/<a[^>]*href=['"]?loans-completed\.php[^>]*>.*?<\/a>/gi, '');
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