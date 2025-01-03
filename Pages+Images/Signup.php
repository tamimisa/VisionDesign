<?php
    //Start the session
    session_start();
    
    // Error reporting
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('display_errors', '1');

    // Create connection
    $conn = new mysqli("localhost", "root", "root", "visiondesign");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Sanitize input data
        $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
        $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        
        $DfirstName = mysqli_real_escape_string($conn, $_POST['DfirstName']);
        $DlastName = mysqli_real_escape_string($conn, $_POST['DlastName']);
        $Demail = mysqli_real_escape_string($conn, $_POST['Demail']);
        $Dpassword = mysqli_real_escape_string($conn, $_POST['Dpassword']);
        $DhashedPassword = password_hash($Dpassword, PASSWORD_DEFAULT);
        
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $type = isset($_POST['UserType']) ? $_POST['UserType'] : '';

        // Check if email address already exists
        $sql = "SELECT * FROM client WHERE emailAddress = '$email'";
        $result = $conn->query($sql);

        // If the email address exists, redirect with message
        if ($result->num_rows > 0) {
            header('Location: Signup.php?message=Email already exists. Please use a different email');
            exit();
        }

        $sqlEmail = "SELECT COUNT(*) AS countEmail FROM designer WHERE emailAddress = '$Demail'";
        $result = $conn->query($sqlEmail);
        $row = mysqli_fetch_assoc($result);

        // If the email address exists, redirect with message
    if ($row["countEmail"] > 0) {
            header('Location: Signup.php?message=Email already exists. Please use a different email');
            exit();
        }

        // Insert new user into the database
        if ($type === 'Designer') {
            $brandName = mysqli_real_escape_string($conn, $_POST['BrandName']);
            $logoFileName = $_FILES["logo"]["name"];
            $logoTempFilePath = $_FILES["logo"]["tmp_name"];
            $targetDirectory = 'uploads/';
            
            // Generate a unique filename using the hash of the logo file contents
            $hashedLogoFileName = hash_file('sha256', $logoTempFilePath) . '_' . time() . '_' . $logoFileName;

            
            // Insert designer data into the database
            $sql = "INSERT INTO designer (firstName, lastName, emailAddress, password, brandName, logoImgFileName) VALUES ('$DfirstName', '$DlastName', '$Demail', '$DhashedPassword', '$brandName', '$hashedLogoFileName')";
            $result = mysqli_query($conn, $sql);
            
            move_uploaded_file($logoTempFilePath, $targetDirectory . $hashedLogoFileName);

            if ($result) {
                $designerID = $conn->insert_id;

                $specialities = isset($_POST['specialities']) ? $_POST['specialities'] : array();
                foreach ($specialities as $Category) {
                    $s = "SELECT * FROM designcategory WHERE category = '$Category'";
                    $re = mysqli_query($conn, $s);
                    $row = $re->fetch_assoc();
                    $designCategoryID = $row['id'];
                    $sql = "INSERT INTO designerspeciality (designerID, designCategoryID) VALUES ('$designerID', '$designCategoryID')";
                    $conn->query($sql);
                }

                $_SESSION['id'] = $designerID;
                $_SESSION['type'] = $type;
                header('Location: Designerhomepage.php');
                exit();
            } else {
                header('Location: Signup.php?message=Error signing up. Please try again.');
                exit();
            }
        } else {
            // Insert client data into the database
            $sql = "INSERT INTO client (firstName, lastName, emailAddress, password) VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
            $result = $conn->query($sql);

            if ($result) {
                $_SESSION['id'] = $conn->insert_id;
                $_SESSION['type'] = $type;
                header('Location: Clienthomepage.php');
                exit();
            } else {
                header('Location: Signup.php?message=Error signing up. Please try again.');
                exit();
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                background-color: #8aa4a7;
            }

            .center {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 400px;
                height: 300px;
                background-color: rgb(246, 245, 245);
                border-radius: 10px;
                padding: 1%;
            }

            .center h2 {
                text-align: center;
                padding: 30px 0 20px 0;
                border-bottom: 1px solid silver;
                background-color: rgb(246, 245, 245);
                font-size: 170%;
                padding-right: 5%;
            }

            .center form.F, .DForm, .CForm {
                padding: 20px 40px;
                box-sizing: border-box;
                background-color: rgb(246, 245, 245);
            }


            label {
                background-color: rgb(246, 245, 245);
            }


            .option-type {
                background-color: rgb(246, 245, 245);
            }

            p {
                padding-top: 10%;
                font-weight: bold;
                font-size: 120%;
                background-color: rgb(246, 245, 245);
            }

            .lableO {
                background-color: rgb(246, 245, 245);
            }

            .option {
                margin: 5%;
                margin-bottom: 1%;
            }

            form .txt-filed {
                position: relative;
                border-bottom: 2px solid #adadad;
                margin: 23px 0;
                background-color: rgb(246, 245, 245);
            }

            .txt-filed input {
                width: 100%;
                padding: 0 2px;
                height: 40px;
                font-size: 16px;
                border: none;
                background: none;
                outline: none;
                background-color: rgb(246, 245, 245);
            }

            .txt-filed label {
                position: absolute;
                top: 50%;
                left: 5px;
                color: #adadad;
                transform: translateY(-50%);
                font-size: 16px;
                pointer-events: none;
                background-color: rgb(246, 245, 245);
                transition: 0.5s;
            }

            .txt-filed span::before {
                content: '';
                position: absolute;
                top: 40px;
                left: 0;
                width: 0%;
                height: 2px;
                background: #2691d9;
                transition: 0.5s;
            }

            .txt-filed input:focus ~ label,
            .txt-filed input:valid ~ label {
                top: -5px;
                color: #2691d9;
            }

            .txt-filed input:focus ~ span::before,
            .txt-filed input:valid ~ span::before {
                width: 100%;
            }


            label {
                background-color: rgb(246, 245, 245);
            }

            .option-type {
                background-color: rgb(246, 245, 245);
            }

            .option-type p {
                background-color: rgb(246, 245, 245);
                font-weight: bold;
            }

            .lableO {
                background-color: rgb(246, 245, 245);
            }

            .logoLable {
                background-color: rgb(246, 245, 245);
                font-weight: bold;
                font-size: 120%;
                padding-right: 5%;
            }

            .LogoS {
                display: flex;
            }


            .center .signup {
                margin-top: 4%;
                margin-bottom: 5%;
                width: 50%;
                height: 40px;
                border: 1px solid;
                margin-left: 20%;
                border-radius: 10px;
                background-color: rgb(122, 147, 180);
                text-align: center;
                font-weight: bold;
                font-size: 100%;
                font-family: 'Times New Roman', Times, serif;
                color: white;
                transition: 0.5s;
            }


            .center .signup:hover {
                background-color: rgb(92, 114, 143);
            }

            .center .signup a:hover {
                color:rgb(238, 238, 238);
                background-color: rgb(92, 114, 143);
            }

            .logo {
                background-color: rgb(246, 245, 245);
            }

            .Check {
                margin: 3%;
                margin-top: 5%;
            }

            .LCheck {
                font-size: 110%;
            }
        </style>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const clientForm = document.querySelector(".CForm");
                const designerForm = document.querySelector(".DForm");
                const optionType = document.querySelector(".option-type");
                const submitButton = document.querySelector("#SButton");

                // Form submission
                submitButton.addEventListener("click", function () {
                    const userType = document.querySelector("input[name='UserType']:checked")
                            ? document.querySelector("input[name='UserType']:checked").value
                            : "";

                    // Check if user type is selected
                    if (!userType) {
                        alert("Please select a user type before submitting.");
                        return;
                    }

                    // Check fields based on user type
                    if (userType === "Client") {
                        const CFN = document.getElementById("CfirstName").value;
                        const CLN = document.getElementById("ClastName").value;
                        const CEmail = document.getElementById("Cemail").value;
                        const Cpass = document.getElementById("Cpassword").value;

                        if (CFN === "" || CLN === "" || CEmail === "" || Cpass === "") {
                            alert("Please fill in all fields.");
                            return;
                        }
                    } else if (userType === "Designer") {
                        const DFN = document.getElementById("DfirstName").value;
                        const DLN = document.getElementById("DlastName").value;
                        const DEmail = document.getElementById("Demail").value;
                        const Dpass = document.getElementById("Dpassword").value;
                        const BrandName = document.getElementById("BrandName").value;
                        const DLogo = document.getElementById("logo").value;

                        if (DFN === "" || DLN === "" || DEmail === "" || Dpass === "" || BrandName === "" || DLogo === "") {
                            alert("Please fill in all fields.");
                            return;
                        }
                    }

                    // If all conditions pass, submit the form
                    document.querySelector(".F").submit();
                });



                // Option-type change
                optionType.addEventListener("change", function () {
                    const userType = document.querySelector("input[name='UserType']:checked")
                            ? document.querySelector("input[name='UserType']:checked").value
                            : "";
                    if (userType === "Client") {
                        clientForm.style.display = "block";
                        designerForm.style.display = "none";
                        document.getElementById("center").style.height = "610px";
                    } else if (userType === "Designer") {
                        clientForm.style.display = "none";
                        designerForm.style.display = "block";
                        document.getElementById("center").style.height = "840px";
                    }
                });
            });
            
            // Function to display message as an alert
            function displayMessage() {
                if (<?php echo isset($_GET['message']) ? 'true' : 'false'; ?>) {
                    alert('<?php echo isset($_GET['message']) ? htmlspecialchars($_GET['message']) : ''; ?>');
                }
            }
            // Call the function when the page loads
            window.onload = displayMessage;
        </script>
    </head>
    <body>
        <div id="center" class="center">
            <form class="F"  method="post" action="Signup.php?type=Designer" enctype="multipart/form-data">
                <h2>SIGN-UP</h2>
                <div class="option-type">
                    <p>User Type: </p>
                    <input class="option" type="radio" id="Client" name="UserType" value="Client">
                    <label class="lableO">Client</label>
                    <input class="option" type="radio" id="Designer" name="UserType" value="Designer">
                    <label class="lableO">Interior Designer</label>
                </div>
                <div class="DForm" style="display: none;">
                    <div class="txt-filed">
                        <input type="text" id="DfirstName" name="DfirstName" required>
                        <span></span>
                        <label>First Name</label> 
                    </div>
                    <div class="txt-filed">
                        <input type="text" id="DlastName" name="DlastName" required>
                        <span></span>
                        <label>Last Name</label> 
                    </div>
                    <div class="txt-filed">
                        <input type="email" id="Demail" name="Demail" required>
                        <span></span>
                        <label>Email</label> 
                    </div>
                    <div class="txt-filed">
                        <input type="password" id="Dpassword" name="Dpassword" required>
                        <span></span>
                        <label>Password</label> 
                    </div>
                    <div class="txt-filed">
                        <input type="text" id="BrandName" name="BrandName" required>
                        <span></span>
                        <label>Brand Name</label> 
                    </div>
                    <div class="LogoS">
                        <lable class="logoLable">Logo:</lable>
                        <input type="file" id="logo" class="logo" name="logo">
                    </div>
                    <p>Design Categories:</p>
                    <input class="Check" type="checkbox" id="Modern" name="specialities[]" value="Modern" required>
                    <label class="LCheck" >Modern</label>
                    <input class="Check" type="checkbox" id="Country" name="specialities[]" value="Country" required>
                    <label class="LCheck" >Country</label>
                    <br>
                    <input class="Check" type="checkbox" id="Coastal" name="specialities[]" value="Coastal" required>
                    <label class="LCheck" >Coastal</label>
                    <input class="Check" type="checkbox" id="Bohemian" name="specialities[]" value="Bohemian" required>
                    <label class="LCheck" >Bohemian</label>
                    <input class="Check" type="checkbox" id="Minimalist" name="specialities[]" value="Minimalist" required>
                    <label class="LCheck" >Minimalist</label>

                </div>
                <div class="CForm" style="display: none;">
                    <div class="txt-filed">
                        <input type="text" id="CfirstName" name="firstName" required>
                        <span></span>
                        <label>First Name</label> 
                    </div>
                    <div class="txt-filed">
                        <input type="text" id="ClastName" name="lastName" required>
                        <span></span>
                        <label>Last Name</label> 
                    </div>
                    <div class="txt-filed">
                        <input type="email" id="Cemail" name="email" required>
                        <span></span>
                        <label>Email</label> 
                    </div>
                    <div class="txt-filed">
                        <input type="password" id="Cpassword" name="password" required>
                        <span></span>
                        <label>Password</label> 
                    </div>
                </div>
                <button id="SButton" type="button" class="signup">Sign-Up</button>
            </form>
        </div>
    </body>
</html>
