<?php
$queryResult = "0";
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

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Admin - Users Manage</title>
    <meta name="description" content="Manage Users">
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
                                    <a class="collapse-item" href="applications-pending.php">New Submissions</a>
                                    <a class="collapse-item" href="applications-approved.php">Approved</a>
                                    <a class="collapse-item" href="applications-disapproved.php">Disapproved</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-3" href="#collapse-3" role="button"><i class="fas fa-user-friends"></i>&nbsp;<span>Users</span></a>
                            <div class="collapse" id="collapse-3">
                                <div class="bg-white border rounded py-2 collapse-inner">
                                    <h6 class="collapse-header">users</h6>
                                    <a class="collapse-item" href="user-register.php">Register User</a>
                                    <a class="collapse-item" href="user-pending.php">Pending Users</a>
                                    <a class="collapse-item" href="user-approved.php">Approved Users</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div><a class="btn btn-link nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapse-4" href="#collapse-4" role="button"><i class="fas fa-trash"></i>&nbsp;<span>Archived</span></a>
                            <div class="collapse" id="collapse-4">
                                <div class="bg-white border rounded py-2 collapse-inner">
                                    <h6 class="collapse-header">Archived</h6>
                                    <a class="collapse-item" href="archived-approved-applications.php">Approved CA</a>
                                    <a class="collapse-item" href="archived-disapproved-applications.php">Dispproved CA</a>
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
                                                <div class="col"><span class="d-none d-lg-inline" id="nav-profile-name" name="nav-profile-acc">CREDIT COMMITTEE</span></div>
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
                    <h3 class="fw-bold text-dark">Users</h3>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold"><i class="fas fa-check-circle me-2"></i>Approved</p>
                        </div>
                        <div class="card-body">
                            <!--Start of Search-->
                            <div class="row">
                                <div class="col-md-6 text-nowrap">
                                    <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable">
                                        <label class="form-label">
                                            Show
                                            <select class="d-inline-block form-select form-select-sm" id="manageuserpagecount">
                                                <option value="10" selected="">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex justify-content-end align-items-start">
                                    <button class="btn btn-primary btn-icon-split m-1" id="printButton"><span class="text-white-50 icon"><i class="fa fa-print"></i></span><span class="text-white text">Print</span></button>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input class="bg-light form-control border-0 small" type="text" id="user-search" placeholder="Search">
                                        <button class="btn btn-primary py-0" type="button" id="user-search-btn"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <!--End of Search-->
                            <div class="table-responsive text-nowrap table mt-2" role="grid" aria-describedby="dataTable_info">
                                <table class="table table-hover table-bordered my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Mobile #</th>
                                            <th>Position</th>
                                            <th>Share Amount(₱)</th>
                                            <th>User Status</th>
                                            <th>Actions</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Assuming you have a database connection established
                                        include_once '../db_connection.php';
                                        
                                        // Retrieve data from the database
                                        $query = "SELECT * FROM user_tbl WHERE share_investment >= 20000 ORDER BY user_id DESC";
                                        $result = mysqli_query($conn, $query);

                                        /// Display data in the table

                                        /// Display data in the table
                                        if (mysqli_num_rows($result) > 0) {
                                            $queryResult = mysqli_num_rows($result);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $formatted_value = number_format($row['share_investment'], 2, '.', ',');
                                                echo "<tr>";
                                                echo "<td>" . $row['user_id'] . "</td>";
                                                echo '<td><img class="rounded-circle me-2" width="30" height="30" src="../admin/' . $row["account_pictures"] . '">' . $row['user_fname'] . " " . $row['user_lname'] . " " . $row['user_sfxname'] . '</td>';
                                                echo "<td>" . $row['user_mnumber'] . "</td>";
                                                echo "<td>" . $row['position'] . "</td>";
                                                echo "<td>" . $formatted_value . "</td>";

                                                $status = $row['user_status'];
                                                if ($status == "Active") {
                                                    echo '<td><span class="badge text-bg-success text-white">Active</span></td>';
                                                } elseif ($status == "Active: Delinquent") {
                                                    echo '<td><span class="badge text-bg-warning text-white">Active: Delinquent</span></td>';
                                                } elseif ($status == "Inactive") {
                                                    echo '<td><span class="badge text-bg-danger text-white">Inactive</span></td>';
                                                } else {
                                                    // Handle any other status values here
                                                    echo '<td></td>';
                                                }

                                                echo "<td>
                                                    <button class='btn btn-primary action-btn p-0' data-bs-toggle='tooltip' data-bss-tooltip='' type='button' title='Manage' onclick=\"window.location.href='user-approved-update.php?id=" . $row['user_id'] . "'\"><i class='fas fa-edit'></i></button>
                                                    </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            // Handle no results here
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="10"><strong>Total Approved Users: <?php echo $queryResult; ?></strong></td>
                                            <!-- Assuming you have 10 columns in your table, adjust colspan value if needed -->
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 align-center">

                                </div>
                                <div class="col-md-6">
                                    <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                        <ul class="pagination">
                                            <li class="page-item"><a class="page-link" href="#">«</a></li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">»</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchButton = document.getElementById("user-search-btn");
            const searchInput = document.getElementById("user-search");
            const table = document.querySelector("table");
            const rows = table.querySelectorAll("tbody tr");

            searchButton.addEventListener("click", function() {
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

        document.addEventListener('DOMContentLoaded', function() {
            const rowsSelect = document.getElementById('manageuserpagecount');
            const tableRows = document.querySelectorAll('#dataTable tbody tr');

            rowsSelect.addEventListener('change', function() {
                const selectedRows = parseInt(this.value);

                for (let i = 0; i < tableRows.length; i++) {
                    if (i < selectedRows) {
                        tableRows[i].style.display = ''; // Show rows up to the selected number
                    } else {
                        tableRows[i].style.display = 'none'; // Hide rows beyond the selected number
                    }
                }
            });

            const initialRows = parseInt(rowsSelect.value);
            for (let i = 0; i < tableRows.length; i++) {
                if (i < initialRows) {
                    tableRows[i].style.display = ''; // Show rows up to the default selected number
                } else {
                    tableRows[i].style.display = 'none'; // Hide rows beyond the default selected number
                }
            }
        });

        $(document).ready(function() {
            $("#printButton").on("click", function() {
                var printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Print Active Table</title>');

                // Include CSS styles for table printing
                printWindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">');
                // Add any additional CSS files or styles if needed

                printWindow.document.write('</head><body>');

                // Get the entire HTML content of the table, excluding the "Actions" column
                var tableContent = $("#dataTable").clone();
                tableContent.find('th:contains("Actions")').remove();
                tableContent.find('th:contains("Status")').remove();
                tableContent.find('td:nth-child(5), th:nth-child(5)').remove();
                tableContent.find('td:nth-child(5), th:nth-child(5)').remove();

                // Write the table content to the new window for printing
                printWindow.document.write(tableContent[0].outerHTML);
                printWindow.document.write('</body></html>');
                printWindow.document.close();

                // Wait for the content to load before printing
                printWindow.onload = function() {
                    printWindow.print();
                };
            });
        });

        //page count button 
        document.addEventListener('DOMContentLoaded', function() {
            const rowsSelect = document.getElementById('manageuserpagecount');
            const tableRows = document.querySelectorAll('#dataTable tbody tr'); // Select all table rows
            const pagination = document.querySelector('.pagination');

            let currentPage = 1;
            let rowsPerPage = parseInt(rowsSelect.value);
            const totalRows = tableRows.length;

            // Calculate total pages based on selected rows
            let totalPages = Math.ceil(totalRows / rowsPerPage);

            // Function to display rows for the current page
            function displayRows(page) {
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                // Loop through all rows and hide/show based on the current page
                tableRows.forEach((row, index) => {
                    if (index >= start && index < end) {
                        row.style.display = ''; // Show rows for the current page
                    } else {
                        row.style.display = 'none'; // Hide rows for other pages
                    }
                });
            }

            // Function to update pagination buttons based on current page and total pages
            function updatePagination() {
                pagination.innerHTML = '';

                const range = 1; // Number of pages to show before and after the current page

                // Previous Button
                const prevButton = document.createElement('li');
                prevButton.className = 'page-item';
                prevButton.innerHTML = '<a class="page-link" href="#">Previous</a>';
                prevButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (currentPage > 1) {
                        currentPage--;
                        displayRows(currentPage);
                        updatePagination();
                    }
                });
                pagination.appendChild(prevButton);

                // Numbered Buttons
                const startRange = Math.max(1, currentPage - range);
                const endRange = Math.min(totalPages, currentPage + range);

                for (let i = startRange; i <= endRange; i++) {
                    const pageButton = document.createElement('li');
                    pageButton.className = 'page-item';
                    const link = document.createElement('a');
                    link.className = 'page-link';
                    link.href = '#';
                    link.textContent = i;
                    pageButton.appendChild(link);

                    pageButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentPage = i;
                        displayRows(currentPage);
                        updatePagination();
                    });

                    pagination.appendChild(pageButton);
                }

                // Next Button
                const nextButton = document.createElement('li');
                nextButton.className = 'page-item';
                nextButton.innerHTML = '<a class="page-link" href="#">Next</a>';
                nextButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (currentPage < totalPages) {
                        currentPage++;
                        displayRows(currentPage);
                        updatePagination();
                    }
                });
                pagination.appendChild(nextButton);

                // Show the first page initially
                displayRows(currentPage);
            }

            // Event listener for the change in number of rows to show
            rowsSelect.addEventListener('change', function() {
                currentPage = 1;
                rowsPerPage = parseInt(this.value);
                totalPages = Math.ceil(totalRows / rowsPerPage);
                displayRows(currentPage);
                updatePagination();
            });

            // Initialize pagination
            updatePagination();
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