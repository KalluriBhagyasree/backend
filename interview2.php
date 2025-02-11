<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($questions);
    exit;
}

// Handling code compilation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['compile'])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $code = $data["code"] ?? "";

    if (empty($code)) {
        echo json_encode(["error" => "No code provided"]);
        exit;
    }

    $postData = json_encode([
        "clientId" => "e06fa282963ba7c4df22eb00374a6b00",  
        "clientSecret" => "98946e06be55e699880939dacf8240a1389ea394b82bd13e5acea69961e881c8",  
        "script" => $code,
        "language" => "c",
        "versionIndex" => "0"
    ]);

    $ch = curl_init("https://api.jdoodle.com/v1/execute");
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

// Handling theory answers submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    file_put_contents("answers_cprogramming.json", json_encode($data, JSON_PRETTY_PRINT));
    echo json_encode(["message" => "Answers submitted successfully!"]);
    exit;
}
?>
