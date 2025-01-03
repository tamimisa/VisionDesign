<?php
    include "session.php";

    // Database connection
    $conn = new mysqli("localhost", "root", "root", "visiondesign");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
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
            return [];
        }
    }

    // Function to retrieve all designers
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
            return [];
        }
    }

    // Check if the category is set in the POST data
    if (isset($_POST['Category'])) {
        $category = $_POST['Category'];
        if ($category === "All") {
            // If "All" is selected, fetch all designers
            $designers = getAllDesigners($conn);
        } else {
            // Otherwise, fetch designers by the selected category
            $designers = getDesignersByCategory($conn, $category);
        }
        // Return the designers data as JSON
        echo json_encode($designers);
    } else {
        // Return empty array if category is not set
        echo json_encode([]);
    }
?>
