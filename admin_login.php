<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

require_once 'db.php';

$error = '';

// Rate limiting: max 5 attempts, 15 minute lockout
$max_attempts  = 5;
$lockout_time  = 15 * 60; // seconds

if (!isset($_SESSION['login_attempts']))  $_SESSION['login_attempts']   = 0;
if (!isset($_SESSION['lockout_until']))   $_SESSION['lockout_until']    = 0;

$is_locked_out = $_SESSION['lockout_until'] > time();
$lockout_remaining = (int) ceil(($_SESSION['lockout_until'] - time()) / 60);

if ($is_locked_out) {
    $error = "Too many failed attempts. Try again in {$lockout_remaining} minute(s).";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_locked_out) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate input
    if (!$username || !$password) {
        $error = 'Username and password are required.';
    } else {
        $conn = get_db();
        $stmt = $conn->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify password with bcrypt
            if (password_verify($password, $user['password_hash'])) {
                // Login successful — reset attempts and create session
                $_SESSION['login_attempts'] = 0;
                $_SESSION['lockout_until']  = 0;
                $_SESSION['admin_id']       = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['login_time']     = time();

                setcookie('admin_session', session_id(), time() + (86400 * 7), '/', '', true, true);

                header('Location: admin_dashboard.php');
                exit;
            } else {
                $_SESSION['login_attempts']++;
                $remaining = $max_attempts - $_SESSION['login_attempts'];

                if ($_SESSION['login_attempts'] >= $max_attempts) {
                    $_SESSION['lockout_until'] = time() + $lockout_time;
                    $error = 'Too many failed attempts. Try again in 15 minute(s).';
                } else {
                    $error = "Invalid username or password. {$remaining} attempt(s) remaining.";
                }
            }
        } else {
            $_SESSION['login_attempts']++;
            $remaining = $max_attempts - $_SESSION['login_attempts'];

            if ($_SESSION['login_attempts'] >= $max_attempts) {
                $_SESSION['lockout_until'] = time() + $lockout_time;
                $error = 'Too many failed attempts. Try again in 15 minute(s).';
            } else {
                $error = "Invalid username or password. {$remaining} attempt(s) remaining.";
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Portfolio</title>
    <link rel="stylesheet" href="admin_style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 2.5rem;
            backdrop-filter: blur(12px);
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }
        .login-container h1 {
            text-align: center;
            color: var(--text-label);
            margin-top: 0;
            font-size: 1.8rem;
        }
        .login-container p {
            text-align: center;
            color: var(--text-body-panel);
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            color: var(--text-label);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        input {
            width: 100%;
            padding: 0.9rem;
            background: var(--input-bg);
            border: 1px solid var(--glass-border);
            color: var(--input-text);
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0, 240, 255, 0.1);
        }
        button {
            width: 100%;
            padding: 1rem;
            background: transparent;
            border: 1px solid var(--accent);
            color: var(--accent);
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        button:hover {
            background: var(--accent);
            color: #000;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.5);
        }
        .error {
            background: rgba(255, 107, 107, 0.1);
            border: 1px solid #ff6b6b;
            color: #ff6b6b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-link a {
            color: var(--accent);
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🔐 Admin Login</h1>
        <p>Manage your portfolio projects</p>

        <?php if ($error): ?>
            <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="admin_login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="admin" 
                    required 
                    autofocus
                >
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••" 
                    required
                >
            </div>
            <button type="submit">Login</button>
        </form>

        <div class="back-link">
            <a href="index.php">← Back to Portfolio</a>
        </div>
    </div>
</body>
</html>
