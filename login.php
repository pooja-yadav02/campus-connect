<?php
// login.php — Handles user login authentication
session_start();
require_once 'db_config.php';

// ── Only accept POST requests ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login_updated.php');
    exit;
}

// ── 1. Collect & sanitize input ────────────────────────────────────────────
$email    = trim(mysqli_real_escape_string($conn, $_POST['email']    ?? ''));
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// ── 2. Basic validation ────────────────────────────────────────────────────
$errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if (empty($password)) {
    $errors[] = 'Password is required.';
}

if (!empty($errors)) {
    $_SESSION['login_errors'] = $errors;
    $_SESSION['login_old']    = ['email' => $email];
    header('Location: login_updated.php');
    exit;
}

// ── 3. Look up user in database ────────────────────────────────────────────
$sql    = "SELECT id, full_name, email, password, role FROM user WHERE email = '$email' LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    // User not found — use a generic message to avoid user enumeration
    $_SESSION['login_errors'] = ['Invalid email or password. Please try again.'];
    $_SESSION['login_old']    = ['email' => $email];
    header('Location: login_updated.php');
    exit;
}

$user = mysqli_fetch_assoc($result);

// ── 4. Verify password against stored hash ─────────────────────────────────
if (!password_verify($password, $user['password'])) {
    $_SESSION['login_errors'] = ['Invalid email or password. Please try again.'];
    $_SESSION['login_old']    = ['email' => $email];
    header('Location: login_updated.php');
    exit;
}

// ── 5. Login successful — create session ──────────────────────────────────
session_regenerate_id(true);   // Prevent session fixation attacks

$_SESSION['user_id']    = $user['id'];
$_SESSION['user_name']  = $user['full_name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role']  = $user['role'];

// ── 6. Remember Me — set a cookie for 30 days ─────────────────────────────
if ($remember) {
    $token = bin2hex(random_bytes(32));   // Secure random token
    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
    // NOTE: In production, store this token in a `remember_tokens` DB table
    //       linked to the user id, and validate it on each visit.
}

// ── 7. Redirect based on role ──────────────────────────────────────────────
if ($user['role'] === 'lecturer') {
    header('Location: lecturer_dashboard.php');
} else {
    header('Location: student_dashboard.php');
}
exit;

mysqli_close($conn);
?>
