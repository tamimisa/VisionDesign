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
                        padding: 1%;
                    }

                    img.logo {
                        width: 35%;
                        height: 30%;
                    }

                    .center h2 {
                        text-align: center;
                        padding: 0 0 20px 0;
                        border-bottom: 1px solid silver;
                        background-color: rgb(246, 245, 245);
                        font-size: 170%;
                    }

                    .center p {
                        text-align: center;
                        padding: 0 0 20px 0;
                        background-color: rgb(246, 245, 245);
                    }

                    .center .login {
                        margin-top: 15%;
                        margin-bottom: 5%;
                        width: 60%;
                        height: 50px;
                        border: 1px solid;
                        margin-left: 18%;
                        border-radius: 10px;
                        background-color: rgb(122, 147, 180);
                        text-align: center;
                        font-weight: bold;
                        font-size: 145%;
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

                    .center p a {
                        background-color: rgb(246, 245, 245);
                    }
		</style>
    </head>
	<body>
        <main>
            <div class="center">
                <img class="logo" src="uploads/logo.png" alt="LOGO">
                <h2>VISION DESIGN</h2>
                <a href="Login.php"><button class="login">Log-in</button></a>
                <p>New User? <a href="Signup.php">Sign-up</a></p>
            </div>
        </main>
    </body>
</html>