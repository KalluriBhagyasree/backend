<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

include 'DbConnect.php';
$ogjDb = new DbConnect();
$conn = $ogjDb->connect();

$sql = "SELECT id, question, option1, option2, option3, option4 FROM questions";
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($questions);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching questions: ' . $e->getMessage()]);
}
?>
