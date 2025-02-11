<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error.log');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include 'DbConnect.php';
$ogjDb = new DbConnect();
$conn = $ogjDb->connect();

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed.'
    ]);
    exit();
}


if (isset($_GET['title']) && !empty($_GET['title'])) {
    $title = $_GET['title'];

    try {
        $stmt = $conn->prepare("SELECT id, question, option_a, option_b, option_c, option_d, correct_option FROM java_questions WHERE title = :title");
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($questions) > 0) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'questions' => $questions
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'No questions available for the selected title.'
                ]);
            }
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to execute query.'
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Database query error: ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Title parameter is missing or empty.'
    ]);
}

$conn = null; 
?>
