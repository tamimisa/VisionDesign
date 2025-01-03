<?php
    include "session.php";
    
    // Error reporting
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('display_errors', '1');

    if (isset($_SESSION['loggedIn'])) {
        if (!$_SESSION['loggedIn'] || $_SESSION['type'] != "Designer") {
            header('location: Login.php');
        }
    } else {
        header('location: Login.php');
    }

    $connection = mysqli_connect("localhost", "root", "root", "visiondesign");
    if (mysqli_connect_error()) {
        echo '<p> Sorry can not connect to Data Base </p><br>';
        die(mysqli_connect_error());
    } else {
        if (!isset($_SESSION['id'])) {
            echo("<script>alert('You are not logged in yet, please login or sign up first')</script>");
            echo("<script>window.location = 'index.php';</script>");
            exit();
        }

        if (!isset($_SESSION['type']) || $_SESSION['type'] == "client") {
            echo " Sorry you do not have access to this page!";
            echo("<script>window.location = 'clientHomepage.php';</script>");
        }

        if (isset($_SESSION['id'])) { // Check if userID exists in session
            $designerID = $_SESSION['id'];
            $sql = "SELECT id, firstName, lastName, emailAddress, brandName FROM designer WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $designerID);
            $stmt->execute();
            $stmt->bind_result($designerID, $firstName, $lastName, $emailAddress, $brandName);
            $stmt->fetch();

            // Close statement
            $stmt->close();
        }
    }
    // Check if AJAX request
    if(isset($_GET['requestID'])) {
        // Get the requestID
        $requestID = $_GET['requestID'];
        
        // Construct SQL query to update the status to "consultation declined"
        $sql = "UPDATE DesignConsultationRequest SET statusID = 'declined' WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $requestID);

        if ($stmt->execute()) {
            // Request status updated successfully and Send a JSON response indicating success
            echo json_encode(array("success" => true));
        } else {
            // Error occurred while executing the prepared statement
            echo json_encode(array("success" => false, "message" => "Failed to update request status"));
        }

        // Close statement
        $stmt->close();
        exit; 
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Designer homepage</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <style>

            .navbar {
                background-color: #8aa4a7;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 120%;
                width: 100%;
                box-shadow: 0px 1px 10px #999;
            }

            nav div img {
                padding-top: 5%; /*      extra   */
                padding-bottom: 5%;
                padding-left: 5%;
                width: 30%;
                height: 40%;
            }


            @media all and (max-width: 480px) {
                .navbar {
                    flex-direction: column;
                    box-shadow: 0 10em rgb(0 0 0 / 0.2);
                }

                nav div img {
                    width: 30%;
                    padding: 10px;
                    margin-left: 0px;
                    padding-right: 20;
                }

            }

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
                min-height: 713px;
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
                border-radius: 5px;
                background-color: #F6F6F6;
                border-color: #F6F6F6;
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

        <!--  header  -->
        <nav class="navbar">
            <div>
                <img src="uploads/LogoB.png" alt="logo">
            </div>
        </nav>

        <!--  test  -->

        <div class="div">
            <section class="section">
                <div class="div-2">
                    <div class="div-3">
                        <div class="column">
                            <div class="div-4"><h2>Welcome <?php echo $firstName; ?></h2></div>
                        </div>
                        <div class="column-2">
                            <div class="div-5">
                                <h3>
                                    <a href="logout.php" id='logout'> 
                                        <strong style="text-align: right"> Log-out</strong>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="div-6" >
                    <?php
                        $sqlForImg = "SELECT logoImgFileName FROM designer WHERE id=$designerID";

                        if ($resultForImg = mysqli_query($connection, $sqlForImg)) {
                            while ($rowFORImg = mysqli_fetch_assoc($resultForImg)) {

                                echo "<img src='uploads/" . $rowFORImg['logoImgFileName'] . "' alt='" . $brandName . "' width='100' height='100'>";
                            }
                        }
                    ?>
                    <br>First name: <?php echo $firstName; ?><br> 
                    Last name: <?php echo $lastName; ?><br>
                    Email address: <?php echo $emailAddress; ?><br>
                    Brand name: <?php echo $brandName; ?><br> 
                    <?php
                    $sqlspec = "SELECT dc.category
                    FROM designerspeciality ds
                    INNER JOIN designcategory dc ON ds.designCategoryID = dc.id
                    WHERE ds.designerID = '$designerID'";
                    $resultspec = mysqli_query($connection, $sqlspec);

                    $specialties = array(); // Initialize an empty array to store specialties

                    if (mysqli_num_rows($resultspec) > 0) {
                        while ($row = mysqli_fetch_assoc($resultspec)) {
                            $specialties[] = $row['category']; // Add each specialty to the array
                        }
                        // Join specialties array elements with comma and display
                        echo "Specialties: " . implode(", ", $specialties);
                    } else {
                        echo "No specialties found for this designer.";
                    }
                    ?> 


                    <br>
                </div>
                <div class="div-7">
                    <div class="div-8">
                        <div class="column">
                            <div class="div-9"></div>
                        </div>
                        <div class="column-3">
                            <div class="div-10">
                                <h3>
                                    <a href="ProjectAddition.php" id="addnewProject">
                                        <strong>Add New Project</strong>
                                    </a>
                                </h3>
                            </div>
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
                                    <td style="border-color:#EBE1D7 #EBE1D7 black black; background-color: #EBE1D7"></td>
                                    <td style="border-color:#EBE1D7 #EBE1D7 black black; background-color: #EBE1D7"></td>
                                    
                                </tr>
                                <?php
                                $sql = "SELECT * FROM designportoflioproject WHERE designerID = '$designerID'";
                                $result = mysqli_query($connection, $sql);
                                if (!$result) {
                                    die('Error in executing SQL query: ' . mysqli_error($connection));
                                }
                                if (mysqli_num_rows($result) == 0) {
                                    echo "<tr><td colspan='4'>No designs by the designer.</td></tr>";
                                } else {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['projectName'] . "</td>";
                                        echo "<td><img src='uploads/" . $row['projectImgFileName'] . "' alt='" . $row['projectName'] . "'></td>";

                                        // Query to fetch design category for the current project
                                        $sql2 = 'SELECT category FROM DesignCategory WHERE id = ' . $row["designCategoryID"];
                                        if ($result2 = mysqli_query($connection, $sql2)) {
                                            if ($row2 = mysqli_fetch_assoc($result2)) {
                                                echo "<td>" . $row2['category'] . "</td>";
                                            } else {
                                                echo '<td>No category found</td>'; // If no category found for the project
                                            }
                                        } else {
                                            echo '<td>No category found</td>'; // If no category found for the project
                                        }

                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td><a href='ProjectUpdate.php?projectId=" . $row['id'] . "'><strong style='background-color: #F6F6F6'>Edit</strong></a></td>";
                                        echo "<td><a href='#' class='delete-btn' data-project-id='" . $row['id'] . "'><strong style='background-color: #F6F6F6'>Delete</strong></a></td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>   
                            </table>

                        </div>
                    </section>
                </div>
            <div class="div-12"></div>
            <div class="section-5" >
                <section class="section-6">
                    <div class="div-13" >
                        <table style="width: 100%">
                            <caption><div class="div-9"><h3>Design Consultation Requests :</h3></div></caption>
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Room Type</th>
                                    <th>Room Dimensions</th>
                                    <th>Design Category</th>
                                    <th>Color Preferences</th>
                                    <th>Date</th>
                                    <td style="border-color:#EBE1D7 #EBE1D7 black black; background-color: #EBE1D7"></td>
                                    <td style="border-color:#EBE1D7 #EBE1D7 black black; background-color: #EBE1D7"></td>                
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // SQL query to select consultation requests for the designer
                                $sql = "SELECT dcr.*, c.firstName AS clientFirstName, c.lastName AS clientLastName, rt.type AS roomType, dc.category AS designCategory 
                                        FROM designconsultationrequest AS dcr 
                                        INNER JOIN Client AS c ON dcr.clientID = c.id 
                                        INNER JOIN RoomType AS rt ON dcr.roomTypeID = rt.id 
                                        INNER JOIN DesignCategory AS dc ON dcr.designCategoryID = dc.id 
                                        WHERE dcr.designerID = $designerID";


                                // Execute the query
                                if ($result = mysqli_query($connection, $sql)) {
                                    $addedRows = 0;
                                    if (mysqli_num_rows($result) > 0) {
                                        // Fetch and display each consultation request
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            if($row['statusID'] == 82){
                                                continue;
                                            }
                                            echo "<tr id='consultation_row_" . $row['id'] . "'>";
                                            echo "<td>" . $row['clientFirstName'] . " " . $row['clientLastName'] . "</td>";
                                            echo "<td>" . $row['roomType'] . "</td>";
                                            echo "<td>" . $row['roomWidth'] . "x" . $row['roomLength'] . "m</td>";
                                            echo "<td>" . $row['designCategory'] . "</td>";
                                            echo "<td>" . $row['colorPreferences'] . "</td>";
                                            echo "<td>" . $row['date'] . "</td>";
                                            echo "<td><a href='designconsultation.php?requestid=" . $row['id'] . "'>Provide Consultation</a></td>";
                                            echo "<td><a href='#' class='decline-btn' data-request-id='" . $row['id'] . "'>Decline Consultation</a></td>";
                                            echo "</tr>";
                                            
                                            $addedRows++;
                                        }
                                    } 
                                    if (!$addedRows) {
                                        // No consultation requests found for this designer
                                        echo "<tr><td colspan='7'>No consultation requests found for this designer.</td></tr>";
                                    }
                                    mysqli_free_result($result);
                                } else {
                                    // Error executing the query
                                    echo "<tr><td colspan='7'>Error: " . mysqli_error($connection) . "</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
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
                            <p id="copyRight">©️2023 Vision-design</p>
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
        <script>
            $(document).ready(function() {
                // When any button with class "decline-btn" is clicked
                $('.decline-btn').click(function(e) {
                    location.reload();
                    e.preventDefault(); // Prevent the default behavior of the button
                    var requestID = $(this).data('request-id'); // Get the value of the "data-request-id" attribute

                    // AJAX call to decline the consultation request
                    $.ajax({
                        type: 'GET',
                        url: 'declineConsultation.php',
                        data: { requestID: requestID },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                // Reload the page after successful decline
                                location.reload();
                            } else {
                                // Show an error message
                                console.log(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                });
            });
            
            
           <!---->  
             $(document).ready(function() {
        // When any button with class "delete-btn" is clicked
        $('.delete-btn').click(function(e) {
            location.reload();
            e.preventDefault(); // Prevent the default behavior of the button
            var projectId = $(this).data('project-id'); // Get the value of the "data-project-id" attribute

            // AJAX call to delete the project
            $.ajax({
                type: 'GET',
                url: 'deleteProject.php',
                data: { projectId: projectId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Remove the corresponding row from the HTML table
                        $('#project_row_' + projectId).remove();
                    } else {
                        // Show an error message
                        console.log(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });
        </script>     
    </body>
</html>