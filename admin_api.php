<?php
session_start();

// Verify admin session
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

require_once 'db.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';
$conn = get_db();

// ============================================================
// GET PROJECT (for editing)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get') {
    $id = intval($_GET['id'] ?? 0);
    
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid project ID']);
        exit;
    }
    
    $stmt = $conn->prepare('SELECT id, title, description, language, status, link FROM projects WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        echo json_encode([
            'success' => true,
            'project' => $result->fetch_assoc()
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Project not found']);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

// ============================================================
// POST REQUESTS (Create, Update, Delete, etc.)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Create or Update Project
if ($action === 'create' || $action === 'update') {
    $id = intval($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $language = trim($_POST['language'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $link = trim($_POST['link'] ?? '');
    
    // Validate
    if (!$title || !$description || !$language || !$status) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }
    
    if (!in_array($status, ['live', 'wip', 'planned'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid status']);
        exit;
    }
    
    // Validate URL if provided
    if ($link && !filter_var($link, FILTER_VALIDATE_URL)) {
        $link = '#'; // Default to # if invalid URL
    }
    
    if ($action === 'create') {
        // Create new project
        $stmt = $conn->prepare('
            INSERT INTO projects (title, description, language, status, link) 
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->bind_param('sssss', $title, $description, $language, $status, $link);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Project created', 'id' => $conn->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Database error']);
        }
        $stmt->close();
        
    } else {
        // Update existing project
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid project ID']);
            exit;
        }
        
        $stmt = $conn->prepare('
            UPDATE projects 
            SET title = ?, description = ?, language = ?, status = ?, link = ?
            WHERE id = ?
        ');
        $stmt->bind_param('sssssi', $title, $description, $language, $status, $link, $id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Project updated']);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Project not found']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Database error']);
        }
        $stmt->close();
    }
}

// Delete Project
elseif ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid project ID']);
        exit;
    }
    
    $stmt = $conn->prepare('DELETE FROM projects WHERE id = ?');
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Project deleted']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Project not found']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
    $stmt->close();
}

// Delete Contact
elseif ($action === 'delete_contact') {
    $id = intval($_POST['id'] ?? 0);
    
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid contact ID']);
        exit;
    }
    
    $stmt = $conn->prepare('DELETE FROM contacts WHERE id = ?');
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Contact deleted']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Contact not found']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
    $stmt->close();
}

// Change Admin Password
elseif ($action === 'change_password') {
    $current_password = trim($_POST['current_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    
    if (!$current_password || !$new_password) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing password fields']);
        exit;
    }
    
    if (strlen($new_password) < 8) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Password must be at least 8 characters']);
        exit;
    }
    
    // Get current password hash
    $admin_id = $_SESSION['admin_id'];
    $stmt = $conn->prepare('SELECT password_hash FROM admin_users WHERE id = ?');
    $stmt->bind_param('i', $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
    if ($result->num_rows !== 1) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'User not found']);
        exit;
    }
    
    $user = $result->fetch_assoc();
    
    // Verify current password
    if (!password_verify($current_password, $user['password_hash'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Current password is incorrect']);
        exit;
    }
    
    // Hash new password
    $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
    
    // Update password
    $stmt = $conn->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?');
    $stmt->bind_param('si', $new_hash, $admin_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to update password']);
    }
    $stmt->close();
}

else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

$conn->close();
?>
