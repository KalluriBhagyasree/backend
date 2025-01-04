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
            $sql = "INSERT INTO signup (name, email, password, confirmpassword,created_at) VALUES (:name, :email, :password, :confirmpassword, :created_at)";
            $st = $conn->prepare($sql);
            $st->bindParam(':name', $user->name);
            $st->bindParam(':email', $user->email);
            $st->bindParam(':password', $user->password); 
            $st->bindParam(':confirmpassword', $user->confirmpassword);
            $st->bindParam(':created_at', $user->created_at);
            if($st->execute())  {
                $response = ['status' => 1, 'message' => 'user created successfully'];
            }   else    {
                $response = ['status' => 0, 'message' => 'Failed to create user'];
            }
            echo json_encode($response);
            break;
    }
?>