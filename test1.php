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
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $st = $conn->prepare($sql);
            $st->bindParam(':username', $user->username);
            $st->bindParam(':password', $user->password); 
            if($st->execute())  {
                $response = ['status' => 1, 'message' => 'User created successfully'];
            }   else    {
                $response = ['status' => 0, 'message' => 'Failed to create user'];
            }
            echo json_encode($response);
            break;
    }
?>