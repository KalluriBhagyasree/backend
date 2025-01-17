<?php
// Display all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow cross-origin requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

// Include the database connection
include 'DbConnect.php';
$ogjDb = new DbConnect();
$conn = $ogjDb->connect();

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Handle GET request: Display all questions with their correct answers
        $sql = "SELECT id, question, correct_option FROM questions";
        $stmt = $conn->query($sql);

        if ($stmt->rowCount() > 0) {
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($questions);
        } else {
            echo json_encode(["message" => "No questions found"]);
        }
        break;

    case 'POST':
        // Handle POST request: Validate the user's selected answer
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate input data
        if (isset($data['questionId'], $data['selectedOption'])) {
            $questionId = $data['questionId'];
            $selectedOption = $data['selectedOption'];

            // Query to fetch the correct answer for the question
            $stmt = $conn->prepare("SELECT correct_option FROM questions WHERE id = :questionId");
            $stmt->bindParam(':questionId', $questionId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $correctOption = $result['correct_option'];

                // Check if the selected option matches the correct option
                $isCorrect = ($selectedOption == $correctOption);

                // Return the result as JSON
                echo json_encode(['isCorrect' => $isCorrect]);
            } else {
                // Question not found
                echo json_encode(['error' => 'Question not found']);
            }
        } else {
            // Missing parameters in the request
            echo json_encode(['error' => 'Invalid input']);
        }
        break;

    default:
        // Handle unsupported methods
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

// Close the database connection
$conn = null;
?>
