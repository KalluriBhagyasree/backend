<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

include 'DbConnect.php';
$ogjDb = new DbConnect();
$conn = $ogjDb->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
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
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['questionId'], $data['selectedOption'])) {
            $questionId = $data['questionId'];
            $selectedOption = $data['selectedOption'];

            $stmt = $conn->prepare("SELECT correct_option FROM questions WHERE id = :questionId");
            $stmt->bindParam(':questionId', $questionId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $correctOption = $result['correct_option'];

                $isCorrect = ($selectedOption == $correctOption);

                echo json_encode(['isCorrect' => $isCorrect]);
            } else {
                echo json_encode(['error' => 'Question not found']);
            }
        } else {
            echo json_encode(['error' => 'Invalid input']);
        }
        break;

    default:
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

$conn = null;
?>
