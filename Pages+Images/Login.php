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
        $email = $_POST['email'];
        $password = $_POST['password'];
        $userType = $_POST['userType'];

        // Prepare statement to prevent SQL injection
        $sql = "";
        if($userType === 'Designer'){
            $sql = "SELECT id, password FROM designer WHERE emailAddress = ?";
        } else {
            $sql = "SELECT id, password FROM client WHERE emailAddress = ?";
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0 ) {
            // User with the provided email exists in the database
            $row = $result->fetch_assoc();
            $storedPassword = $row['password']; 

            // Verify hashed password
            if (password_verify($password, $storedPassword)) {
                // Password is correct
                $_SESSION['id'] = $row['id'];
                $_SESSION['type'] = $userType; 
                $_SESSION['loggedIn'] = true;
                if ($userType === 'Designer') { 
                    header('Location: Designerhomepage.php');
                    exit();
                } else {
                    header('Location: Clienthomepage.php');
                    exit();
                }
            } else {
                // Password is incorrect
                header('Location: login.php?message=Incorrect email address or password');
                $_SESSION['loggedIn'] = false;
                exit();
            }
        } else {
            // User with the provided email does not exist in the database
            header('Location: login.php?message=Incorrect email address or password');
            exit();
        }

        $stmt->close();
        $conn->close();
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
                background-color: #8aa4a7;
            }

            .center {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 400px;
                height: 400px;
                background-color: rgb(246, 245, 245);
                border-radius: 10px;
                padding: 2.5%
            }


            .center h2 {    
                text-align: center;
                padding: 0 0 20px 0;
                border-bottom: 1px solid silver;
                background-color: rgb(246, 245, 245);
                font-size: 170%;
                padding-right: 4%;
            }

            .center form {
                padding: 0 40px;
                box-sizing: border-box;
                background-color: rgb(246, 245, 245);
            }

            form .txt-filed {
                position: relative;
                border-bottom: 2px solid #adadad;
                margin: 30px 0;
                background-color: rgb(246, 245, 245);
            }

            .txt-filed input {
                width: 100%;
                padding: 0 5px;
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
                font-size: 110%;
            }

            .lableO {
                background-color: rgb(246, 245, 245);
                font-size: 105%;
            }


            .center .login {
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

            .center a {
                background-color: rgb(246, 245, 245);
            }

            .center .login:hover {
                background-color: rgb(92, 114, 143);
            }

            .center .login a:hover {
                color:rgb(238, 238, 238);
                background-color: rgb(92, 114, 143);
            }

            .option {
                margin: 5%;
            }
        </style>
    </head>
    <body>
        <div class="center">
            <h2>LOG-IN</h2>
            <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="txt-filed">
                    <input type="email" id="email" name="email" required>
                    <span></span>
                    <label>Email</label> 
                </div>
                <div class="txt-filed">
                    <input type="password" id="password" name="password" required>
                    <span></span>
                    <label>Password</label> 
                </div>
                
                <?php
                if(isset($_GET['message'])) {
                    echo '<p style="color:red; background-color: rgb(246, 245, 245);">' . $_GET['message'] . '</p>';
                }
                ?>
                
                <div class="option-type">
                    <p>User Type: </p>
                    <input class="option" type="radio" id="Client" name="userType" value="Client" required>
                    <label class="lableO">Client</label> 
                    <input class="option" type="radio" id="Designer" name="userType" value="Designer" required>
                    <label class="lableO">Interior Designer</label> 
                </div>
                <button type="button" class="login" onclick="login()">Log-in</button>
            </form>
        </div>

        <script>      
              function login() {
                var email = document.getElementById("email").value;
                var password = document.getElementById("password").value;
                var userType = document.querySelector("input[name='userType']:checked");

                if(email==""){
                    alert("Please enter your email");
                    return;
                }

                if(password==""){
                    alert("Please enter your password");
                    return;
                }

                if (!userType) {
                    alert("Please select a user type");
                    return;
                }

                document.getElementById("loginForm").submit();
            }
        </script>
    </body>
</html>
