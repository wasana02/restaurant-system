<?php
// ============================================================
// auth/logout.php — Destroy session and logout
// ============================================================
session_start();
session_unset();
session_destroy();
header('Location: ../index.php');
exit();
?>
