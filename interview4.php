<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// ✅ C++ Interview Questions
$questions = [
    ["type" => "theory", "question" => "1. What are the key features of C++?"],
    ["type" => "theory", "question" => "2. Explain the difference between C and C++."],
    ["type" => "code", "question" => "3. Write a C++ program to check if a number is even or odd.", 
     "starter_code" => "#include <iostream>\nusing namespace std;\n\nvoid checkEvenOdd(int n) {\n    // Your code here\n}\n\nint main() {\n    int num;\n    cin >> num;\n    checkEvenOdd(num);\n    return 0;\n}"],
    ["type" => "theory", "question" => "4. What is the difference between stack and heap memory in C++?"],
    ["type" => "code", "question" => "5. Write a C++ program to calculate the factorial of a given number.", 
     "starter_code" => "#include <iostream>\nusing namespace std;\n\nint factorial(int n) {\n    // Your code here\n}\n\nint main() {\n    int num;\n    cin >> num;\n    cout << factorial(num);\n    return 0;\n}"],
    ["type" => "theory", "question" => "6. What are constructors and destructors in C++?"],
    ["type" => "theory", "question" => "7. Explain the concept of polymorphism in C++."],
    ["type" => "code", "question" => "8. Write a C++ program to reverse a string.", 
     "starter_code" => "#include <iostream>\nusing namespace std;\n\nstring reverseString(string s) {\n    // Your code here\n}\n\nint main() {\n    string str;\n    cin >> str;\n    cout << reverseString(str);\n    return 0;\n}"],
    ["type" => "theory", "question" => "9. What is the difference between pass-by-value and pass-by-reference in C++?"],
    ["type" => "code", "question" => "10. Write a C++ program to check if a given string is a palindrome.", 
     "starter_code" => "#include <iostream>\nusing namespace std;\n\nbool isPalindrome(string s) {\n    // Your code here\n}\n\nint main() {\n    string str;\n    cin >> str;\n    cout << (isPalindrome(str) ? \"Yes\" : \"No\");\n    return 0;\n}"]
];

// ✅ Handle GET request to fetch C++ questions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($questions);
    exit;
}

// ✅ Handle C++ Code Compilation Using Piston API
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
        "language" => "cpp",
        "version" => "10.2.0",
        "files" => [
            [
                "name" => "main.cpp",
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
    file_put_contents("answers_cpp.json", json_encode($data, JSON_PRETTY_PRINT));

    // ✅ Optionally Save in MySQL (Optional)
    /*
    $conn = new mysqli("localhost", "root", "", "interview_db");
    $answer_json = json_encode($data);
    $sql = "INSERT INTO answers (subject, answers) VALUES ('cpp', '$answer_json')";
    $conn->query($sql);
    */

    echo json_encode(["message" => "Answers submitted successfully!"]);
    exit;
}
?>
