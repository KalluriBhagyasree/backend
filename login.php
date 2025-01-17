<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

include 'DbConnect.php';
$ogjDb = new DbConnect;
$conn = $ogjDb->connect();
$method = $_SERVER['REQUEST_METHOD'];
switch($method)
    {
        case 'POST':
            $user = json_decode(file_get_contents('php://input'));

            if (isset($user->action) && $user->action === 'login') {
                
                $sql = "SELECT * FROM signup WHERE name = :username";
                $st = $conn->prepare($sql);
                $st->bindParam(':username', $user->username);
                $st->execute();
                $existingUser = $st->fetch(PDO::FETCH_ASSOC);
    
                if ($existingUser) {
                    if ($existingUser['password'] === $user->password) {
                        echo json_encode(['status' => 1, 'message' => 'Login successful']);
                    } else {
                        echo json_encode(['status' => 0, 'message' => 'Incorrect password']);
                    }
                } else {
                    echo json_encode(['status' => 0, 'message' => 'No account found with this username']);
                }
            } else {
                $hashedPassword = password_hash($user->password, PASSWORD_BCRYPT);
                $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
                $st = $conn->prepare($sql);
                $st->bindParam(':username', $user->username);
                $st->bindParam(':password', $hashedPassword); 
    
                if (password_verify($user->password, $existingUser['password'])) {
                    echo json_encode(['status' => 1, 'message' => 'Login successful']);
                } else {
                    echo json_encode(['status' => 0, 'message' => 'Incorrect password']);
                }
                
            }
            break;

            case 'GET':
                $sql = "SELECT * FROM users"; 
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
?>