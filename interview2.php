<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// ✅ C Programming Interview Questions
$questions = [
    ["type" => "theory", "question" => "1. What are pointers in C, and how do they work?"],
    ["type" => "theory", "question" => "2. Explain the difference between malloc() and calloc()."],
    ["type" => "code", "question" => "3. Write a C program to check if a number is even or odd.", 
     "starter_code" => "#include <stdio.h>\nint main() {\n    // Your code here\n    return 0;\n}"],
    ["type" => "theory", "question" => "4. What is the difference between global and static variables in C?"],
    ["type" => "code", "question" => "5. Write a C program to find the factorial of a given number.", 
     "starter_code" => "#include <stdio.h>\nint main() {\n    // Your code here\n    return 0;\n}"],
    ["type" => "theory", "question" => "6. What is a segmentation fault in C?"],
    ["type" => "theory", "question" => "7. How does dynamic memory allocation work in C?"],
    ["type" => "code", "question" => "8. Write a C program to reverse a string.", 
     "starter_code" => "#include <stdio.h>\nint main() {\n    // Your code here\n    return 0;\n}"],
    ["type" => "theory", "question" => "9. Explain the difference between pass-by-value and pass-by-reference in C."],
    ["type" => "code", "question" => "10. Write a C program to check if a given string is a palindrome.", 
     "starter_code" => "#include <stdio.h>\nint main() {\n    // Your code here\n    return 0;\n}"]
];

// ✅ Handle GET request to fetch C questions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($questions);
    exit;
}

// ✅ Handle C Code Compilation Using Piston API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['compile'])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $code = $data["code"] ?? "";

    // ✅ If No Code Is Provided
    if (empty($code)) {
        echo json_encode(["error" => "No code provided"]);
        exit;
    }
    $api_url = "https://emkc.org/api/v2/piston/execute";
    // ✅ Call Piston API for Compilation
    $postData = json_encode([
        "language" => "c",
        "version" => "10.2.0",
        "files" => [
            [
                "name" => "main.c",
                "content" => $code
            ]
        ]
    ]);

    // ✅ CURL Request to Piston API
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

// ✅ Handle Theory Answers Submission (Save to JSON or MySQL)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // ✅ Save Answers in JSON File
    file_put_contents("answers_cprogramming.json", json_encode($data, JSON_PRETTY_PRINT));

    // ✅ Optionally Save Answers in MySQL (Uncomment If Needed)
    /*
    $conn = new mysqli("localhost", "root", "", "interview_db");
    $answer_json = json_encode($data);
    $sql = "INSERT INTO answers (subject, answers) VALUES ('c', '$answer_json')";
    $conn->query($sql);
    */

    // ✅ Success Response
    echo json_encode(["message" => "Answers submitted successfully!"]);
    exit;
}
?>
