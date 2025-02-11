<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight (OPTIONS request)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'DbConnect.php';
$ogjDb = new DbConnect();
$conn = $ogjDb->connect();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

// Read JSON data
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['answers']) || !is_array($data['answers'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid or missing answers data.']);
    exit();
}

$answers = $data['answers'];
$results = [];

foreach ($answers as $questionId => $userAnswer) {
    $stmt = $conn->prepare("SELECT correct_option FROM java_questions WHERE id = :id");
    $stmt->bindParam(':id', $questionId, PDO::PARAM_INT);
    $stmt->execute();
    $correctAnswer = $stmt->fetchColumn();

    if ($correctAnswer) {
        $isCorrect = ($userAnswer === $correctAnswer);
        $results[] = [
            'question_id' => $questionId,
            'user_answer' => $userAnswer,
            'correct_answer' => $correctAnswer,
            'is_correct' => $isCorrect
        ];
    }
}

http_response_code(200);
echo json_encode([
    'success' => true,
    'results' => $results
]);

$conn = null;
?>
