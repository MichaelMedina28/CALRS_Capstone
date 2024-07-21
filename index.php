<?php

//session for login attempts
session_start();

if (!isset($_SESSION['loginAttempts'])) {
    $_SESSION['loginAttempts'] = 0;
}

$cooldownPeriod = 5 * 60; // 5 minutes in seconds
if (isset($_SESSION['lastLoginAttempt']) && time() - $_SESSION['lastLoginAttempt'] < $cooldownPeriod) {
    // Display an error message indicating that the user should wait before attempting another login
    $error = "Please wait for the cooldown period to expire before attempting another login.";
} else {
    // Allow the login attempt
    // Reset the login attempts and last login attempt timestamp
    $loginAttempts = 0;
    $_SESSION['loginAttempts'] = $loginAttempts;
    $_SESSION['lastLoginAttempt'] = time();
}


$loginAttempts = $_SESSION['loginAttempts'];

$remainingAttempts = 5 - $loginAttempts;

$error = "";
$error2 = "";
// Check if the form is submitted and the reCAPTCHA response is present
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['g-recaptcha-response'])) {
    // Get the user input from the form
    $userLoginId = $_POST["user-login-id"];
    $userLoginPassword = $_POST["user-login-pass"];

    // Validate the reCAPTCHA response
    $captchaResponse = $_POST['g-recaptcha-response'];
    $secretKey = '6Lf_LMMoAAAAAJKLQOrehjooCFILHUrBWfwLB62Q';

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secretKey,
        'response' => $captchaResponse
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result);


    if ($response->success) {
        // Include the database connection file
        include_once 'db_connection.php';

        // Prepare the SQL statement to check if the user exists
        $sql = "SELECT * FROM user_tbl WHERE user_id = '$userLoginId'";
        $result = $conn->query($sql);

        // Check if the login is successful
        if ($result->num_rows == 1) {
            // Fetch the user data
            $row = $result->fetch_assoc();

            $storedPassword = $row["user_pass"];
            $profile = $row["profile"]; // Assuming 'profile' is the column in the user_tbl table

            // Get the user's last name and first name

            if (password_verify($userLoginPassword, $storedPassword)) {

                // Redirect based on admin profile
                if ($profile == 'admin' || $profile == 'admin2' || $profile == 'admin3') {


                    if (isset($_SESSION['adminloggedin'])) {
                        // Destroy the existing admin session
                        session_destroy();
                        session_start();
                    }

                    // Redirect to the appropriate admin dashboard
                    header("Location: {$profile}/dashboard.php?id=$userLoginId");
                
                    $_SESSION["adminloggedin"] = true;
                    $_SESSION["id"] = $userLoginId;
                
                    // Set $fname and $lname based on the common columns
                    $lname = $row["user_lname"];
                    $fname = $row["user_fname"];
                
                    $_SESSION["admin_lname"] = $lname;
                    $_SESSION["admin_fname"] = $fname;
                    $_SESSION['admin_role'] = $profile;

                    $userId = $row["user_id"];
                    $description = "Logged in";
                    $logSql = "INSERT INTO login_logs (user_id, notif_description) VALUES ('$userLoginId', '$description')";
                    $conn->query($logSql);

                    $loginAttempts = 0;
                    $_SESSION['loginAttempts'] = $loginAttempts;

                } else {

                    session_destroy();
                    session_start();

                    header("Location: user/dashboard.php?id=$userLoginId");

                    $lname = $row["user_lname"];
                    $fname = $row["user_fname"];
                
                    // Create a session for the logged-in user
                    $_SESSION["userloggedin"] = true;
                    $_SESSION["userid"] = $userLoginId;
                    $_SESSION["userfname"] = $fname;
                    $_SESSION["userlname"] = $lname;

                    $userId = $row["user_id"];
                    $description = "Logged in";
                    $logSql = "INSERT INTO login_logs (user_id, notif_description) VALUES ('$userLoginId', '$description')";
                    $conn->query($logSql); // Assuming $conn is your database connection

                    //Reset login attempts
                    $loginAttempts = 0;
                    $_SESSION['loginAttempts'] = $loginAttempts;
                }
            } else {
                // Login failed, show an error message
                $loginAttempts++;
                $_SESSION['loginAttempts'] = $loginAttempts;

                $error = "Invalid Login Credentials.";
                $error2 = "Remaining attempts: $remainingAttempts";

                if ($loginAttempts >= 6) {
                    // Maximum login attempts exceeded, take action
                    $error = "Maximum login attempts reached.";
                    $error2 = "Please try again later";

                    $_SESSION['lastLoginAttempt'] = time();
                    // You can also implement a cooldown period here

                } else {
                    // Invalid login credentials
                    $error = "Invalid login credentials";
                }
            }
        } else {
            // Login failed, show an error message
            $loginAttempts++;
            $_SESSION['loginAttempts'] = $loginAttempts;

            $error = "Invalid login credentials.";
            $error2 = "Remaining attempts: $remainingAttempts";

            if ($loginAttempts >= 6) {
                // Maximum login attempts exceeded, take action
                $error = "Maximum login attempts reached.";
                $error2 = "Please try again later";

                $_SESSION['lastLoginAttempt'] = time();
                // You can also implement a cooldown period here

            } else {
                // Invalid login credentials
                $error = "Invalid login credentials";
            }
        }
    } else {

        $loginAttempts++;
        $_SESSION['loginAttempts'] = $loginAttempts;

        $error = "Invalid login credentials.";
        $error2 = "Remaining attempts: $remainingAttempts";

        if ($loginAttempts >= 6) {
            // Maximum login attempts exceeded, take action
            $error = "Maximum login attempts reached.";
            $error2 = "Please try again later";

            $_SESSION['lastLoginAttempt'] = time();
            // You can also implement a cooldown period here

        } else {
            // Invalid login credentials
            $error = "Invalid login credentials";
        }
    }
}

