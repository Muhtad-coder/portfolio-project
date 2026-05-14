<?php

// InfinityFree Database Configuration



define('DB_HOST', 'sql123.infinityfree.com');  // ← Got the  from InfinityFree panel
define('DB_PORT', 3306);                         // ← Standard MySQL port (NOT 8889)
define('DB_USER', 'epiz12345678_user');         // ← my InfinityFree DB username
define('DB_PASS', 'your_password_here');        // ← my InfinityFree DB password
define('DB_NAME', 'epiz12345678_portfolio');    // ← my InfinityFree DB name

function get_db(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}