<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// ✅ Java Interview Questions
$questions = [
    ["type" => "theory", "question" => "1.What are the four main principles of Object-Oriented Programming (OOP) in Java?"],
    ["type" => "theory", "question" => "2.What is the difference between checked and unchecked exceptions in Java?"],
    ["type" => "code", "question" => "3.Write a Java program to check if a number is prime.", 
     "starter_code" => "public class PrimeCheck {\n    public static void main(String[] args) {\n        // Your code here\n    }\n}"],
    ["type" => "theory", "question" => "4.What is the difference between synchronized and volatile in Java?"],
    ["type" => "code", "question" => "5.Write a Java program to find the factorial of a given number.", 
     "starter_code" => "public class Factorial {\n    public static void main(String[] args) {\n        // Your code here\n    }\n}"],
    ["type" => "theory", "question" => "6.What is Dependency Injection in Spring, and why is it used?"],
    ["type" => "theory", "question" => "7.How does Java's Garbage Collector work?"],
    ["type" => "code", "question" => "8.Write a Java program to reverse a string.", 
     "starter_code" => "public class ReverseString {\n    public static void main(String[] args) {\n        // Your code here\n    }\n}"],
    ["type" => "theory", "question" => "9.What is the Singleton Design Pattern in Java, and how is it implemented?"],
    ["type" => "code", "question" => "10.Write a Java program to check if a given string is a palindrome.", 
     "starter_code" => "public class PalindromeCheck {\n    public static void main(String[] args) {\n        // Your code here\n    }\n}"]
];

// ✅ Handle GET request to fetch questions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($questions);
    exit;
}

// ✅ Handle Java Code Compilation Using Piston API
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
        "language" => "java",
        "version" => "17.0.2",
        "files" => [
            [
                "name" => "Main.java",
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

// ✅ Handle Theory Answers Submission (Save to JSON or Database)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // ✅ Option 1: Save Answers in JSON File
    file_put_contents("answers_java.json", json_encode($data, JSON_PRETTY_PRINT));

    // ✅ Option 2: Save Answers in MySQL Database (Optional)
    /*
    $conn = new mysqli("localhost", "root", "", "interview_db");
    $answer_json = json_encode($data);
    $sql = "INSERT INTO answers (subject, answers) VALUES ('java', '$answer_json')";
    $conn->query($sql);
    */

    // ✅ Return Success Message
    echo json_encode(["message" => "Answers submitted successfully!"]);
    exit;
}
?>
