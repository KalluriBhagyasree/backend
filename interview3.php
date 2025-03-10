<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// ✅ Python Interview Questions
$questions = [
    ["type" => "theory", "question" => "1. What are Python lists, and how do they differ from tuples?"],
    ["type" => "theory", "question" => "2. Explain the difference between deep copy and shallow copy in Python."],
    ["type" => "code", "question" => "3. Write a Python program to check if a number is even or odd.", 
     "starter_code" => "def check_even_odd(n):\n    # Your code here\n    pass"],
    ["type" => "theory", "question" => "4. What is the difference between global and local variables in Python?"],
    ["type" => "code", "question" => "5. Write a Python program to calculate the factorial of a given number.", 
     "starter_code" => "def factorial(n):\n    # Your code here\n    pass"],
    ["type" => "theory", "question" => "6. What is a Python `NoneType` and when is it used?"],
    ["type" => "theory", "question" => "7. How does memory management work in Python?"],
    ["type" => "code", "question" => "8. Write a Python program to reverse a string.", 
     "starter_code" => "def reverse_string(s):\n    # Your code here\n    pass"],
    ["type" => "theory", "question" => "9. Explain the difference between pass-by-value and pass-by-reference in Python."],
    ["type" => "code", "question" => "10. Write a Python program to check if a given string is a palindrome.", 
     "starter_code" => "def is_palindrome(s):\n    # Your code here\n    pass"]
];

// ✅ Handling GET request to fetch questions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($questions);
    exit;
}

// ✅ Handling Python Code Compilation (Using Piston API)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['compile'])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $code = $data["code"] ?? "";

    if (empty($code)) {
        echo json_encode(["error" => "No code provided"]);
        exit;
    }

    // ✅ Piston Compiler API URL
    $api_url = "https://emkc.org/api/v2/piston/execute";

    // ✅ Data to be sent to the API
    $postData = json_encode([
        "language" => "python3",
        "version" => "3.10.0",
        "files" => [
            [
                "name" => "main.py",
                "content" => $code
            ]
        ]
    ]);

    // ✅ Making a CURL request to the Piston API
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(["error" => "Compilation request failed"]);
    } else {
        echo $response;
    }

    curl_close($ch);
    exit;
}

// ✅ Handling Theory Answers Submission (Store in File or Database)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // ✅ Option 1: Save answers in JSON file
    file_put_contents("answers_python.json", json_encode($data, JSON_PRETTY_PRINT));

    // ✅ Option 2: Save answers in MySQL (Optional)
    // $conn = new mysqli("localhost", "root", "", "interview_db");
    // $sql = "INSERT INTO answers (answer) VALUES ('".json_encode($data)."')";
    // $conn->query($sql);

    echo json_encode(["message" => "Answers submitted successfully!"]);
    exit;
}
?>
