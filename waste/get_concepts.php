<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

include 'DbConnect.php';
$ogjDb = new DbConnect();
$conn = $ogjDb->connect();

$subject = isset($_GET['subject']) ? $_GET['subject'] : '';

if (empty($subject)) {
    echo json_encode(["error" => "Subject parameter is missing"]);
    exit;
}

try {
    $query = "SELECT id, sub_concept FROM concepts WHERE subject = :subject";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($data)) {
        echo json_encode(["message" => "No concepts found for the given subject"]);
    } else {
        echo json_encode($data);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Query failed: " . $e->getMessage()]);
}

$conn = null; 
?>
