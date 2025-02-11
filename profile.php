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

if ($method === 'POST') {
    $user = json_decode(file_get_contents('php://input'));

    if (isset($user->action) && $user->action === 'getProfile' && isset($user->username)) {
        $sql = "SELECT name, email FROM signup WHERE name = :username";
        $st = $conn->prepare($sql);
        $st->bindParam(':username', $user->username);
        $st->execute();
        $userData = $st->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            echo json_encode(['status' => 1, 'data' => $userData]);
        } else {
            echo json_encode(['status' => 0, 'message' => 'User not found']);
        }
    } else {
        echo json_encode(['status' => 0, 'message' => 'Invalid request']);
    }
} else {
    echo json_encode(['status' => 0, 'message' => 'Method not allowed']);
}
?>
