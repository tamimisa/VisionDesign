<?php
    include "session.php";
    
    if (isset($_SESSION['loggedIn'])) {
        if (!$_SESSION['loggedIn'] || $_SESSION['type'] != "Client") {
            header('location: Login.php');
        }
    } else {
        header('location: Login.php');
    }

    // Create connection
    $conn = new mysqli("localhost", "root", "root", "visiondesign");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $designerId = mysqli_real_escape_string($conn, $_POST['designerid']);
        $roomType = mysqli_real_escape_string($conn, $_POST['roomType']);
        $roomWidth = mysqli_real_escape_string($conn, $_POST['roomWidth']);
        $roomLength = mysqli_real_escape_string($conn, $_POST['roomLength']);
        $designCategory = mysqli_real_escape_string($conn, $_POST['designCategory']);
        $colorPreferences = mysqli_real_escape_string($conn, $_POST['colorPreferences']);
        $requestStatus = 81;
        $requestDate = date('Y-m-d');
        $clientId = $_SESSION['id'];
        $sqlD = "SELECT * FROM designer WHERE id=?";

        $stmtD = $conn->prepare($sqlD);
        $stmtD->bind_param("i", $designerId);
        $stmtD->execute();
        $resultD = $stmtD->get_result();
        if ($resultD->num_rows > 0) {
            $sql = "INSERT INTO designconsultationrequest(clientID, designerID, roomTypeID, designCategoryID, roomWidth, roomLength, colorPreferences, date, statusID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiiddssi", $clientId, $designerId, $roomType, $designCategory, $roomWidth, $roomLength, $colorPreferences, $requestDate, $requestStatus);

            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
            } else {
                header('location: Clienthomepage.php');
            }
        } else {
            $_SESSION['error'] = "Please select a correct designer id.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Request Design Consultation</title>
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
        <form action="" method="post">
            <?php
            if (isset($_SESSION['error'])) {
                ?>
                <span class="error"><?= $_SESSION['error'] ?></span> 
                <?php
                unset($_SESSION['error']);
            }
            ?>
            <input type="hidden" name="designerid" value="<?= $_GET["designerid"] ?>">
            <fieldset>
                <h1>Request Design Consultation </h1>
                <hr>
                <label for="roomType">Room Type:</label>
                <select id="roomType" name="roomType" required>
                    <option value="" disabled selected>Select Room Type</option>
                    <?php
                    $sqlRT = "SELECT id, type FROM roomtype ORDER BY type ASC";

                    $stmtRT = $conn->prepare($sqlRT);
                    $stmtRT->execute();
                    $resultRT = $stmtRT->get_result();

                    if ($resultRT->num_rows > 0) {
                        while ($rowRT = $resultRT->fetch_assoc()) {
                            echo '<option value="' . $rowRT['id'] . '">' . $rowRT['type'] . '</option>';
                        }
                    }
                    ?>
                </select>

                <label for="roomWidth">Room Width (in meters):</label>
                <input type="number" id="roomWidth" name="roomWidth" required>

                <label for="roomLength">Room Length (in meters):</label>
                <input type="number" id="roomLength" name="roomLength" required>

                <label for="designCategory">Design Category:</label>
                <select id="designCategory" name="designCategory" required>
                    <option value="" disabled selected>Select Design Category</option>
                    <?php
                    $sqlDC = "SELECT id, category FROM designcategory ORDER BY category ASC";

                    $stmtDC = $conn->prepare($sqlDC);
                    $stmtDC->execute();
                    $resultDC = $stmtDC->get_result();

                    if ($resultDC->num_rows > 0) {
                        while ($rowDC = $resultDC->fetch_assoc()) {
                            echo '<option value="' . $rowDC['id'] . '">' . $rowDC['category'] . '</option>';
                        }
                    }
                    ?>
                </select>

                <label for="colorPreferences">Color Preferences:</label>
                <input type="text" id="colorPreferences" name="colorPreferences">

                <button type="submit">Submit</button>
            </fieldset>
        </form>

    </body>
</html>