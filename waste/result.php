<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli("localhost", "root", "", "project_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['user_id']) && isset($_GET['subject'])) {
    $user_id = $_GET['user_id'];
    $subject = $_GET['subject'];

    $result = $conn->query("SELECT q.question, a.user_answer, a.correct_answer, a.output_result, a.status 
                             FROM interview_answers a
                             JOIN interview_questions q ON a.question_id = q.id
                             WHERE a.user_id = '$user_id' AND a.subject = '$subject'");

    echo "<h2>Interview Result for: " . ucfirst($subject) . "</h2>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Question</th><th>Your Answer</th><th>Correct Answer</th><th>Output Result</th><th>Status</th></tr>";

    while ($row = $result->fetch_assoc()) {
        $statusColor = ($row['status'] == 'correct') ? 'green' : 'red';
        echo "<tr>";
        echo "<td>" . $row['question'] . "</td>";
        echo "<td>" . $row['user_answer'] . "</td>";
        echo "<td>" . $row['correct_answer'] . "</td>";
        echo "<td>" . $row['output_result'] . "</td>";
        echo "<td style='color:$statusColor;'>" . ucfirst($row['status']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No User ID or Subject provided.";
}
?>
