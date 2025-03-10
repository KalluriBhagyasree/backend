<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

include 'DbConnect.php';
$ogjDb = new DbConnect();
$conn = $ogjDb->connect();

try {
    $sql = "SELECT id, title FROM cpp";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
 
    $titles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($titles)) {
        echo json_encode(['success' => true, 'titles' => $titles]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No titles found.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
