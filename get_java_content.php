<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

include 'DbConnect.php';
$ogjDb = new DbConnect();
$conn = $ogjDb->connect();

if (!isset($_GET['id']) && !isset($_GET['title'])) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid parameter.']);
    exit();
}

try {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT content FROM java WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    } elseif (isset($_GET['title'])) {
        $title = $_GET['title'];
        $sql = "SELECT content FROM java WHERE title = :title";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    }

    $stmt->execute();
    $content = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($content) {
        echo json_encode(['success' => true, 'content' => $content['content']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Content not found.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

