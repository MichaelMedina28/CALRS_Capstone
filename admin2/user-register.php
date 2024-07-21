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

// Include the database connection file
include_once '../db_connection.php';

$sql = "SELECT user_id FROM user_tbl ORDER BY user_id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $last_id = $row["user_id"];
    }
} else {
    echo "0 results";
}

$next_id = $last_id + 1;


$conn->close();

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Admin - Users Register</title>
    <meta name="description" content="Register Users">
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

                    <form id="user-register" method="post" enctype="multipart/form-data" action="user-register-confirm.php">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <p class="text-primary m-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Register</p>

                            </div>
                            <div class="card-body">
                                <div>
                                    <p><strong>User Identification</strong><br><span style="color: rgb(231, 74, 59);">User ID is auto-generated</span></p>
                                    <div class="form-floating mb-3">
                                        <input class="form-control form-control" type="text" id="user-id" placeholder=" " disabled="" required="" autocomplete="off" name="user-id" value="<?php echo $next_id; ?>">
                                        <input type="hidden" name="user-id" value="<?php echo $next_id; ?>"><label class="form-label" for="floatingInput">User ID</label>
                                    </div>
                                    <p><strong>Preferred Password</strong><br></p>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" id="user-pass" placeholder=" " required="" minlength="8" maxlength="20" name="user-pass" oninput="validatePassword()"><label class="form-label" for="floatingInput">Password</label>
                                    </div>
                                    <p id="passwordError" style="color: red;"></p>
                                    <div class="form-floating mb-3"><input class="form-control" type="password" id="user-pass-confirm" placeholder=" " required="" minlength="8" maxlength="20" name="user-pass-confirm">
                                        <label class="form-label" for="floatingInput">Confirm Password</label>
                                    </div>
                                    <p><strong>Name</strong><br><span style="color: rgb(231, 74, 59);">Use of special characters are not allowed/first letter should be capitalized.</span></p>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-lname" placeholder=" " autocomplete="on" required="" name="user-lname" oninput="capitalizeFirstLetter('user-lname')" onblur="checkNameAvailability()"><label class="form-label" for="floatingInput">Surname / Last Name</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-fname" placeholder=" " required="" name="user-fname" oninput="capitalizeFirstLetter('user-fname')" onblur="checkNameAvailability()"><label class="form-label" for="floatingInput">Given Name / First Name</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-mname" placeholder=" " name="user-mname" oninput="capitalizeFirstLetter('user-mname')" onblur="checkNameAvailability()"><label class="form-label class-name" for="floatingInput">Middle Name</label></div>
                                    <div class="form-floating mb-3"><select class="form-select form-select" id="user-sfxname" for="floatinginput" name="user-sfxname">
                                            <option value=" " selected="">N/A</option>
                                            <option value="Jr.">Jr.</option>
                                            <option value="Sr.">Sr.</option>
                                            <option value="II">II</option>
                                            <option value="III">III</option>
                                            <option value="IV">IV</option>
                                            <option value="V">V</option>
                                            <option value="VI">VI</option>
                                        </select><label class="form-label" for="floatinginput">Suffix</label>
                                    </div>
                                    <span id="name-warning" style="color: rgb(231, 74, 59);"></span>
                                    <p><strong>Date of Birth</strong></p>
                                    <div class="mb-3"><input class="form-control form-control" id="user-birthdate" type="date" required name="user-birthdate" max="2002-12-31" min="1958-12-30"></div>
                                    <p><strong>Address</strong></p>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-address-room" placeholder=" " name="user-address-room"><label class="form-label" for="floatingInput">Room / Floor / Unit No. &amp; Building Name</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-address-house" placeholder=" " name="user-address-house"><label class="form-label" for="floatingInput">House / Lot &amp; Block No.</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-address-street" placeholder=" " required="" name="user-address-street"><label class="form-label" for="floatingInput">Street</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-address-subd" placeholder=" " name="user-address-subd"><label class="form-label" for="floatingInput">Subdivision</label></div>
                                    <div class="form-floating mb-3"><select class="form-select form-select" id="user-address-brgy" for="floatinginput" required="" name="user-address-brgy">
                                            <option value="Bagong Kalsada">Bagong Kalsada</option>
                                            <option value="Bañadero">Bañadero</option>
                                            <option value="Banlic">Banlic</option>
                                            <option value="Barandal">Barandal</option>
                                            <option value="Barangay 1">Barangay 1</option>
                                            <option value="Barangay 2">Barangay 2</option>
                                            <option value="Barangay 3">Barangay 3</option>
                                            <option value="Barangay 4">Barangay 4</option>
                                            <option value="Barangay 5">Barangay 5</option>
                                            <option value="Barangay 6">Barangay 6</option>
                                            <option value="Barangay 7">Barangay 7</option>
                                            <option value="Batino">Batino</option>
                                            <option value="Bubuyan">Bubuyan</option>
                                            <option value="Bucal">Bucal</option>
                                            <option value="Bunggo">Bunggo</option>
                                            <option value="Burol">Burol</option>
                                            <option value="Camaligan">Camaligan</option>
                                            <option value="Canlubang">Canlubang</option>
                                            <option value="Halang">Halang</option>
                                            <option value="Hornalan">Hornalan</option>
                                            <option value="Kay-Anlog">Kay-Anlog</option>
                                            <option value="La Mesa">La Mesa</option>
                                            <option value="Laguerta">Laguerta</option>
                                            <option value="Lawa">Lawa</option>
                                            <option value="Lecheria">Lecheria</option>
                                            <option value="Lingga">Lingga</option>
                                            <option value="Looc">Looc</option>
                                            <option value="Mabato">Mabato</option>
                                            <option value="Majada Labas">Majada Labas</option>
                                            <option value="Makiling">Makiling</option>
                                            <option value="Mapagong">Mapagong</option>
                                            <option value="Masili">Masili</option>
                                            <option value="Maunong">Maunong</option>
                                            <option value="Mayapa">Mayapa</option>
                                            <option value="Milagrosa">Milagrosa</option>
                                            <option value="Paciano Rizal">Paciano Rizal</option>
                                            <option value="Palingon">Palingon</option>
                                            <option value="Palo-Alto">Palo-Alto</option>
                                            <option value="Pansol">Pansol</option>
                                            <option value="Parian">Parian</option>
                                            <option value="Prinza">Prinza</option>
                                            <option value="Punta">Punta</option>
                                            <option value="Puting Lupa">Puting Lupa</option>
                                            <option value="Real">Real</option>
                                            <option value="Saimsim">Saimsim</option>
                                            <option value="Sampiruhan">Sampiruhan</option>
                                            <option value="San Cristobal">San Cristobal</option>
                                            <option value="San Jose">San Jose</option>
                                            <option value="San Cristobal">San Juan</option>
                                            <option value="Sirang Lupa">Sirang Lupa</option>
                                            <option value="Sucol">Sucol</option>
                                            <option value="Turbina">Turbina</option>
                                            <option value="Ulango">Ulango</option>
                                            <option value="Uwisan">Uwisan</option>
                                        </select><label class="form-label" for="floatingInput">Barangay</label></div>
                                    <p><strong>Additional Information</strong></p>
                                    <div class="form-floating mb-3"><select class="form-select form-select" id="user-position" for="floatinginput" required="" name="user-position">
                                            <option value="Kamay-ari">Kamay-ari</option>
                                            <option value="Regular">Regular</option>
                                            <option value="Farmer">Farmer</option>
                                            <option value="Associate">Associate</option>
                                            <option value="Non-Farmer">Non-Farmer</option>
                                        </select><label class="form-label" for="floatingInput">Position</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="number" id="user-investment" placeholder=" " name="share-investment"><label class="form-label" for="floatingInput">Share on Investment</label></div>
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-spouse-name" placeholder=" " name="user-spouse-name"><label class="form-label" for="floatingInput">Spouse Name</label></div>
                                    <p><strong>Account Picture</strong><br><span style="color: rgb(231, 74, 59);">Optional. Use a 1x1 aspect ratio digital copy&nbsp; of your profile picture</span></p>
                                    <div class="mb-3">
                                        <input class="form-control" type="file" id="user-picture-profile" required="" name="user-picture-profile" accept=".jpg,.jpeg,.png,.gif,.bmp|image/*" onchange="validatePicture()">
                                    </div>
                                    <p id="errorMessage" style="color: red;"></p>
                                    <p><strong>Mobile Number</strong><br><span style="color: rgb(231, 74, 59);">Please provide&nbsp; an active mobile number (i.e. 09123456789)</span></p>
                                    <div class="form-floating mb-3"><input class="form-control form-control-sm" type="number" id="user-mnumber" required="" placeholder=" " name="user-mnumber"><label class="form-label" for="floatingInput">Mobile Number</label></div>
                                    <p id="error-message" style="color: red;"></p>
                                    <p><strong>Email</strong><br></p>
                                    <div class="form-floating mb-3"><input class="form-control form-control-sm" type="email" id="user-email" placeholder=" " autocomplete="on" name="user-email"><label class="form-label" for="floatingInput">Email Address</label></div>
                                    <p><strong>Preferred Document</strong><br><span style="color: rgb(231, 74, 59);">Document for Registration and Proof of identity/ownership</span></p>
                                    <div class="mb-3">
                                        <div class="form-floating mb-3"><select class="form-select form-select" id="user-pdocument-type" for="floatinginput" required="" name="user-pdocument-type">
                                                <option value="Valid ID" selected="">Valid ID</option>
                                                <option value="Land Title">Land Title</option>
                                                <option value="Tax Declaration">Tax Declaration</option>
                                                <option value="Certificate of Land Ownership Award (CLOA)">Certificate of Land Ownership Award (CLOA)</option>
                                                <option value="Business Permit">Business Permit</option>
                                                <option value="Other">Other</option>
                                            </select><label class="form-label" for="floatinginput">Preferred Document Type</label></div><input class="form-control" type="file" id="user-pdocument" required="" name="user-pdocument" accept=".pdf,.jpg,.jpeg,.png,.gif,.bmp|image/*" onchange="validateDocument()">
                                    </div>
                                    <p id="errorMessage2" style="color: red;"></p>
                                    <div class="mb-3">
                                        <div class="form-check"><input class="form-check-input" type="checkbox" id="user-register-checkbox"><label class="form-check-label" for="formCheck-2">All information including people, name, and agreements in this User Registration is legitimate, accurate, and abides with proper consent and CRG-MPC's regulations.<span style="color: rgb(231, 74, 59);">*</span></label></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-xl-flex justify-content-xl-end">
                                <button class="btn btn-primary btn-icon-split m-1" id="submit-for-approval-btn" type="submit" data-bs-toggle="modal" name="submit-for-approval-btn"><span class="text-white-50 icon"><i class="fas fa-user-plus"></i></span><span class="text-white text">Register User</span></button>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>

    //check name availability 
    function checkNameAvailability() {
        // Get the input values
        var firstName = document.getElementById('user-fname').value;
        var middleName = document.getElementById('user-mname').value;
        var lastName = document.getElementById('user-lname').value;
        var namecond = "";
        const userRegisterRegister = document.getElementById('submit-for-approval-btn');
        const userRegisterCheckbox = document.getElementById('user-register-checkbox');

        console.log('Sending AJAX request with data:', firstName, middleName, lastName);

        // Send an AJAX request to the server
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'check_name_availability.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;
                console.log('Received response:', response);

                if (response === 'exists') {
                    // Display a warning message and disable form submission
                    document.getElementById('name-warning').innerHTML = 'Name already exists';
                    document.getElementById('submit-for-approval-btn').disabled = true;
                } else {
                    // Clear the warning message and enable form submission
                    document.getElementById('name-warning').innerHTML = '';
                    var namecond = "Yes";
                }

                if (namecond == "Yes" && userRegisterCheckbox.checked){
                    userRegisterRegister.disabled = false;
                    console.log("pwede");
                }else {
                    userRegisterRegister.disabled = true;
                    console.log("hindi pwede");
                }
            }
        };
        xhr.send('fname=' + encodeURIComponent(firstName) + '&mname=' + encodeURIComponent(middleName) + '&lname=' + encodeURIComponent(lastName));
    }
    //end of code




        const userRegisterRegister = document.getElementById('submit-for-approval-btn');
        const userRegisterCheckbox = document.getElementById('user-register-checkbox');
        userRegisterRegister.disabled = true;
        userRegisterCheckbox.addEventListener('click', function() {
            if (userRegisterCheckbox.checked) {
                userRegisterRegister.disabled = false;
            } else {
                userRegisterRegister.disabled = true;
            }
        });

        //Validate Password
        function validatePassword() {
            var password = document.getElementById("user-pass").value;
            var passwordError = document.getElementById("passwordError");
            var isValidPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$/.test(password);

            if (!isValidPassword) {
                passwordError.textContent = "Password must be 8-20 characters long and include at least one lowercase letter, one uppercase letter, and one digit.";
                return;
            }
            passwordError.textContent = '';
        }

        //Validate Name
        function capitalizeFirstLetter(id) {
            var inputElement = document.getElementById(id);
            var inputValue = inputElement.value;

            // Capitalize the first letter and add the rest of the string
            var capitalizedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1);

            // Update the input field with the capitalized value
            inputElement.value = capitalizedValue;
        }

        //Validate Mobile
        document.getElementById('user-mnumber').addEventListener('input', function() {
            validateMobileNumber();
        });

        function validateMobileNumber() {
            var mobileNumber = document.getElementById('user-mnumber').value;
            var errorMessage = document.getElementById('error-message');

            // Remove non-digit characters
            var numericMobileNumber = mobileNumber.replace(/\D/g, '');

            if (numericMobileNumber.length === 11) {
                errorMessage.textContent = ''; // Clear error message
            } else {
                errorMessage.textContent = 'Please enter a valid 11-digit mobile number.';
            }
        }

        //Validate Picture
        function validatePicture() {
            var input = document.getElementById('user-picture-profile');
            var errorMessage = document.getElementById('errorMessage');
            var file = input.files[0];

            if (file) {
                // Check if the file size is less than or equal to 5MB (5 * 1024 * 1024 bytes)
                if (file.size > 5 * 1024 * 1024) {
                    errorMessage.textContent = 'File size must be less than or equal to 5MB.';
                    input.value = ''; // Clear the file input
                    return;
                }

                // Clear any previous error messages
                errorMessage.textContent = '';
            }
        }

        //Validate Document
        function validateDocument() {
            var input = document.getElementById('user-pdocument');
            var errorMessage2 = document.getElementById('errorMessage2');
            var file = input.files[0];

            if (file) {
                // Check if the file size is less than or equal to 5MB (5 * 1024 * 1024 bytes)
                if (file.size > 5 * 1024 * 1024) {
                    errorMessage2.textContent = 'File size must be less than or equal to 5MB.';
                    input.value = ''; // Clear the file input
                    return;
                }

                // Clear any previous error messages
                errorMessage.textContent = '';
            }
        }
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