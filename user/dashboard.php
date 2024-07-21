<?php
session_start();



if(!isset($_SESSION['userloggedin']) || $_SESSION['userloggedin'] !== true){
    header("Location: ../index.php");
    exit;
}
$userLoginId = $_GET['id'];

include_once '../db_connection.php';

// Query to get the count of loan IDs for the user from loan_active_tbl
$query1 = "SELECT COUNT(*) AS loan_count FROM app_pending_tbl WHERE user_id = '$userLoginId'";
$result1 = mysqli_query($conn, $query1);
$row1 = mysqli_fetch_assoc($result1);
$pendingCount = $row1['loan_count'];

// Query to get the count of loan IDs for the user from forapproval_tbl
$query2 = "SELECT COUNT(*) AS loan_count FROM app_approved_tbl WHERE user_id = '$userLoginId'";
$result2 = mysqli_query($conn, $query2);
$row2 = mysqli_fetch_assoc($result2);
$forApprovalCount = $row2['loan_count'];

$query3 = "SELECT COUNT(*) AS loan_count FROM loan_active_tbl WHERE user_id = '$userLoginId'";
$result3 = mysqli_query($conn, $query3);
$row3 = mysqli_fetch_assoc($result3);
$activeLoanCount = $row3['loan_count'];

$query4 = "SELECT COUNT(*) AS loan_count FROM loan_completed_tbl WHERE user_id = '$userLoginId'";
$result4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($result4);
$completedCount = $row4['loan_count'];

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
    <title>User - Dashboard</title>
    <meta name="description" content="Dashboard">
    <link rel="icon" type="image/png" sizes="396x396" href="../assets/img/logo/logo.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/css/bs-theme-overrides.css">
    <link rel="stylesheet" href="../assets/css/animate.min.css">
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
                    <h3 class="fw-bold text-dark">Dashboard</h3>
                    <div class="row">
                        <div class="col-md-6 col-xl-3 mb-4"><a class="text-decoration-none" href="applications-manage.php?id=<?php echo $userLoginId; ?>">
                                <div class="card shadow border-start-primary py-2" data-bss-disabled-mobile="true" data-bss-hover-animate="pulse">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col">
                                                <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span class="text-nowrap text-warning">PENDING APPLICATIONS</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><?php echo '<span>' . $pendingCount . '</span>' ?></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-file-alt fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </a></div>
                        <div class="col-md-6 col-xl-3 mb-4"><a class="text-decoration-none" href="applications-manage.php?id=<?php echo $userLoginId; ?>">
                                <div class="card shadow border-start-success py-2" data-bss-disabled-mobile="true" data-bss-hover-animate="pulse">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col">
                                                <div class="text-uppercase text-success fw-bold text-xs mb-1"><span class="text-nowrap text-sucess">APPROVED APPLICATIONS</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><?php echo '<span>' . $forApprovalCount . '</span>' ?></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-file-signature fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </a></div>
                        <div class="col-md-6 col-xl-3 mb-4"><a class="text-decoration-none" href="loans-active.php?id=<?php echo $userLoginId; ?>">
                                <div class="card shadow border-start-success py-2" data-bss-disabled-mobile="true" data-bss-hover-animate="pulse">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col">
                                                <div class="text-uppercase text-success fw-bold text-xs mb-1"><span class="text-nowrap text-info">Active Loans</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><?php echo '<span>' . $activeLoanCount . '</span>' ?></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-folder-open fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </a></div>
                        <div class="col-md-6 col-xl-3 mb-4"><a class="text-decoration-none" href="loans-completed.php?id=<?php echo $userLoginId; ?>">
                                <div class="card shadow border-start-warning py-2" data-bss-disabled-mobile="true" data-bss-hover-animate="pulse">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col">
                                                <div class="text-uppercase text-warning fw-bold text-xs mb-1"><span class="text-nowrap">COMPLETED LOANS</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><?php echo '<span>' . $completedCount . '</span>' ?></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-folder fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </a></div>
                    </div>

                    <!-- Dashboard 2 -->
                    <div class="row" id="dashboard-2">
                        <div class="col col-md-6 mb-3">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <p class="text-primary m-0 fw-bold">Upcoming Due Loan Payments</p>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info"><?php
                                            // Assuming you have established your database connection
                                            // Fetching data for the table
                                            $formattedDate = date('m/d/Y');
                                            $stmt = $conn->prepare('SELECT * FROM loan_active_tbl
                                                    WHERE (DATEDIFF(da_amortization_date1, ?) BETWEEN 0 AND 7
                                                        OR DATEDIFF(da_amortization_date2, ?) BETWEEN 0 AND 7)
                                                        AND user_id = ? 
                                                    ORDER BY da_amortization_date1');
                    
                                            if (!$stmt) {
                                                die('Error in SQL query: ' . $conn->error); // Check for errors in prepare statement
                                            }
                                            
                                            $stmt->bind_param('sss', $formattedDate, $formattedDate, $userLoginId);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            // Check if there are rows fetched
                                            if ($result->num_rows > 0) {
                                                ?>
                                                <table class="table my-0" id="dataTable">
                                                    <thead>
                                                        <tr class="text-nowrap">
                                                            <th>Loan ID</th>
                                                            <th>User ID</th>
                                                            <th>Name</th>
                                                            <th>Payment Date</th>
                                                            <th>Amount</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        while ($row = $result->fetch_assoc()) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $row['loan_id']; ?></td>
                                                                <td><?php echo $row['user_id']; ?></td>
                                                                <td><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                                                                <td><?php echo $row['da_amortization_date1'] .' | ' .$row['da_amortization_date2']; ?></td>
                                                                <td><?php echo $row['da_amortization_amount1'].' | ' .$row['da_amortization_amount2']; ?></td>
                                                                <td class="d-flex align-items-center">
                                                                    <a class="btn btn-secondary d-flex d-xl-flex justify-content-center align-items-center align-items-xl-center action-btn" href="loans-active-view.php?id=<?php echo $row['loan_id']; ?>" data-bs-toggle="tooltip" data-bss-tooltip="" type="button" title="View" name="update-payment-btn">
                                                                        <i class="fas fa-eye text-light"></i>
                                                                    </a>   
                                                                </td>
                                                                
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <?php
                                            } else {
                                                echo '<div style="text-align: center;">No payments due.</div>';
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot>
                                                <tr></tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col col-md-6 mb-3">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <p class="text-danger m-0 fw-bold">Overdue Loan Payment</p>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                                        
                                        <?php
                                            // Assuming you have established your database connection
                                            // Fetching data for the table
                                            $stmt = $conn->prepare('SELECT * FROM loan_active_tbl
                                            WHERE (? > da_amortization_date1 OR  ? > da_amortization_date2)
                                            AND user_id = ? 
                                            ORDER BY da_amortization_date1');
                    
                                            if (!$stmt) {
                                                die('Error in SQL query: ' . $conn->error); // Check for errors in prepare statement
                                            }
                                            
                                            $stmt->bind_param('sss', $formattedDate, $formattedDate, $userLoginId);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            // Check if there are rows fetched
                                            if ($result->num_rows > 0) {
                                                ?>
                                                <table class="table my-0" id="dataTable">
                                                    <thead>
                                                        <tr class="text-nowrap">
                                                        <th>Loan ID</th>
                                                        <th>User ID</th>
                                                        <th>Name</th>
                                                        <th>Payment Date</th>
                                                        <th>Amount</th>
                                                        <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        while ($row = $result->fetch_assoc()) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $row['loan_id']; ?></td>
                                                                <td><?php echo $row['user_id']; ?></td>
                                                                <td><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                                                                <td><?php echo $row['da_amortization_date1'] .' | ' .$row['da_amortization_date2']; ?></td>
                                                                <td><?php echo $row['da_amortization_amount1'].' | ' .$row['da_amortization_amount2']; ?></td>
                                                                <td class="d-flex align-items-center">
                                                                    <a class="btn btn-secondary d-flex d-xl-flex justify-content-center align-items-center align-items-xl-center action-btn" href="loans-active-view.php?id=<?php echo $row['loan_id']; ?>" data-bs-toggle="tooltip" data-bss-tooltip="" type="button" title="View" name="update-payment-btn">
                                                                        <i class="fas fa-eye text-light"></i>
                                                                    </a>   
                                                                </td>
                                                                </td>
                                                                
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <?php
                                            } else {
                                                echo '<div style="text-align: center;">No payments due.</div>';
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot>
                                                <tr></tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col col-md-12 mb-3">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <p class="text-primary m-0 fw-bold">Notifications</p>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                                        
                                        <?php
                                            // Assuming you have established your database connection
                                            // Fetching data for the table
                                            $stmt = $conn->prepare("SELECT * FROM notif_logs WHERE user_id = ? ORDER BY notif_time DESC");
                                            
                                            $stmt->bind_param('s', $userLoginId);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            // Check if there are rows fetched
                                            if ($result->num_rows > 0) {
                                                ?>
                                                <table class="table my-0" id="dataTable">
                                                    <thead>
                                                        <tr class="text-nowrap">
                                                        <th>Date &amp; Time</th>
                                                        <th>From</th>
                                                        <th>Description</th>
                                                        <th>Comment</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        while ($row = $result->fetch_assoc()) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $row['notif_time']; ?></td>
                                                                <td><?php echo $row['approver']; ?></td>
                                                                <td><?php echo $row['notif_description']; ?></td>
                                                                <td><?php echo $row['comment_delete']; ?></td>
                                                                  
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <?php
                                            } else {
                                                echo '<div style="text-align: center;">No Notifications for today.</div>';
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot>
                                                <tr></tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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