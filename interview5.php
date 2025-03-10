<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// ✅ Web Design Interview Questions
$questions = [
    ["type" => "theory", "question" => "1. What are the key principles of web design?"],
    ["type" => "theory", "question" => "2. Explain the difference between HTML and HTML5."],
    ["type" => "code", "question" => "3. Write HTML and CSS code to create a simple responsive navigation bar.", 
     "starter_code" => "<!DOCTYPE html>\n<html>\n<head>\n<style>\n/* Your CSS here */\n</style>\n</head>\n<body>\n<!-- Your HTML here -->\n</body>\n</html>"],
    ["type" => "theory", "question" => "4. What is the difference between inline, internal, and external CSS?"],
    ["type" => "code", "question" => "5. Write a JavaScript function to validate an email address.", 
     "starter_code" => "function validateEmail(email) {\n    // Your code here\n}\nconsole.log(validateEmail('test@example.com'));"],
    ["type" => "theory", "question" => "6. What are media queries in CSS? Provide examples."],
    ["type" => "theory", "question" => "7. Explain the concept of flexbox and grid layout in CSS."],
    ["type" => "code", "question" => "8. Write HTML and CSS to create a simple contact form.", 
     "starter_code" => "<!DOCTYPE html>\n<html>\n<head>\n<style>\n/* Your CSS here */\n</style>\n</head>\n<body>\n<form>\n    <!-- Your form fields here -->\n</form>\n</body>\n</html>"],
    ["type" => "theory", "question" => "9. What is the difference between relative, absolute, and fixed positioning in CSS?"],
    ["type" => "code", "question" => "10. Write JavaScript code to toggle a dark mode theme.", 
     "starter_code" => "function toggleDarkMode() {\n    // Your code here\n}\ndocument.getElementById('darkModeBtn').addEventListener('click', toggleDarkMode);"],
];

// ✅ Handle GET request to fetch Web Design Questions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($questions);
    exit;
}

// ✅ Handle JavaScript Code Compilation Using Piston API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['compile'])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $code = $data["code"] ?? "";

    // ✅ Error if no code is provided
    if (empty($code)) {
        echo json_encode(["error" => "No code provided"]);
        exit;
    }
    $api_url = "https://emkc.org/api/v2/piston/execute";
    // ✅ Piston API Body Data
    $postData = json_encode([
        "language" => "js",
        "version" => "18.16.0",
        "files" => [
            [
                "name" => "index.js",
                "content" => $code
            ]
        ]
    ]);

    // ✅ Piston API CURL Request
    $ch = curl_init("https://emkc.org/api/v2/piston/execute");
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

// ✅ Handle Theory Answers Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // ✅ Save Answers in JSON File
    file_put_contents("answers_webdesign.json", json_encode($data, JSON_PRETTY_PRINT));

    // ✅ Optionally Save in MySQL (Optional)
    /*
    $conn = new mysqli("localhost", "root", "", "interview_db");
    $answer_json = json_encode($data);
    $sql = "INSERT INTO answers (subject, answers) VALUES ('webdesign', '$answer_json')";
    $conn->query($sql);
    */

    echo json_encode(["message" => "Answers submitted successfully!"]);
    exit;
}
?>
