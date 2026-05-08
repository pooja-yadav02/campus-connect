<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Campus Connect - Register</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #f5f7fb;
      font-family: 'Segoe UI', sans-serif;
    }

    .navbar {
      background: #fff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .logo {
      width: 40px;
      height: 40px;
      background: #2563eb;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      font-weight: bold;
    }

    .register-container {
      min-height: 90vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding-top: 15px;
      padding-bottom: 15px;
    }

    .register-card {
      width: 100%;
      max-width: 450px;
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .register-card h2 {
      font-weight: 700;
    }

    .form-control, .form-select {
      border-radius: 8px;
      padding: 10px;
    }

    .btn-primary {
      border-radius: 8px;
      padding: 10px;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background: #1d4ed8;
    }

    .login-link a {
      text-decoration: none;
      color: #2563eb;
      font-weight: 500;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    footer {
      background: linear-gradient(135deg, #0f172a, #1e293b);
      color: #cbd5e1;
    }

    footer a {
      color: #cbd5e1;
      transition: 0.3s;
    }

    footer a:hover {
      color: white;
      transform: translateX(3px);
      display: inline-block;
    }

    .social-icons a {
      font-size: 1.2rem;
      transition: 0.3s;
    }

    .social-icons a:hover {
      transform: translateY(-3px);
      color: #2563eb !important;
    }
  </style>
</head>

<body>

<?php
// ── Start session to read flash messages ──────────────────────────────────
session_start();
$errors  = $_SESSION['reg_errors']  ?? [];
$old     = $_SESSION['reg_old']     ?? [];
$success = $_SESSION['reg_success'] ?? '';

// Clear flash messages after reading
unset($_SESSION['reg_errors'], $_SESSION['reg_old'], $_SESSION['reg_success']);
?>

<!-- Navbar -->
<nav class="navbar px-4 py-2 d-flex justify-content-between">
  <div class="d-flex align-items-center gap-2">
    <a href="index.html" class="d-flex align-items-center gap-2 text-decoration-none">
      <div class="logo">CC</div>
      <h5 class="mb-0">Campus Connect</h5>
    </a>
  </div>

  <div>
    <a href="login_updated.php" class="me-3 text-dark text-decoration-none">Login</a>
    <a href="register_updated.php" class="btn btn-primary btn-sm">Get started</a>
  </div>
</nav>

<!-- Register Form -->
<div class="register-container">
  <div class="register-card">

    <h2 class="text-center mb-4">Create your account</h2>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- ACTION points to register.php -->
    <form id="registerForm" action="register.php" method="POST">

      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control"
               placeholder="Enter full name"
               value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
               required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email address</label>
        <input type="email" name="email" class="form-control"
               placeholder="Enter email"
               value="<?= htmlspecialchars($old['email'] ?? '') ?>"
               required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" id="password"
               class="form-control" placeholder="Enter password" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirmPassword"
               class="form-control" placeholder="Confirm password" required>
      </div>

      <div class="mb-3">
        <label class="form-label">I am a:</label>
        <select name="role" class="form-select" required>
          <option value="">Select role</option>
          <option value="student"  <?= ($old['role'] ?? '') === 'student'  ? 'selected' : '' ?>>Student</option>
          <option value="lecturer" <?= ($old['role'] ?? '') === 'lecturer' ? 'selected' : '' ?>>Lecturer</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary w-100">Create Account</button>

      <p class="text-center mt-3 login-link">
        Already have an account? <a href="login_updated.php">Sign in</a>
      </p>

    </form>

    <div class="text-center mt-2">
      <a href="index.html" class="text-decoration-none">← Go back to Home</a>
    </div>

  </div>
</div>

<!-- Footer -->
<footer class="mt-5 pt-5 pb-3">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4">
        <h5 class="text-white fw-bold">Campus Connect</h5>
        <p class="small opacity-75">Empowering education through smart technology. Bridging the gap between lecturers and students.</p>
        <div class="social-icons mt-3">
          <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
          <a href="#" class="me-3"><i class="fab fa-linkedin-in"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
      <div class="col-md-2 mb-4">
        <h6 class="text-white fw-bold">Product</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><a href="index.html#features" class="text-decoration-none">Features</a></li>
          <li class="mb-2"><a href="index.html#howitworks" class="text-decoration-none">How it works</a></li>
          <li class="mb-2"><a href="index.html#stories" class="text-decoration-none">Stories</a></li>
        </ul>
      </div>
      <div class="col-md-2 mb-4">
        <h6 class="text-white fw-bold">Resources</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><a href="#" class="text-decoration-none">Help Center</a></li>
          <li class="mb-2"><a href="#" class="text-decoration-none">Guides</a></li>
          <li class="mb-2"><a href="#" class="text-decoration-none">API Docs</a></li>
        </ul>
      </div>
      <div class="col-md-4 mb-4">
        <h6 class="text-white fw-bold">Stay updated</h6>
        <div class="input-group">
          <input type="email" class="form-control" placeholder="Your email address">
          <button class="btn btn-primary">Subscribe</button>
        </div>
        <p class="small mt-2 opacity-75">Get the latest updates and news</p>
      </div>
    </div>
    <hr class="opacity-25">
    <div class="text-center small opacity-75">© 2026 Campus Connect — Bridging learning & collaboration | All Rights Reserved</div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Client-side password match check (pre-validation before submit) -->
<script>
  document.getElementById("registerForm").addEventListener("submit", function(e){
    const password        = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    if (password !== confirmPassword) {
      e.preventDefault();
      alert("Passwords do not match!");
    }
  });
</script>

</body>
</html>