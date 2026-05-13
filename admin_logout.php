<?php
session_start();

// Destroy session
session_destroy();

// Clear admin session cookie if exists
if (isset($_COOKIE['admin_session'])) {
    setcookie('admin_session', '', time() - 3600, '/', '', true, true);
}

// Redirect to homepage
header('Location: index.php?logged_out=1');
exit;
?>
