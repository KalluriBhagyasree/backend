<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Include the database connection file
include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();

// Check if the 'content' parameter exists
if (isset($_GET['content']) && !empty($_GET['content'])) {
    $content = $_GET['content'];

    try {
        // Use a prepared statement for security
        $stmt = $conn->prepare("SELECT content FROM tutorials WHERE title = :content LIMIT 1");
        $stmt->bindParam(':content', $content, PDO::PARAM_STR); // Bind 'content' as a string
        $stmt->execute();

        // Fetch the result
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode([
                "success" => true,
                "content" => $row['content'] // Return the content
            ]);
        } else {
            // If no matching content is found
            echo json_encode([
                "success" => false,
                "message" => "Content not found."
            ]);
        }
    } catch (Exception $e) {
        // Handle any exceptions
        echo json_encode([
            "success" => false,
            "message" => "An error occurred while fetching content.",
            "error" => $e->getMessage()
        ]);
    }
} else {
    // Missing 'content' parameter or it's empty
    echo json_encode([
        "success" => false,
        "message" => "No content specified or content is empty."
    ]);
}

// Close the database connection
$conn = null;
?>
