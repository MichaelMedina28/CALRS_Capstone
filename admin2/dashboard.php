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

include_once '../db_connection.php';

// Query to get the count of items in app_pending_tbl
$query = "SELECT  
(SELECT COUNT(*) FROM lo_app_pending_tbl) AS count1,
(SELECT COUNT(*) FROM app_approved_tbl) AS count2,
(SELECT COUNT(*) FROM loan_active_tbl) AS count3,
(SELECT COUNT(*) FROM loan_completed_tbl) AS count4";
$result = mysqli_query($conn, $query);

// Fetch the count from the result
$row = mysqli_fetch_assoc($result);
$totalCount1 = $row['count1'];
$totalCount2 = $row['count2'];
$totalCount3 = $row['count3'];
$totalCount4 = $row['count4'];

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Admin - Dashboard</title>
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
            <div class="container d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="dashboard.php">
                    <div class="sidebar-brand-icon"><img class="border rounded-circle" id="nav-img" src="../assets/img/logo/logo.png"></div>
                    <div class="sidebar-brand-text mx-3"><span>CRG-MPC</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>

                    <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-2" href="#collapse-2" role="button"><i class="fas fa-folder"></i>&nbsp;<span>Loans</span></a>
                            <div class="collapse" id="collapse-2">
                                <div class="bg-white border rounded py-2 collapse-inner">
                                    <h6 class="collapse-header">LOANS</h6>
                                    <a class="collapse-item" href="loans-active.php">Active Loans</a>
                                    <a class="collapse-item" href="loans-completed.php">Completed Loans</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-4" href="#collapse-4" role="button"><i class="fas fa-trash"></i>&nbsp;<span>Archived</span></a>
                            <div class="collapse" id="collapse-4">
                                <div class="bg-white border rounded py-2 collapse-inner">
                                    <h6 class="collapse-header">Archived</h6>
                                    <a class="collapse-item" href="archived-completed-loans.php">Completed Loans</a>
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

                            <!--
                            <li class="nav-item dropdown no-arrow mx-1">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="badge bg-danger badge-counter">3+</span><i class="fas fa-bell fa-fw"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-list animated--grow-in">
                                        <h6 class="dropdown-header">alerts center</h6><a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="me-3">
                                                <div class="bg-primary icon-circle"><i class="fas fa-file-alt text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 12, 2019</span>
                                                <p>A new monthly report is ready to download!</p>
                                            </div>
                                        </a><a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="me-3">
                                                <div class="bg-success icon-circle"><i class="fas fa-donate text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 7, 2019</span>
                                                <p>$290.29 has been deposited into your account!</p>
                                            </div>
                                        </a><a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="me-3">
                                                <div class="bg-warning icon-circle"><i class="fas fa-exclamation-triangle text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 2, 2019</span>
                                                <p>Spending Alert: We've noticed unusually high spending for your account.</p>
                                            </div>
                                        </a><a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                                    </div>
                                </div>
                            </li>
                             -->

                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#">
                                        <div class="d-flex flex-column align-items-lg-end me-2">
                                            <div class="row" id="nav-profile">
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-fname"><?php echo $_SESSION["admin_fname"]; ?></span><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-lname"><?php echo $_SESSION["admin_lname"]; ?></span></div>
                                            </div>
                                            <div class="row" id="nav-profile">
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-acc">LOAN OFFICER</span></div>
                                            </div>
                                        </div><img class="border rounded-circle img-profile" id="nav-profile-img" src="../assets/img/profile/profile-default.png" name="nav-profile-img">
                                    </a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                        <a class="dropdown-item" href="profile.php">
                                            <i class="fas fa-user fa-sm fa-fw" id="profile-icon"></i>Profile</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="activity-log.php">
                                            <i class="fas fa-list-alt fa-sm fa-fw" id="profile-icon"></i>Activity Log</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="logout.php">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw" id="profile-icon"></i>Logout</a>

                                    </div>

                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <h3 class="fw-bold text-dark">Dashboard</h3>
                    <div class="row">
                        <div class="col-md-6 col-xl-3 mb-4"><a class="text-decoration-none" href="applications-pending.php">
                                <div class="card shadow border-start-primary py-2" data-bss-disabled-mobile="true" data-bss-hover-animate="pulse">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col">
                                                <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span class="text-nowrap text-warning">NEW CA SUBMISSIONS</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><?php echo '<span>' . $totalCount1 . '</span>' ?></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-file-alt fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </a></div>
                        <div class="col-md-6 col-xl-3 mb-4"><a class="text-decoration-none" href="applications-approved.php">
                                <div class="card shadow border-start-success py-2" data-bss-disabled-mobile="true" data-bss-hover-animate="pulse">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col">
                                                <div class="text-uppercase text-success fw-bold text-xs mb-1"><span class="text-nowrap text-success">APPROVED APPLICATIONS</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><?php echo '<span>' . $totalCount2 . '</span>' ?></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-file-signature fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </a></div>
                        <div class="col-md-6 col-xl-3 mb-4"><a class="text-decoration-none" href="loans-active.php">
                                <div class="card shadow border-start-success py-2" data-bss-disabled-mobile="true" data-bss-hover-animate="pulse">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col">
                                                <div class="text-uppercase text-success fw-bold text-xs mb-1"><span class="text-nowrap text-info">ACTIVE LOANS</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><?php echo '<span>' . $totalCount3 . '</span>' ?></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-folder fa-2x text-gray-300"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </a></div>
                        <div class="col-md-6 col-xl-3 mb-4"><a class="text-decoration-none" href="loans-completed.php">
                                <div class="card shadow border-start-warning py-2" data-bss-disabled-mobile="true" data-bss-hover-animate="pulse">
                                    <div class="card-body">
                                        <div class="row align-items-center no-gutters">
                                            <div class="col">
                                                <div class="text-uppercase text-warning fw-bold text-xs mb-1"><span class="text-nowrap text-primary">COMPLETED LOANS</span></div>
                                                <div class="text-dark fw-bold h5 mb-0"><?php echo '<span>' . $totalCount4 . '</span>' ?></div>
                                            </div>
                                            <div class="col-auto"><i class="fas fa-user-friends fa-2x text-gray-300"></i></div>
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
                                    <p class="text-primary m-0 fw-bold">Upcoming Due Loan Payment</p>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                                        <?php
                                        // Assuming you have established your database connection
                                        // Fetching data for the table
                                        $stmt = $conn->prepare('SELECT * FROM loan_active_tbl
                                            WHERE DATEDIFF(da_amortization_date1, CURDATE()) BETWEEN 0 AND 7
                                                OR (DATEDIFF(da_amortization_date2, CURDATE()) BETWEEN 0 AND 7)
                                            ORDER BY da_amortization_date1');

                                        if (!$stmt) {
                                            die('Error in SQL query: ' . $conn->error); // Check for errors in prepare statement
                                        }

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
                                                            <td><?php echo $row['da_amortization_date1'] . ' | ' . $row['da_amortization_date2']; ?></td>
                                                            <td><?php echo $row['da_amortization_amount1'] . ' | ' . $row['da_amortization_amount2']; ?></td>
                                                            <td class="d-flex align-items-center">
                                                                <a class="btn btn-success d-flex d-xl-flex justify-content-center align-items-center align-items-xl-center action-btn" href="loans-active-manage.php?id=<?php echo $row['loan_id']; ?>" data-bs-toggle="tooltip" data-bss-tooltip="" type="button" title="Update" name="update-payment-btn">
                                                                    <i class="fas fa-money-bill text-light"></i>
                                                                </a>
                                                                <a class="btn btn-info d-flex d-xl-flex justify-content-center align-items-center align-items-xl-center action-btn" href="dashboard-notif-confirm.php?id=<?php echo $row['loan_id']; ?>" id="notify-btn" data-bs-toggle="tooltip" data-bss-tooltip="" title="Notify" name="notify-btn">
                                                                    <i class="fas fa-bell text-light"></i>
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
                                    <p class="text-info m-0 fw-bold">Active Loans List</p>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">

                                        <?php
                                        // Assuming you have established your database connection
                                        // Fetching data for the table
                                        $stmt = $conn->prepare('SELECT * FROM loan_active_tbl
                                            WHERE (CURDATE() > da_amortization_date1 OR CURDATE() > da_amortization_date2)
                                            ORDER BY da_amortization_date1');

                                        if (!$stmt) {
                                            die('Error in SQL query: ' . $conn->error); // Check for errors in prepare statement
                                        }

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
                                                            <td><?php echo $row['da_amortization_date1'] . ' | ' . $row['da_amortization_date2']; ?></td>
                                                            <td><?php echo $row['da_amortization_amount1'] . ' | ' . $row['da_amortization_amount2']; ?></td>
                                                            <td class="d-flex align-items-center">
                                                                <a class="btn btn-success d-flex d-xl-flex justify-content-center align-items-center align-items-xl-center action-btn" href="loans-active-manage.php?id=<?php echo $row['loan_id']; ?>" data-bs-toggle="tooltip" data-bss-tooltip="" type="button" title="Update" name="update-payment-btn">
                                                                    <i class="fas fa-money-bill text-light"></i>
                                                                </a>
                                                                <a class="btn btn-info d-flex d-xl-flex justify-content-center align-items-center align-items-xl-center action-btn" href="dashboard-notif2-confirm.php?id=<?php echo $row['loan_id']; ?>" id="notify-btn" data-bs-toggle="tooltip" data-bss-tooltip="" title="Notify" name="notify-btn">
                                                                    <i class="fas fa-bell text-light"></i>
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