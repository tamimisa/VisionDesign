 <?php
    include "session.php";

    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('display_errors', '1');

    $connection = mysqli_connect("localhost", "root", "root", "visiondesign");
    if (mysqli_connect_error()) {
        echo '<p>Sorry, cannot connect to the database.</p>';
        die(mysqli_connect_error());
    } else {
        // Check if request ID is provided in the URL
        if (isset($_GET['requestID'])) {
            $requestID = $_GET['requestID'];

            // Construct SQL query to update the status to "consultation declined"
            $sql = "UPDATE DesignConsultationRequest SET statusID = 82 WHERE id = ?";

            $stmt = mysqli_prepare($connection, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $requestID);

                if (mysqli_stmt_execute($stmt)) {
                    // Request status updated successfully and Fetch consultation request details
                    $sql = "SELECT * FROM DesignConsultationRequest WHERE id = ?";
                    $stmt = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $requestID);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $consultationRequest = mysqli_fetch_assoc($result);

                    // Return JSON data containing consultation request details
                    echo json_encode($consultationRequest);
                } else {
                    // Error occurred while executing the prepared statement
                    echo json_encode(false);
                }

                mysqli_stmt_close($stmt);
            } else {
                // Error occurred while preparing the statement
                echo json_encode(false);
            }
        } else {
            // Request ID not provided in URL
            echo json_encode(false);
        }
    }
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        // AJAX request
        include "session.php";
    } else {
        // Not an AJAX request, handle accordingly 
        header("HTTP/1.0 403 Forbidden");
        exit('Direct access not allowed');
    }
?>