<?php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$reason  = trim($_POST['reason']  ?? '');
$message = trim($_POST['message'] ?? '');

// Server-side validation
if (!$name || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['error' => 'Name, email, and message are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email address.']);
    exit;
}

$conn = get_db();
$stmt = $conn->prepare('INSERT INTO contacts (name, email, reason, message) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssss', $name, $email, $reason, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Message saved successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save message.']);
}

$stmt->close();
$conn->close();
