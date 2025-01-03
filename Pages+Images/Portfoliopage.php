<?php
    include "session.php";

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

    // Check if designer ID is set in session
    if(isset($_GET['designerId'])) {
    // Get designer ID from session
    $designerID = $_GET['designerId'];
        $sql = "SELECT dp.projectName, dp.projectImgFileName, dc.category, dp.description
                FROM designportoflioproject dp
                INNER JOIN DesignCategory dc ON dp.designCategoryID = dc.id
                WHERE dp.designerID = '$designerID'";
        $result = mysqli_query($conn, $sql);

        // Fetch designer information
        $sql2 = "SELECT id, firstName, lastName, emailAddress, brandName FROM designer WHERE id = ?";
        $stmt = $conn->prepare($sql2);
        $stmt->bind_param("i", $designerID);
        $stmt->execute();
        $stmt->bind_result($designerID, $firstName, $lastName, $emailAddress, $brandName);
        $stmt->fetch();

        // Close statement
        $stmt->close();
    } else {
        echo "Designer ID not found in session.";
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title> <?php echo $brandName;?> - Design Portfolio</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
            />
        <style>
             /*    footer     */
            .footer {
                line-height: 1.5;
                font-family: "Poppins", sans-serif;
                background-color: #24262b;
                padding-top: 2em;
                padding-bottom: 1em;
                margin-top: auto;
            }


            .footerrow {
                display: flex;
                flex-wrap: wrap;
                padding-left: 10em;
                background-color: #24262b;

            }
            ul {
                list-style: none;
            }

            .footer-col {
                background-color: #24262b;
                width: 30%;
                padding: 0 15px;
            }
            .footer-col h4 {
                font-size: 18px;
                color: #ffffff;
                background-color: #24262b;

                text-transform: capitalize;
                margin-bottom: 35px;
                font-weight: 500;
                position: relative;
            }
            .footer-col h4::before {
                content: "";
                position: absolute;
                left: 0;
                bottom: -10px;
                background-color: #8AA4A7;
                height: 2px;
                width: 50px;
            }

            .footer-col ul li {
                font-size: 16px;
                text-transform: capitalize;
                color: #ffffff;
                text-decoration: none;
                background-color: #24262b;

                font-weight: 300;
                color: #bbbbbb;
                display: block;
                transition: all 0.3s ease;
                margin: 0;
                padding: 0;
            }
            .fa-brands {
                padding: 2px;
                padding-bottom: 1em;
                background-color: #24262b;

            }
            #copyRight {
                font-size: 16px;
                text-transform: capitalize;
                color: #ffffff;
                font-weight: 300;
                color: #bbbbbb;
                background-color: #24262b;

                display: block;
            }

            body{
                font-family: Times New Roman;
                background-color: #EBE1D7;
            }
            *{
                font-family: Times New Roman;
            }
            .div {
                display: flex;
                flex-direction: column;
                position: relative;
                min-height: 500px;
                padding: 20px;
            }
            @media (max-width: 991px) {
                .div {
                    display: flex;
                }
            }
            @media (max-width: 640px) {
                .div {
                    display: flex;
                }
            }
            .section {
                display: flex;
                flex-direction: column;
                position: relative;
                min-height: 713px;
                padding: 20px;
                width: 100%;
                align-self: stretch;
                flex-grow: 1;
                max-width: 1200px;
                margin-left: auto;
                margin-right: auto;
            }
            @media (max-width: 991px) {
                .section {
                    display: flex;
                }
            }
            @media (max-width: 640px) {
                .section {
                    display: flex;
                }
            }
            .div-2 {
                display: flex;
                flex-direction: column;
                position: relative;
                margin-top: 20px;
            }
            .div-3 {
                gap: 20px;
                display: flex;
            }
            @media (max-width: 991px) {
                .div-3 {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 0px;
                }
            }
            .column {
                display: flex;
                flex-direction: column;
                line-height: normal;
                width: 50%;
                margin-left: 0px;
            }
            @media (max-width: 991px) {
                .column {
                    width: 100%;
                }
            }
            .div-4 {
                position: relative;
                margin-top: 20px;
                height: auto;
            }
            .column-2 {
                display: flex;
                flex-direction: column;
                line-height: normal;
                width: 50%;
                margin-left: 20px;
            }
            @media (max-width: 991px) {
                .column-2 {
                    width: 100%;
                }
            }
            .div-5 {
                position: relative;
                margin-top: 20px;
                height: auto;
                text-align: right;
            }
            .div-6 {
                position: relative;
                height: auto;
                min-height: 100px;
                border-style: solid;
                border-width: 2px;
                padding: 10px 20px 20px;
            }

            .div-7 {
                display: flex;
                flex-direction: column;
                position: relative;
                margin-top: 20px;
            }
            .div-8 {
                gap: 20px;
                display: flex;
            }
            @media (max-width: 991px) {
                .div-8 {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 0px;
                }
            }
            .div-9 {
                position: relative;
                height: auto;
                text-align: left;
                margin: auto 0;
            }
            .column-3 {
                display: flex;
                flex-direction: column;
                line-height: normal;
                width: 50%;
                margin-left: 20px;
            }
            @media (max-width: 991px) {
                .column-3 {
                    width: 100%;
                }
            }
            .div-10 {
                position: relative;
                height: auto;
                text-align: right;
                margin: auto 0;
            }
            .section-3 {
                display: flex;
                flex-direction: column;
                position: relative;
                min-height: 589px;
                width: 1160px;
                padding: 20px;
            }
            .section-4 {
                display: flex;
                flex-direction: column;
                position: relative;
                min-height: 589px;
                width: 100%;
                padding: 20px;
                align-self: stretch;
                flex-grow: 1;
                max-width: 1200px;
                margin-left: auto;
                margin-right: auto;
            }
            .div-11 {
                display: inline;
                flex-direction: column;
                height: auto;
                flex-grow: 0;
                font-size: 20px;
                text-align: center;
                font-weight: 400;
                border-style: none;
                border-width: 1px;
                margin: 0 auto;
            }
            .div-12 {
                position: relative;
                margin-top: -200px;
                height: auto;
                text-align: left;
            }
            .section-5 {
                display: flex;
                flex-direction: column;
                position: relative;
                min-height: 257px;
                padding: 20px 29px 20px 20px;
            }
            .section-6 {
                display: flex;
                flex-direction: column;
                position: relative;
                min-height: 257px;
                padding: 20px 29px 20px 20px;
                width: 100%;
                align-self: stretch;
                flex-grow: 1;
                max-width: 1200px;
                margin-left: auto;
                margin-right: auto;
            }
            .div-13 {
                display: inline;
                flex-direction: column;
                flex-grow: 0;
                font-size: 20px;
                text-align: center;
                font-weight: 400;
                border-style: none;
                border-width: 1px;
                margin: 0 auto;
            }
            table,th,td {
                border: 1px solid black;
                border-collapse: collapse;
                width: 100%;
                max-width: 800px;
                background-color: #F6F6F6;
            }
            th {
                background-color: #8AA4A7;
            }
            a {
                color: Black;
            }
            img {
                width: 60px;
                height: 60px;
            }
            table img{
                width:130px;
                height:130px;
            }
            .div-13 {
                display: flex;
                flex-direction: column;
                position: relative;
                margin-top: 20px;
                max-width: 100%;
            }

            .div-13 table {
                width: 80%;
                margin: auto;
            }

            .div-13 {
                overflow-x: auto;
            }


            table,th,td {
                border: 1px solid black;
                border-collapse: collapse;
                width: 25%;
                max-width: 800px;
            }

        </style>
            </head>
    <body>
        <div class="div">
            <section class="section">
                <div class="div-2"> <?php
                            $sqlForImg= "SELECT logoImgFileName FROM designer WHERE id=$designerID";
                            if($resultForImg = mysqli_query($conn, $sqlForImg)){
                                while ($rowFORImg = mysqli_fetch_assoc($resultForImg)) {
                                    
                                      echo "<img src='uploads/" . $rowFORImg['logoImgFileName'] . "' alt='" .$brandName. "' width='100' height='100'>";
                                }
                            }
                        ?>
                    <div class="div-3">
                        <div class="column">

                            <div class="div-4"> <h2> <?php echo $brandName;?> </h2></div>
                        </div>
                    </div>
                </div>
                <div class="section-3" >
                    <section class="section-4">
                        <div class="div-11" >
                            <table style="width: 100%">
                                <caption><div class="div-9"><h3>Design Portfolio :</h3></div></caption>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Image</th>
                                    <th>Design Category</th>
                                    <th>Description</th>
                                </tr>
                                <?php
                                    if (mysqli_num_rows($result) > 0) {
                                        // Output data of each row
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row["projectName"] . "</td>";
                                            echo "<td><img src='uploads/" . $row["projectImgFileName"] . "' alt='" . $row["projectName"] . "' style='width:100px;height:100px;'></td>";
                                            echo "<td>" . $row["category"] . "</td>";
                                            echo "<td>" . $row["description"] . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No projects found for this designer.</td></tr>";
                                    }
                                ?>
                            </table>

                        </div>
                    </section>
                </div>
            </section>
        </div>
           <!--footer-->
        <footer class="footer">
            <div class="footercontainer">
                <div class="footerrow">
                    <div class="footer-col">
                        <h4>Location</h4>
                        <ul>
                            <li>saudi arabia, Riyadh</li>
                            <li>saudi arabia, jeddah</li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>follow us</h4>
                        <div class="social">
                            <i class="fa-brands fa-twitter" style="color: #ffffff"></i>
                            <i class="fa-brands fa-instagram" style="color: #ffffff"></i>
                            <i class="fa-brands fa-facebook" style="color: #ffffff"></i>
                            <i class="fa-brands fa-linkedin" style="color: #ffffff"></i>
                        </div>
                        <p id="copyRight">©2023 Vision-design</p>
                    </div>
                    <div class="footer-col">
                        <h4>get help</h4>
                        <ul>
                            <li>+9660552778344</li>
                            <li>Vision-design@gmail.com</li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>

        <!--footer-->
    </body>
</html>
