<?php
include 'DbConnect.php';
$db = new DbConnect();
$conn = $db->connect();
if ($conn) {
    echo "✅ Database Connected Successfully!";
} else {
    echo "❌ Database Connection Failed!";
}
?>
