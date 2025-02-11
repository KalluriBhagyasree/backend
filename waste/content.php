<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

include 'DbConnect.php';
$objDb = new DbConnect();
$conn = $objDb->connect();

if (isset($_GET['content']) && !empty($_GET['content'])) {
    $content = $_GET['content'];

    try {
        $stmt = $conn->prepare("SELECT content FROM tutorials WHERE title = :content LIMIT 1");
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode([
                "success" => true,
                "content" => $row['content'] 
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Content not found."
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "An error occurred while fetching content.",
            "error" => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "No content specified or content is empty."
    ]);
}

$conn = null;
?>
