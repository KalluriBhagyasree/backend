<?php
// Enable debugging and CORS
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include the database connection
include 'DbConnect.php';
$ogjDb = new DbConnect();
$conn = $ogjDb->connect();

// Get the sub_concept_id parameter
$subConceptId = isset($_GET['sub_concept_id']) ? intval($_GET['sub_concept_id']) : 0;

if ($subConceptId === 0) {
    echo json_encode(["error" => "sub_concept_id parameter is missing or invalid"]);
    exit;
}

try {
    // Prepare the query
    $query = "SELECT id, question, option1, option2, option3, option4 
              FROM quizzes 
              WHERE sub_concept_id = :sub_concept_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':sub_concept_id', $subConceptId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch results
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($data)) {
        echo json_encode(["message" => "No quizzes found for the given sub_concept_id"]);
    } else {
        echo json_encode($data);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Query failed: " . $e->getMessage()]);
}

// Close the connection
$conn = null;
?>
