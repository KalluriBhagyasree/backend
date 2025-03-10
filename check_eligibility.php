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
$objDb = new DbConnect();
$conn = $objDb->connect();

if (!isset($conn)) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

if (!isset($_GET['username']) || !isset($_GET['subject'])) {
    die(json_encode(["success" => false, "message" => "Missing parameters."]));
}

$username = trim($_GET['username']);
$subject = trim($_GET['subject']);

$query = "SELECT SUM(score) AS total_score FROM scores WHERE username = :username AND subject = :subject";
$stmt = $conn->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':subject', $subject);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row && $row['total_score'] !== null) {
    echo json_encode(["success" => true, "total_score" => $row['total_score']]);
} else {
    echo json_encode(["success" => false, "message" => "No records found."]);
}
?>
