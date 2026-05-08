<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_updated.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_course'])) {
    $lecturer_id = $_SESSION['user_id'];
    $course_name = mysqli_real_escape_string($conn, trim($_POST['course_name'] ?? ''));
    $department  = mysqli_real_escape_string($conn, trim($_POST['department'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));

    $query = "INSERT INTO courses (lecturer_id, course_name, department, description) VALUES ('$lecturer_id', '$course_name', '$department', '$description')";
    mysqli_query($conn, $query);
    header('Location: lecturer_dashboard.php');
    exit();
}

$courses = mysqli_query($conn, "SELECT c.*, u.full_name AS lecturer_name FROM courses c LEFT JOIN user u ON c.lecturer_id = u.id ORDER BY c.id DESC");
$db_error = '';
if ($courses === false) {
    $db_error = mysqli_error($conn);
    $courses = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Browse Courses</title>
  <style>
    body { margin:0; font-family: Arial, sans-serif; background:#f2f4f8; color:#1f2937; }
    .page { max-width: 1080px; margin: 0 auto; padding: 28px 20px; }
    header { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:28px; }
    header h1 { font-size:1.8rem; margin:0; }
    .nav-links { display:flex; gap:12px; flex-wrap:wrap; }
    .nav-links a { color:#334155; text-decoration:none; padding:10px 14px; border-radius:10px; border:1px solid transparent; transition:.2s; }
    .nav-links a:hover, .nav-links a.active { background:#e2e8f0; border-color:#cbd5e1; }
    .courses-grid { display:grid; grid-template-columns: repeat(auto-fill,minmax(280px,1fr)); gap:18px; }
    .course-card { background:#fff; border:1px solid #e2e8f0; border-radius:18px; padding:20px; box-shadow: 0 6px 20px rgba(15,23,42,0.05); }
    .course-card h2 { margin:0 0 10px; font-size:1.2rem; }
    .course-card p { margin:8px 0; line-height:1.55; color:#475569; }
    .course-meta { display:flex; justify-content:space-between; gap:12px; font-size:.9rem; color:#64748b; margin-top:14px; }
    .empty-state, .error-box { background:#fff7ed; border:1px solid #fcd5c8; padding:18px; border-radius:14px; margin-top:20px; }
    .error-box { background:#fee2e2; border-color:#fecaca; color:#b91c1c; }
    .back-link { display:inline-flex; align-items:center; gap:8px; margin-top:18px; color:#1d4ed8; text-decoration:none; font-weight:600; }
  </style>
</head>
<body>
  <div class="page">
    <header>
      <div>
        <h1>Browse lecturer-uploaded courses</h1>
        <p>Explore all courses added by lecturers. Click the course title to view details.</p>
      </div>
      <div class="nav-links">
        <a href="student_dashboard.php">Dashboard</a>
        <a href="courses.php" class="active">Courses</a>
        <a href="logout.php">Logout</a>
      </div>
    </header>

    <?php if ($db_error): ?>
      <div class="error-box">
        <strong>Database error:</strong> <?= htmlspecialchars($db_error) ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($courses) && mysqli_num_rows($courses) > 0): ?>
      <div class="courses-grid">
        <?php while ($course = mysqli_fetch_assoc($courses)): ?>
          <div class="course-card">
            <h2><?= htmlspecialchars($course['course_name'] ?? $course['title'] ?? 'Untitled Course') ?></h2>
            <p><strong>Department:</strong> <?= htmlspecialchars($course['department'] ?? 'General') ?></p>
            <p><?= nl2br(htmlspecialchars($course['description'] ?? 'No description available.')) ?></p>
            <div class="course-meta">
              <span>Lecturer: <?= htmlspecialchars($course['lecturer_name'] ?? 'Unknown') ?></span>
              <span>ID: <?= htmlspecialchars($course['id']) ?></span>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <p>No lecturer-uploaded courses are available right now.</p>
      </div>
    <?php endif; ?>

    <a class="back-link" href="student_dashboard.php">← Back to dashboard</a>
  </div>
</body>
</html>