<?php
// logout.php — Destroy user session and redirect to login
session_start();

// ── Destroy session ────────────────────────────────────────────────────────
session_destroy();

// ── Clear cookies if any ──────────────────────────────────────────────────
setcookie('remember_token', '', time() - 3600, '/');

// ── Redirect to home page ─────────────────────────────────────────────────
header('Location: index.html');
exit;
?>
