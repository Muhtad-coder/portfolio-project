<?php
require_once 'db.php';

header('Content-Type: application/json');

$conn = get_db();
$result = $conn->query('SELECT id, title, description, language, status, link FROM projects ORDER BY id ASC');

if ($result === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch projects.']);
    $conn->close();
    exit;
}

$projects = [];
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}

$conn->close();
echo json_encode($projects);