include_once 'db_connection.php';

$sqlreg = "SELECT user_id FROM user_tbl ORDER BY user_id DESC LIMIT 1";
$resultreg = $conn->query($sqlreg);

if ($resultreg->num_rows > 0) {
    // output data of each row
    while ($row = $resultreg->fetch_assoc()) {
        $last_id = $row["user_id"];
    }
} else {
    echo "0 results";
}

$next_id = $last_id + 1;


include_once 'db_connection.php';

$sqlsubmit = "SELECT user_id FROM user_tbl ORDER BY user_id DESC LIMIT 1";
$resultsubmit = $conn->query($sqlsubmit);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-for-approval-btn'])) {
    // Retrieve the form inputs
    $user_id = $_POST['user-id'];
    $user_pass = $_POST['user-pass'];
    $user_pass_confirm = $_POST['user-pass-confirm'];
    //name
    $user_firstName = $_POST['user-fname'];
    $user_middleName = $_POST['user-mname'];
    $user_lastName = $_POST['user-lname'];
    $user_suffixName = $_POST['user-sfxname'];
    //date
    $user_birthdate = $_POST['user-birthdate'];
    //address
    $user_address_room = $_POST['user-address-room'];
    $user_address_house = $_POST['user-address-house'];
    $user_address_street = $_POST['user-address-street'];
    $user_address_subd = $_POST['user-address-subd'];
    $user_address_brgy = $_POST['user-address-brgy'];
    //additional information


    //Account Picture
    $file = $_FILES["user-picture-profile"];
    // File details
    $fileName = "$user_firstName $user_lastName - " . $file["name"];
    $fileTmpName = $file["tmp_name"];
    $fileSize = $file["size"];
    $fileError = $file["error"];
    // Move the uploaded file to a desired location on the server
    $destination = "../admin/account_pictures/" . $fileName;
    move_uploaded_file($fileTmpName, $destination);

    //Preferred Document
    $document = $_FILES["user-pdocument"];
    // File details
    $documentName = "$user_firstName $user_lastName - " . $document["name"];
    $documentTmpName = $document["tmp_name"];
    $documentSize = $document["size"];
    $documentError = $document["error"];
    // Move the uploaded file to a desired location on the server
    $pdocument = "../admin/preferred_document/" . $documentName;
    move_uploaded_file($documentTmpName, $pdocument);

    //mobile number
    $user_mnumber = $_POST['user-mnumber'];

    $user_email = $_POST['user-email'];
    $user_pdocument_type = $_POST['user-pdocument-type'];

    $user_position = $_POST['user-position'];
    $user_spouseName = $_POST['user-spouse-name'];
    $share_investment = $_POST['share-investment'];



    // Prepare the SQL statement to insert the data into app_pending_tbl
    $sql = "INSERT INTO user_tbl (user_id, user_pass, user_pass_confirm, 
    user_fname, user_mname, user_lname, user_sfxname,
    user_birthdate, user_address_room, user_address_house, user_address_street, user_address_subd, user_address_brgy,   
    account_pictures,
    user_mnumber, user_email, user_pdocument_type, pdocument, position, spouse_name, share_investment) 
    VALUES ('$user_id','$user_pass','$user_pass_confirm', '$user_firstName', 
    '$user_middleName', '$user_lastName', '$user_suffixName',
    '$user_birthdate', '$user_address_room', '$user_address_house', '$user_address_street', '$user_address_subd', '$user_address_brgy',
    '$destination',
    '$user_mnumber', '$user_email', '$user_pdocument_type', '$pdocument', '$user_position', '$user_spouseName', '$share_investment')";


    // Execute the SQL statement
    if ($conn->query($sqlsubmit) === TRUE) {
        // Data inserted successfully

    }
}

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>CRG-MPC Login</title>
    <meta name="description" content="Login">
    <link rel="icon" type="image/png" sizes="396x396" href="assets/img/logo/logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/bs-theme-overrides.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
    <nav class="navbar navbar-expand-md bg-body shadow py-3" id="navbar-main">
        <div class="container"><a class="navbar-brand d-flex align-items-right" href="index.php"><img id="login-img" src="assets/img/logo/logo.png"><span><strong>CRG-MPC</strong></span></a>

            <div class="ml-auto"> <!-- Added ml-auto class -->
                <button class="btn btn-primary btn-icon-split" type="button" data-bs-target="#login-register-modal" data-bs-toggle="modal">
                    <span class="text-white-50 icon"><i class="fas fa-question"></i></span>
                    <span class="text-white text">FAQ</span>
                </button>

            </div>

            <div class="modal fade modal-xl" role="dialog" tabindex="-1" id="login-register-modal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><strong>FAQ</strong></h5><button class="btn-close btn-close-white" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">

                            <p class="p-text"><strong>How to Register?</strong><br>Step 1: Visit the Calamba Rice Growers Multi-Purpose Cooperative establishment.<br><br>Step 2: Apply for a User Account with the following qualifications:<br>- Must be a farmer or owner of a land.<br>- Must have positive credit score.<br>- Must agree to the provided terms and conditions.<br><br>Step 3: Once accepted, you will be issued with your "User ID" where it contains your account number used for login.<br><br>Step 4: Input the User ID and assigned Password to the Login Page to access your account.</p>


                            <p class="p-text"><strong>Forgot Password?</strong><br>Option 1: Use the Forgot Password Link.<br>- Must have a valid authenticated account email.<br>- After requesting for a forgot password, check email for new password.<br>- New password will now be required for login.<br><br>Option 2: Visit the Calamba Rice Growers Multi-Purpose Cooperative establishment.<br>- Must provide the admin with required credentials for confirmation of identity.</p>
                        </div>
                        <div class="modal-footer"><button class="btn btn-danger btn-icon-split" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-times-circle"></i></span><span class="text-white text">Close</span></button></div>
                    </div>
                </div>
            </div>
            <div class="modal fade modal-xl" role="dialog" tabindex="-1" id="user-online-register-modal">
                <div class="modal-dialog" role="document">
                    <form action="">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><strong>Online Registration</strong></h5><button class="btn-close btn-close-white" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">

                                <div>
                                    <p><strong>User Identification</strong><br><span style="color: rgb(231, 74, 59);">User ID is auto-generated</span></p>
                                    <div class="form-floating mb-3">
                                        <input class="form-control form-control" type="text" id="user-id" placeholder=" " disabled="" required="" autocomplete="off" name="user-id" value="<?php echo $next_id; ?>">
                                        <input type="hidden" name="user-id" value="<?php echo $next_id; ?>"><label class="form-label" for="floatingInput">User ID</label>
                                    </div>
                                    <p><strong>Preferred Password</strong><br><span style="color: rgb(231, 74, 59);">8-20 alphanumeric characters/different from user ID/one lowercase/one uppercase/one digit/Password and Confirm Password must be the SAME.</span></p>
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
                                    <div class="form-floating mb-3"><input class="form-control form-control" type="number" id="user-investment" placeholder=" " name="share-investment" onchange="amountLimit()"><label class="form-label" for="floatingInput">Share on Investment</label></div>
                                    <script>
                                        function amountLimit() {
                                            var amountInput = document.getElementById("user-investment");
                                            var amountValue = parseInt(amountInput.value);

                                            if (amountValue < 5000) {
                                                amountInput.value = 5000;
                                            }
                                            if (amountValue > 500000) {
                                                amountInput.value = 500000;
                                            }
                                        }
                                    </script>


                                    <div class="form-floating mb-3"><input class="form-control form-control" type="text" id="user-spouse-name" placeholder=" " name="user-spouse-name">
                                        <label class="form-label" for="floatingInput">Spouse Name</label>
                                    </div>
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
                            <div class="modal-footer">

                                <button class="btn btn-danger btn-icon-split" type="button" data-bs-dismiss="modal"><span class="text-white-50 icon"><i class="fas fa-times-circle"></i></span><span class="text-white text">Close</span></button>
                                <button class="btn btn-primary btn-icon-split m-1" id="submit-for-approval-btn" type="submit" data-bs-toggle="modal" name="submit-for-approval-btn" disabled><span class="text-white-50 icon"><i class="fas fa-user-plus"></i></span><span class="text-white text">Register User</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </nav>
    <section class="d-lg-flex position-relative py-5 py-xl-5">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-9 col-lg-7 col-xl-6">
                    <div class="card mb-5">
                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="my-3"><img id="login-logo" src="assets/img/logo/logo.png"></div>
                            <h4 class="mb-3">Calamba Rice Growers Multi-Purpose Cooperative Loan System</h4>
                            <form class="text-center" method="post" action="index.php">
                                <div class="form-floating mb-3">
                                    <input class="form-control" type="text" id="user-login-id" required="" placeholder=" " name="user-login-id" <?php if ($remainingAttempts <= 0) echo 'disabled'; ?>>
                                    <label class="form-label" for="floatingInput">User ID</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" type="password" id="user-login-pass" placeholder="  " required="" autocomplete="on" name="user-login-pass" <?php if ($remainingAttempts <= 0) echo 'disabled'; ?>>
                                    <label class="form-label" for="floatingInput">Password</label>
                                </div>
                                <div class="g-recaptcha mb-3" data-sitekey="6Lf_LMMoAAAAAB-F85DU2HjiOhtA16rCC_zUcec3"></div>
                                <p class="error-message"><?php echo $error; ?></p>
                                <p class="error-message"><?php echo $error2; ?></p>

                                <div class="my-3">
                                    <button class="btn btn-primary btn-icon-split <?php if ($remainingAttempts <= 0) echo 'disabled'; ?>" name="submit" id="login-btn" type="submit">
                                        <span class="text-white-50 icon"><i class="fas fa-check-circle"></i></span>
                                        <span class="text-white text">Login</span>
                                    </button>
                                </div>
                                <a class="card-link fw-bold" data-bs-target="#login-forgotpass-modal" data-bs-toggle="modal">Forgot your Password?</a>
                            </form>
                            <!--modal for forgot password-->
                            <div class="modal fade modal-md" role="dialog" tabindex="-1" id="login-forgotpass-modal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><strong>Find your account</strong></h5>
                                            <button class="btn-close btn-close-white" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="forget p-text">Please enter your email to reset your password.</p>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" type="text" id="user-reset-pass-email" required="" autocomplete="on" placeholder=" " name="user-reset-pass-email">
                                                <label class="form-label" for="floatingInput">Email</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger btn-icon-split" type="button" data-bs-dismiss="modal">
                                                <span class="text-white-50 icon"><i class="fas fa-times-circle"></i></span>
                                                <span class="text-white text">Close</span>
                                            </button>
                                            <button class="btn btn-primary btn-icon-split" type="button" id="user-reset-pass-btn" name="user-reset-pass-btn">
                                                <span class="text-white-50 icon"><i class="fas fa-redo"></i></span>
                                                <span class="text-white text">Reset</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="bg-white sticky-footer">
        <div class="container my-auto">
            <div class="text-center my-auto copyright"><span>Copyright © City College of Calamba 2023.</span></div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#user-reset-pass-btn').click(function() {
                var email = $('#user-reset-pass-email').val(); // Get the entered email

                // Perform AJAX POST request to trigger password reset
                $.ajax({
                    url: 'user-reset-password.php', // Replace with your backend PHP file
                    method: 'POST',
                    data: {
                        'email': email
                    }, // Send the email data to the backend
                    success: function(response) {
                        // Handle the response from the backend (e.g., display success message)
                        alert(response);
                    },
                    error: function(xhr, status, error) {
                        // Handle errors, if any
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
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

                    if (namecond == "Yes" && userRegisterCheckbox.checked) {
                        userRegisterRegister.disabled = false;
                        console.log("pwede");
                    } else {
                        userRegisterRegister.disabled = true;
                        console.log("hindi pwede");
                    }
                }
            };
            xhr.send('fname=' + encodeURIComponent(firstName) + '&mname=' + encodeURIComponent(middleName) + '&lname=' + encodeURIComponent(lastName));
        }
        //end of code

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
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/features/disable-for-approval.js"></script>
    <script src="assets/js/features/scripts.js"></script>
    <script src="assets/js/index.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>