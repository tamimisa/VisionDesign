<?php include "session.php"; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="PAstyle.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <title>AddProject</title>
        <style>
            body {

                margin: 0;
                background-color: #8aa4a7;
                color: #0b0b0a;
                font-family: 'Times New Roman', Times, serif;
            }

            .content-container {
                width: 70%;
                max-width: 600px;
                background-color: rgb(246,245,245);
                margin: 10px auto;

            }


            .PA {
                background-color: #f5f5f5;
                border-radius: 8px;
                padding: 20px;

            }

            .PA h2 {
                text-align: center;
            }

            #img1{
                width: 7em;
                height: 10em;
                padding-left: 3%;

            }

            img{
                padding-top: 10px;
                width: 1.5em;
                height: 1.5em;
            }

            .PA fieldset {
                border: 1px solid #8aa4a7;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);

            }

            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            input,
            select,
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

        </style>
    </head>
    <body>
        <img src="uploads/LogoB.png" alt="" id="img1">
        <div class="content-container">
            <form action="projectAddition.php" class="PA" method="post" enctype="multipart/form-data">
                <fieldset>
                    <a href="Designerhomepage.php"> <img src="uploads/WEb_delete.png" alt=""> </a>
                    <h2>  Add a new project </h2>
                    <label for="Pname">Project Name:</label>
                    <input type="text" name="ProjectName" id="Pname">
                    <label for="image">Project images:</label>
                    <input type="file" name="image" id="image">
                    <label for="Category">Choose a Design Category:</label>
                    <select name="DesignCategory" id="Category">
                        <option value="Modern">Modern</option>
                        <option value="Country">Country</option>
                        <option value="Coastal">Coastal</option>
                        <option value="Minimalist">Minimalist</option>
                        <option value="Bohemian">Bohemian</option>
                    </select>
                    <label for="Description">Description:</label>
                    <br>
                    <textarea name="Descrip" id="Description" cols="5" rows="5"></textarea>
                    <input type="submit" value="Submit">
                </fieldset>
            </form>
        </div>
    </body>
</html>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "visiondesign";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
        $projectName = $_POST["ProjectName"];
        $description = $_POST["Descrip"];
        $categoryName = $_POST["DesignCategory"];

        // Check if category exists
        $categoryQuery = "SELECT id FROM designcategory WHERE category = '$categoryName'";
        $categoryResult = mysqli_query($conn, $categoryQuery);

        if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
            $categoryRow = mysqli_fetch_assoc($categoryResult);
            $designCategoryID = $categoryRow['id'];

            // Proceed with file upload
            $filename = $_FILES["image"]["name"];
            $fileTmpName = $_FILES["image"]["tmp_name"];
            $fileType = $_FILES["image"]["type"];
            $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];

            if (in_array($fileType, $allowedTypes)) {
                $newFileName = uniqid('', true) . '.' . pathinfo($filename, PATHINFO_EXTENSION);
                $uploadPath = "uploads/" . $newFileName;

                if (move_uploaded_file($fileTmpName, $uploadPath)) {


                    // Get designer ID from session
                    $designerID = $_SESSION['id'];

                    $insertQuery = "INSERT INTO designportoflioproject (designerID, projectName, projectImgFileName, description, designCategoryID) 
                    VALUES ('$designerID', '$projectName', '$newFileName', '$description', '$designCategoryID')";

                    if (mysqli_query($conn, $insertQuery)) {
                        header("Location: Designerhomepage.php?upload=success");
                        exit;
                    } else {
                        echo "Error executing insert statement: " . mysqli_error($conn);
                    }
                } else {
                    echo "There was an error uploading your file.";
                }
            } else {
                echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            echo "No category found with the name " . $categoryName;
        }
    }

    mysqli_close($conn);
?>









