<?php
// register.php — Handles new user registration
session_start();
require_once 'db_config.php';

// ── Only accept POST requests ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register_updated.php');
    exit;
}

// ── 1. Collect & sanitize input ────────────────────────────────────────────
$full_name        = trim(mysqli_real_escape_string($conn, $_POST['full_name']        ?? ''));
$email            = trim(mysqli_real_escape_string($conn, $_POST['email']            ?? ''));
$password         = $_POST['password']         ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role             = $_POST['role']             ?? '';

// ── 2. Server-side validation ──────────────────────────────────────────────
$errors = [];

if (empty($full_name)) {
    $errors[] = 'Full name is required.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters long.';
}

if ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match.';
}

if (!in_array($role, ['student', 'lecturer'])) {
    $errors[] = 'Please select a valid role (Student or Lecturer).';
}

if (!empty($errors)) {
    // Return to form with error messages stored in session
    $_SESSION['reg_errors'] = $errors;
    $_SESSION['reg_old']    = compact('full_name', 'email', 'role');
    header('Location: register_updated.php');
    exit;
}

// ── 3. Check if email already exists ──────────────────────────────────────
$check_sql  = "SELECT id FROM user WHERE email = '$email' LIMIT 1";
$check_res  = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_res) > 0) {
    $_SESSION['reg_errors'] = ['An account with this email already exists. Please log in.'];
    $_SESSION['reg_old']    = compact('full_name', 'email', 'role');
    header('Location: register_updated.php');
    exit;
}

// ── 4. Hash password & insert new user ────────────────────────────────────
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$insert_sql = "INSERT INTO user (full_name, email, password, role)
               VALUES ('$full_name', '$email', '$hashed_password', '$role')";

if (mysqli_query($conn, $insert_sql)) {
    // Registration successful — auto-login the user
    $new_id = mysqli_insert_id($conn);

    $_SESSION['user_id']   = $new_id;
    $_SESSION['user_name'] = $full_name;
    $_SESSION['user_email']= $email;
    $_SESSION['user_role'] = $role;
    $_SESSION['reg_success'] = 'Account created successfully! Welcome, ' . htmlspecialchars($full_name) . '.';

    // Redirect to appropriate dashboard
    if ($role === 'lecturer') {
        header('Location: lecturer_dashboard.php');
    } else {
        header('Location: student_dashboard.php');
    }
    exit;
} else {
    $_SESSION['reg_errors'] = ['Registration failed. Please try again. Error: ' . mysqli_error($conn)];
    header('Location: register_updated.php');
    exit;
}

mysqli_close($conn);
?>
