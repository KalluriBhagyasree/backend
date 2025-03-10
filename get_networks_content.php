<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

include 'DbConnect.php';
$ogjDb = new DbConnect();
$conn = $ogjDb->connect();

// Validate if at least one of 'id' or 'title' is set
if (!isset($_GET['id']) && !isset($_GET['title'])) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid parameter.']);
    exit();
}

try {
    if (isset($_GET['id']) && isset($_GET['title'])) {
        echo json_encode(['success' => false, 'message' => 'Only one parameter (id or title) can be used at a time.']);
        exit();
    }

    // Check if 'id' is passed
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT content FROM networks WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    }
    // Check if 'title' is passed
    elseif (isset($_GET['title'])) {
        $title = $_GET['title'];
        $sql = "SELECT content FROM networks WHERE title = :title";
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
