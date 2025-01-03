<?php
    include "session.php";

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "visiondesign";
    $connection = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }


    if (!isset($_GET['projectId'])) {

        header("Location: Designerhomepage.php");
        exit();
    }

    $projectId = $_GET['projectId'];

    $stmt = $connection->prepare("SELECT * FROM designportoflioproject WHERE id = ?");
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {

        header("Location: Designerhomepage.php");
        exit();
    }


    $project = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $projectName = $_POST['ProjectName'];
        $description = $_POST['Descrip'];
        $categoryName = $_POST['DesignCategory'];

        $categoryQuery = "SELECT id FROM DesignCategory WHERE category = '$categoryName'";
        $categoryResult = mysqli_query($connection, $categoryQuery);

        if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
            $categoryRow = mysqli_fetch_assoc($categoryResult);
            $designCategoryID = $categoryRow['id'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

                $targetDir = "uploads/";
                $targetFile = $targetDir . basename($_FILES["image"]["name"]);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($imageFileType, $allowedTypes)) {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                } else {

                    $newFileName = uniqid('', true) . '.' . $imageFileType;
                    $uploadPath = "uploads/" . $newFileName;

                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath)) {

                        $updateQuery = "UPDATE designportoflioproject SET projectName = '$projectName', projectImgFileName = '$newFileName', description = '$description', designCategoryID = '$designCategoryID' WHERE id = '$projectId'";
                        if (mysqli_query($connection, $updateQuery)) {

                            header("Location: Designerhomepage.php");
                            exit();
                        } else {
                            echo "Error updating project: " . mysqli_error($connection);
                        }
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            } else {

                $updateQuery = "UPDATE designportoflioproject SET projectName = '$projectName', description = '$description', designCategoryID = '$designCategoryID' WHERE id = '$projectId'";
                if (mysqli_query($connection, $updateQuery)) {

                    header("Location: Designerhomepage.php");
                    exit();
                } else {
                    echo "Error updating project: " . mysqli_error($connection);
                }
            }
        } else {
            echo "No category found with the name " . $categoryName;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="PAstyle.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <title>Update Project</title>
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?projectId=" . $projectId; ?>" class="PA" method="post" enctype="multipart/form-data">
                <fieldset>
                    <a href="Designerhomepage.php"> <img src="uploads/WEb_delete.png" alt=""> </a>
                    <h2>Update Project</h2>
                    <label for="Pname">Project Name:</label>
                    <input type="text" id="Pname" name="ProjectName" value="<?php echo $project['projectName']; ?>"><br>
                    <label for="image">Project Image:</label>
                    <img src="uploads/<?php echo $project['projectImgFileName']; ?>" alt="Current Project Image" id="currentImage">
                    <input type="file" id="image" name="image"><br>
                    <label for="Category">Choose a Design Category:</label>
                    <select id="Category" name="DesignCategory">
                        <?php
                            $categoryQuery = "SELECT id, category FROM DesignCategory";
                            $categoryResult = mysqli_query($connection, $categoryQuery);

                            while ($category = mysqli_fetch_assoc($categoryResult)) {
                                echo "<option value='" . $category['category'] . "'";
                                if ($category['id'] == $project['designCategoryID']) {
                                    echo " selected"; // Select the current category
                                }
                                echo ">" . $category['category'] . "</option>";
                            }
                        ?>
                    </select><br>
                    <label for="Description">Description:</label><br>
                    <textarea id="Description" name="Descrip"><?php echo $project['description']; ?></textarea><br>
                    <input type="submit" value="Submit">
                </fieldset>
            </form>
        </div>
    </body>
</html>




