<?php
    include "session.php";
    if (isset($_SESSION['loggedIn'])) {
        if (!$_SESSION['loggedIn'] || $_SESSION['type'] != "Designer") {
            header('location: Login.php');
        }
    } else {
        header('location: Login.php');
    }

    $conn = new mysqli("localhost", "root", "root", "visiondesign");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $request = "";

    if (isset($_GET["requestid"])) {
        $requestid = $_GET["requestid"];
        $sql = "SELECT client.firstName, client.lastName, roomtype.type, roomWidth, roomLength, colorPreferences, date  
            FROM designconsultationrequest 
            INNER JOIN client ON designconsultationrequest.clientID=client.id 
            INNER JOIN roomtype ON designconsultationrequest.roomTypeID=roomtype.id 
            WHERE designconsultationrequest.id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $requestid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $request .= "Client: " . $row['firstName'] . " " . $row['lastName'] . "\n";
            $request .= "Room: " . $row['type'] . "\n";
            $request .= "Dimensions: " . $row['roomLength'] . "m x " . $row['roomWidth'] . "m\n";
            $request .= "Color Preferences: " . $row['colorPreferences'] . "\n";
            $request .= "Date: " . $row['date'];
        }
    } else {
        $_SESSION['error'] = "Please select a request id.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $requestid = mysqli_real_escape_string($conn, $_POST['requestid']);
        $consultation = mysqli_real_escape_string($conn, $_POST['consultation']);

        $sqlD = "SELECT * FROM designconsultationrequest WHERE id=?";

        $stmtD = $conn->prepare($sqlD);
        $stmtD->bind_param("i", $requestid);
        $stmtD->execute();
        $resultD = $stmtD->get_result();

        if ($resultD->num_rows > 0) {
            if (!empty($_FILES["image"]["name"])) {
                $fileName = basename($_FILES["image"]["name"]);
                $targetDir = "uploads/";
                $newFileName = md5(uniqid(mt_rand(), true)) . "_" . $fileName;
                $targetFilePath = $targetDir . $newFileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                // Allow certain file formats 
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                if (in_array($fileType, $allowTypes)) {
                    // Upload file to server 
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                        $sql = "INSERT INTO designconsultation(requestID, consultation, consultationImgFileName) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("iss", $requestid, $consultation, $newFileName);

                        if (!$stmt->execute()) {
                            echo "Error: " . $stmt->error;
                        } else {
                            header('location: Designerhomepage.php');
                        }
                    } else {
                        $_SESSION['error'] = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $_SESSION['error'] = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
                }
            } else {
                $_SESSION['error'] = 'Please select a file to upload.';
            }
        } else {
            $_SESSION['error'] = "Request id is wrong.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Design Consultation Page</title>
        <style>
            body {
                justify-content: center;
                text-align: center;
                height: 100vh;
                background-color: #8aa4a7;
                color: #0b0b0a;
                font-family: 'Times New Roman', Times, serif;
            }

            #consultation-form {
                max-width: 600px;
                margin: auto;
                text-align: center;
            }

            form {
                display: inline-block;
                text-align: left;
            }

            fieldset {
                border: 2px solid #333;
                padding: 20px;
                border-radius: 8px;
                background: rgb(246, 245, 245);
                max-width: 600px;

            }

            legend {
                font-size: 1.5em;
                font-weight: bold;
                margin-bottom: 10px;
            }

            select, input,
            textarea {
                width: 100%;
                padding: 8px;
                margin-bottom: 10px;
                box-sizing: border-box;
            }

            button {
                width: 100%;
                padding: 10px;
                background-color: #758d90;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 1.1em;
            }

            button:hover {
                background-color: #647a7d;
            }

            .error {
                color: #ea0b0b;
                margin: 20px;
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <form action="" method="post" enctype="multipart/form-data">
            <?php
            if (isset($_SESSION['error'])) {
                ?>
                <span class="error"><?= $_SESSION['error'] ?></span> 
                <?php
                unset($_SESSION['error']);
            }
            ?>
            <input type="hidden" name="requestid" value="<?= $_GET["requestid"] ?>">
            <fieldset>
                <h1>Design Consultation</h1>
                <hr>
                <label for="client-request-info"><h2>Request Information</h2></label>
                <textarea id="client-request-info" name="client-request-info" rows="5" readonly>
                    <?php echo $request; ?>
                </textarea>
                <label for="consultation"><h2>Consultation:</h2></label>
                <textarea id="consultation" name="consultation" rows="4" cols="50" required></textarea><br>

                <label for="image"><h2>Upload Image:</h2></label>
                <input type="file" id="image" name="image"><br><br>

                <button type="submit" value="Send"> Send </button>
            </fieldset>
        </form>
    </body>
</html>