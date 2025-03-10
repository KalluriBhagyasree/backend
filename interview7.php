<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$questions = [
    ["type" => "theory", "question" => "1. What is a Computer Network, and why is it used?"],
    ["type" => "theory", "question" => "2. Explain the difference between LAN, MAN, and WAN."],
    ["type" => "theory", "question" => "3. What is the difference between IP address and MAC address?"],
    ["type" => "theory", "question" => "4. What is TCP/IP model? Explain its layers."],
    ["type" => "theory", "question" => "5. What is the difference between Hub, Switch, and Router?"],
    ["type" => "theory", "question" => "6. What is DNS (Domain Name System) and how does it work?"],
    ["type" => "theory", "question" => "7. What is HTTP and HTTPS? Explain the difference."],
    ["type" => "theory", "question" => "8. What is a Firewall and why is it used in networks?"],
    ["type" => "theory", "question" => "9. Explain the concept of VPN (Virtual Private Network)."],
    ["type" => "theory", "question" => "10. What is the role of a Proxy Server in a network?"]
];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($questions);
    exit;
}

// Handling theory answers submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    file_put_contents("answers_network.json", json_encode($data, JSON_PRETTY_PRINT));
    echo json_encode(["message" => "Answers submitted successfully!"]);
    exit;
}
?>
