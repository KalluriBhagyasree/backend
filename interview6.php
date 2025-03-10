<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// ✅ SQL Interview Questions
$questions = [
    ["type" => "theory", "question" => "1. What is SQL and why is it used?"],
    ["type" => "theory", "question" => "2. What is the difference between SQL and MySQL?"],
    ["type" => "theory", "question" => "3. Explain the concept of normalization in SQL?"],
    ["type" => "theory", "question" => "4. What is a primary key in SQL?"],
    ["type" => "theory", "question" => "5. What is the difference between INNER JOIN and LEFT JOIN?"],
    ["type" => "code", "question" => "6. Write an SQL query to create a table named 'employees'.", "starter_code" => "CREATE TABLE employees (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    age INT,
    department VARCHAR(100)
);"],
    ["type" => "code", "question" => "7. Write an SQL query to fetch all records from the 'employees' table.", "starter_code" => "SELECT * FROM employees;"],
    ["type" => "code", "question" => "8. Write an SQL query to update the age of an employee with id 5.", "starter_code" => "UPDATE employees SET age = 30 WHERE id = 5;"],
    ["type" => "code", "question" => "9. Write an SQL query to delete an employee with id 10.", "starter_code" => "DELETE FROM employees WHERE id = 10;"],
    ["type" => "theory", "question" => "10. What is the difference between DELETE and TRUNCATE?"],
];

// ✅ Handle GET Request for Questions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($questions);
    exit;
}

// ✅ Compile SQL Code Using Piston API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['compile'])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $code = $data["code"] ?? "";

    if (empty($code)) {
        echo json_encode(["error" => "No code provided"]);
        exit;
    }

    $postData = json_encode([
        "language" => "sql",
        "version" => "latest",
        "files" => [[
            "name" => "query.sql",
            "content" => $code
        ]]
    ]);

    $ch = curl_init("https://emkc.org/api/v2/piston/execute");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(["error" => "Compilation request failed"]);
    } else {
        echo $response;
    }

    curl_close($ch);
    exit;
}

?>
