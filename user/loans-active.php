<?php
session_start();
if(!isset($_SESSION['userloggedin']) || $_SESSION['userloggedin'] !== true){
    header("Location: ../index.php");
    exit;
}
$userLoginId = $_GET['id'];

include_once '../db_connection.php';

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
    <title>User - Loans Active</title>
    <meta name="description" content="Loans Active">
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
                    <div class="sidebar-brand-icon"><img class="border rounded-circle" id="nav-img" src="../assets/img/logo/logo.png"></div>
                    <div class="sidebar-brand-text mx-3"><span>CRG-MPC</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php?id=<?php echo $userLoginId; ?>"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-1" href="#collapse-1" role="button"><i class="fas fa-file-alt"></i>&nbsp;<span>Applications</span></a>
                            <div class="collapse" id="collapse-1">
                                <div class="bg-white border rounded py-2 collapse-inner">
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
                            <div><i class="far fa-calendar-alt text-primary me-1" id="nav-datetime-icon"></i><span id="current-date"  class="current-datetime" style="font-size: 12px;">Sun | January 1, 2023</span></div>
                            <div><i class="far fa-clock text-primary me-1" id="nav-datetime-icon"></i><span id="current-time" class="current-datetime" style="font-size: 12px;">1:00:00 AM</span></div>
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
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                        <a class="dropdown-item" href="profile.php?id=<?php echo $userLoginId; ?>"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="user-notification.php?id=<?php echo $userLoginId; ?>"><i class="fas fa-bell fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Notifications</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                <h3 class="fw-bold text-dark">Loans</h3>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold"><i class="fas fa-check-circle me-2"></i>Active</p>
                        </div>
                        <div class="card-body">
                        <!--Start of Search-->
                        <div class="row">
                        <div class="col-md-9 text-nowrap">
                            <!-- <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable">
                                <label class="form-label">
                                    Show&nbsp;
                                    <select class="d-inline-block form-select form-select-sm" id="activepagecount">
                                        <option value="10" selected="">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>&nbsp;
                                </label>
                            </div> -->
                        </div>
                            <div class="col-md-3">
                            <div class="input-group">
                                        <input class="bg-light form-control border-0 small" type="text" id="active-search" placeholder="Search">
                                        <button class="btn btn-primary py-0" type="button" id="active-search-btn"><i class="fas fa-search"></i></button>
                                    </div>
                            </div>
                        </div>
                        <!--End of Search-->
                            <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                                <table class="table table-hover table-bordered my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Loan ID</th>
                                            <th>Name</th>
                                            <th>Date Issued</th>
                                            <th>Term End</th>
                                            <th>Position</th>
                                            <th>Amount (₱)</th>
                                            <th>Amount Balance(₱)</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                            // Assuming you have a database connection established
                                            include_once '../db_connection.php';

                                            // Retrieve data from the database
                                            $query = "SELECT * FROM loan_active_tbl WHERE user_id = '$userLoginId' ORDER BY loan_id DESC";
                                            $result = mysqli_query($conn, $query);

                                            // Display data in the table
                                            if (mysqli_num_rows($result) > 0) {
                                                
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    
                                                    echo "<tr>";
                                                    echo "<td>" . $row['loan_id'] . "</td>";
                                                    echo "<td>" . $row['fname'] . " " . $row['lname'] . " " . $row['sfxname'] . "</td>";
                                                    echo "<td>" . $row['term_start'] . "</td>";
                                                    echo "<td>" . $row['term_end'] . "</td>";
                                                    echo "<td>" . $row['position'] . "</td>";
                                                    echo "<td>" . $row['amount_number'] . "</td>";
                                                    echo "<td>" . $row['al_amount_balance'] . "</td>";
                                                    echo "<td button class='d-flex align-items-center'>
                                                          <button class='btn btn-primary d-flex d-xl-flex justify-content-center align-items-center align-items-xl-center action-btn' data-bss-tooltip='' id='action-btn' type='button' title='View' onclick=\"window.location.href='loans-active-view.php?id=". $row['loan_id'] ."'\"><i class='far fa-eye'></i></a></button>";
                                                    
                                                    //echo "<button class='btn btn-danger d-flex d-xl-flex justify-content-center align-items-center justify-content-xl-center align-items-xl-center action-btn'  data-bss-tooltip='' id='action-btn' type='button' title='Delete' onclick=\"window.location.href='applications-manage-delete-confirm.php?id=". $row['loan_id'] ."'\"><i class='fas fa-trash'></i></button>";
                                                    echo "</td>";
                                                    echo "</tr>";       
                                                }
                                                } else {
                                                        
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
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © City College of Calamba 2023</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" data-bs-toggle="tooltip" data-bss-tooltip="" id="page-top-btn" href="#page-top" title="Return to Top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const searchButton = document.getElementById("active-search-btn");
        const searchInput = document.getElementById("active-search");
        const table = document.querySelector("table");
        const rows = table.querySelectorAll("tbody tr");

        searchButton.addEventListener("click", function () {
            const searchTerm = searchInput.value.toLowerCase();

            rows.forEach((row) => {
                const cells = row.getElementsByTagName("td");
                let found = false;

                for (let cell of cells) {
                    if (cell.innerHTML.toLowerCase().includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }

                if (found) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
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