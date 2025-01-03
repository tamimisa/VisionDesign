<?php
include "session.php";
    // Database connection
    $conn = new mysqli("localhost", "root", "root", "visiondesign");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if client is logged in
    $clientID = $_SESSION['id'];
    if (!isset($_SESSION['id'])) {
        // Redirect to login page if not logged in
        header("Location: login.php");
        exit();
    }

    // Function to retrieve client information
    function getClientInfo($conn, $clientId) {
        $sql = "SELECT * FROM client WHERE id = $clientId";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    // Function to retrieve designers by category
    function getDesignersByCategory($conn, $category) {
        $sql = "SELECT designer.*, GROUP_CONCAT(designcategory.category SEPARATOR ', ') AS specialties 
                        FROM designer 
                        JOIN designerspeciality ON designer.id = designerspeciality.designerID 
                        JOIN designcategory ON designerspeciality.designCategoryID = designcategory.id 
                        WHERE designcategory.category = '$category' 
                        GROUP BY designer.id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    // Function to retrieve all designers and their specialties
    function getAllDesigners($conn) {
        $sql = "SELECT designer.*, GROUP_CONCAT(designcategory.category SEPARATOR ', ') AS specialties 
                        FROM designer 
                        JOIN designerspeciality ON designer.id = designerspeciality.designerID 
                        JOIN designcategory ON designerspeciality.designCategoryID = designcategory.id 
                        GROUP BY designer.id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    // Function to retrieve consultation requests for the client
    function getClientConsultationRequests($conn, $clientId) {
        $sql = "SELECT designer.brandName, designer.logoImgFileName, 
                        designconsultationrequest.roomTypeID, designconsultationrequest.roomWidth, 
                        designconsultationrequest.roomLength, designcategory.category AS designCategory, 
                        designconsultationrequest.colorPreferences, designconsultationrequest.date, 
                        requeststatus.status, designconsultation.consultation, 
                        designconsultation.consultationImgFileName 
                        FROM designconsultationrequest 
                        JOIN designer ON designconsultationrequest.designerID = designer.id 
                        JOIN designcategory ON designconsultationrequest.designCategoryID = designcategory.id 
                        LEFT JOIN designconsultation ON designconsultationrequest.id = designconsultation.requestID 
                        JOIN requeststatus ON designconsultationrequest.statusID = requeststatus.id 
                        WHERE designconsultationrequest.clientID = $clientId";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    // Initialize variables
    $clientInfo = getClientInfo($conn, $_SESSION['id']);
    $designers = [];
    $consultationRequests = [];

    // Check request method
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // If POST, filter designers by category
        if (isset($_POST['Category'])) {
            $category = $_POST['Category'];
            $designers = getDesignersByCategory($conn, $category);
        }
    } else {
        // If GET, retrieve all designers
        $designers = getAllDesigners($conn);
    }

    // Retrieve consultation requests for the client
    $consultationRequests = getClientConsultationRequests($conn, $_SESSION['id']);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Client homepage</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
            />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                min-height: 80px;
                border-style: solid;
                border-width: 2px;
                padding: 30px 20px 10px;
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
                min-height: 400px;
                width: 1160px;
                padding: 20px;
            }
            .section-4 {
                display: flex;
                flex-direction: column;
                position: relative;
                min-height: 400px;
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
                margin-top: -150px;
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
            .image{
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
            option,select, input[type="submit"],input[type="button"] {
                font-size: 18px;
                padding: 10px 15px;
                background-color: #8AA4A7;
                border-color: #8AA4A7;
            }
            .column-3 select,.column-3 input[type="submit"],input[type="button"] {
                border-radius: 5px;
                margin-right: 5px;

            }

            .no-requests-message {
                padding-left: 300px;
                padding-right: 300px;
                text-align: center;
            }


        </style>
    </head>
    <body>
        <!-- Header -->
        <nav class="navbar">
            <div>
                <img src="uploads\LogoB.png" alt="logo" >
            </div>
        </nav>

        <!-- Client Info -->
        <div class="div">
            <section class="section">
                <div class="div-2">
                    <div class="div-3">
                        <div class="column">
                            <div class="div-4"><h2>Welcome <?php echo $clientInfo['firstName']; ?></h2></div>
                        </div>
                        <div class="column-2">
                            <div class="div-5">
                                <h3>
                                    <a href="logout.php"> <!-- Link to logout page -->
                                        <strong style="text-align: right">Log-out</strong>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="div-6">
                    First name: <?php echo $clientInfo['firstName']; ?><br> 
                    Last name: <?php echo $clientInfo['lastName']; ?> <br> 
                    Email address: <?php echo $clientInfo['emailAddress']; ?>
                </div>
                <!-- Filter -->
                <div class="div-7">
                    <div class="div-8">
                        <div class="column">
                        </div>
                        <div class="column-3">
                            <div class="div-10">
                                <h3>
                                    <strong>Select Category:</strong>
                                    <select id="categorySelect" name="Category">
                                        <option>All</option>
                                        <option>Country</option>
                                        <option>Modern</option>
                                        <option>Coastal</option>
                                        <option>Minimalist</option>
                                        <option>Bohemian</option>
                                    </select>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Designers Table -->
                <div class="section-3">
                    <section class="section-4">
                        <div class="div-11">
                            <div>
                                <table id="designersTable" style="width: 100%">  
                                    <caption><div class="div-9"><h3>Interior Designers : </h3></div></caption>
                                    <tr>
                                        <th>Designer</th>
                                        <th>Specialty</th>
                                        <td style="border-color:#EBE1D7 #EBE1D7 black black; background-color: #EBE1D7"></td>
                                    </tr>
                                    <?php
                                    // Check if $designers is an array or object before looping through it
                                    if (is_array($designers) || is_object($designers)) {
                                        foreach ($designers as $designer) {
                                            echo "<tr>";
                                            echo "<td><a style=\"background-color: #F6F6F6;\" href=\"Portfoliopage.php?designerId={$designer["id"]}\"><img src=\"uploads/" . $designer['logoImgFileName'] . "\" alt=\"" . $designer['brandName'] . " Logo\"/><br>" . $designer['brandName'] . "</a></td>";
                                            echo "<td>" . $designer['specialties'] . "</td>";
                                            echo "<td><a href=\"RequestDesignConsultation.php?designerid={$designer['id']}\"> <strong style=\"background-color: #F6F6F6;\">Request Design Consultation</strong> </a></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2' class='no-requests-message'>No designers found.</td></tr>";
                                    }
                                    ?>

                                </table>
                            </div>
                        </div>
                    </section>
                </div>
                <!-- Consultation Requests Table --> 
                <div class="section-5"> 
                    <section class="section-6"> 
                        <div class="div-13"> 
                            <table style="width: 100%"> 
                                <caption><div class="div-9"><h3>Previous Design Consultation Requests : </h3></div></caption> 
                                <tr> 
                                    <th>Designer</th> 
                                    <th>Room</th> 
                                    <th>Dimensions</th> 
                                    <th>Design Category</th> 
                                    <th>Color Preferences</th> 
                                    <th>Request Date</th> 
                                    <th>Design Consultation</th> 
                                </tr> 
                                <?php
                                if (is_array($consultationRequests)) {
                                    foreach ($consultationRequests as $request) {
                                        echo "<tr>";
                                        echo "<td><img src=\"uploads/" . $request['logoImgFileName'] . "\" alt=\"" . $request['brandName'] . " Logo\"/><br>" . $request['brandName'] . "</td>";
                                        echo "<td>";
                                        $roomTypeID = $request['roomTypeID'];
                                        $query = "SELECT type FROM roomtype WHERE id = $roomTypeID";
                                        $result = mysqli_query($conn, $query);
                                        if ($result && mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            echo $row['type'];
                                        } else {
                                            echo "N/A"; // If room type not found
                                        }
                                        echo "</td>";
                                        echo "<td>" . $request['roomWidth'] . "*" . $request['roomLength'] . "m</td>";
                                        echo "<td>" . $request['designCategory'] . "</td>";
                                        echo "<td>" . $request['colorPreferences'] . "</td>";
                                        echo "<td>" . $request['date'] . "</td>";
                                        echo "<td>";
                                        if ($request['consultation'] !== null) {
                                            echo "<img class=\"uploads\" src=\"uploads/" . $request['consultationImgFileName'] . "\" alt=\"Consultation uploads\"/>";
                                        } else {
                                            // Display status if consultation image is not provided
                                            echo $request['status'];
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No consultation requests found.</td></tr>";
                                }
                                ?>
                            </table>
                        </div>
                    </section>
                </div>
            </section>
        </div>
        <script>
            $(document).ready(function () {
                // Function to update designers table with retrieved data
                function updateDesignersTable(data) {
                    var tableBody = $('#designersTable');
                    tableBody.empty(); // Clear table body
                    if (data.length > 0) {
                        var tableContent = '<caption><div class="div-9"><h3>Interior Designers : </h3></div></caption>' +
                            '<tr><th>Designer</th><th>Specialty</th><td style="border-color:#EBE1D7 #EBE1D7 black black; background-color: #EBE1D7"></td></tr>';
                        $.each(data, function (index, designer) {
                            var row = '<tr>' +
                                '<td><a style="background-color: #F6F6F6;" href="Portfoliopage.php?designerId=' + designer.id + '"><img src="uploads/' + designer.logoImgFileName + '" alt="' + designer.brandName + ' Logo"/><br>' + designer.brandName + '</a></td>' +
                                '<td>' + designer.specialties + '</td>' +
                                '<td><a href="RequestDesignConsultation.php?designerid=' + designer.id + '"> <strong style="background-color: #F6F6F6;">Request Design Consultation</strong> </a></td>' +
                                '</tr>';
                            tableContent += row;
                        });
                        tableBody.html(tableContent); // Update table body
                    } else {
                        tableBody.html('<caption><div class="div-9"><h3>Interior Designers : </h3></div></caption><tr><td colspan="3" class="no-requests-message">No designers found.</td></tr>');
                    }
                }

                // Event listener for category select change
                $('#categorySelect').change(function () {
                    var selectedCategory = $(this).val();
                    $.ajax({
                        url: 'filter_designers.php', // PHP page to handle AJAX request
                        type: 'POST',
                        dataType: 'json',
                        data: { Category: selectedCategory },
                        success: function (data) {
                            updateDesignersTable(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                });

                // Trigger initial AJAX request on page load
                $('#categorySelect').trigger('change');
            });


        </script>




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
    </body>
</html>
