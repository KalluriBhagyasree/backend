<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

// Include the database connection
include 'DbConnect.php';

// Create an instance of DbConnect and establish connection
$ogjDb = new DbConnect();
$conn = $ogjDb->connect(); // Correct variable name here

$content_id = intval($_GET['content_id']); // Retrieve content_id from URL

try {
    
    $stmt = $conn->prepare("SELECT id, question, option1, option2, option3, option4, correct_option 
                            FROM java_quiz 
                            WHERE sub_concept_id = ?");
    $stmt->execute([$content_id]);

    // Fetch all questions as associative array
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($questions) {
        echo json_encode(['success' => true, 'questions' => $questions]); // Return questions
    } else {
        echo json_encode(['success' => false, 'message' => 'No questions found for the specified content_id.']);
    }
} catch (Exception $e) {
    // Handle any exceptions and return a JSON error message
    echo json_encode(['success' => false, 'message' => 'Error fetching questions: ' . $e->getMessage()]);
}
?>
