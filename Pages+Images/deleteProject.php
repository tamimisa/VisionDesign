<?php
include "session.php";
// Error reporting
error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('display_errors', '1');

$connection = mysqli_connect("localhost", "root", "root", "visiondesign");

if (isset($_GET['projectId'])) {
    $projectId = mysqli_real_escape_string($connection, $_GET['projectId']);

    $sql = "DELETE FROM DesignPortoflioProject WHERE id = '$projectId'";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        // Project deleted successfully
        echo json_encode(array("success" => true));
    } else {
        // Error occurred during deletion
        echo json_encode(array("success" => false, "message" => "Error: " . mysqli_error($connection)));
    }
} else {
    // Project ID is not provided in the URL
    echo json_encode(array("success" => false, "message" => "Project ID is missing."));
}
