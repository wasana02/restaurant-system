<?php
// ============================================================
// includes/db.php — Database Connection
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // WAMP default = empty password
define('DB_NAME', 'una_beach_restaurant');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('
    <div style="font-family:sans-serif; padding:40px; background:#fff3cd; border:1px solid #ffc107; border-radius:8px; margin:40px auto; max-width:600px;">
        <h3 style="color:#dc3545;">❌ Database Connection Failed</h3>
        <p><strong>Error:</strong> ' . $conn->connect_error . '</p>
        <hr>
        <p>✅ <strong>Fix these:</strong></p>
        <ol>
            <li>Make sure <strong>WAMP is running</strong> (tray icon must be green)</li>
            <li>Make sure you imported <strong>database.sql</strong> in phpMyAdmin</li>
            <li>Check that DB_NAME is <strong>una_beach_restaurant</strong></li>
        </ol>
    </div>');
}

$conn->set_charset('utf8mb4');
?>
