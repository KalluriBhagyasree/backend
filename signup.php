<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

include 'DbConnect.php';
$ogjDb = new DbConnect;
$conn = $ogjDb->connect();
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'POST':
        $user = json_decode(file_get_contents('php://input'));
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid email format']);
            exit;
        }
        $checkSql = "SELECT * FROM signup WHERE email = :email";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindParam(':email', $user->email);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'error' => 'Email is already registered']);
        } else {
            $sql = "INSERT INTO signup (name, email, password,confirmpassword, created_at) 
                VALUES (:name, :email, :password, :confirmpassword, :created_at)";
            $st = $conn->prepare($sql);
            $st->bindParam(':name', $user->name);
            $st->bindParam(':email', $user->email);
            $st->bindParam(':password', $user->password);
            $st->bindParam(':confirmpassword', $user->confirmpassword);
            $st->bindParam(':created_at', $user->created_at);

            if ($st->execute()) {
                echo json_encode(['success' => true, 'message' => 'Signup successful']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to create user']);
            }
        }
        break;

    case 'GET':
        $sql = "SELECT * FROM signup";
        $st = $conn->prepare($sql);
        $st->execute();
        $users = $st->fetchAll(PDO::FETCH_ASSOC);

        if ($users) {
            echo json_encode(['status' => 1, 'data' => $users]);
        } else {
            echo json_encode(['status' => 0, 'message' => 'No users found']);
        }
        break;

    default:
        echo json_encode(['status' => 0, 'message' => 'Method not allowed']);
        break;
}