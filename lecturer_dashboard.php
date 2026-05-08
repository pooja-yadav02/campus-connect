<?php
// ── Authenticate user via PHP session ──────────────────────────────────────
session_start();

// ── If not logged in or not a lecturer, redirect to login ──────────────────
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'lecturer') {
    header('Location: login_updated.php');
    exit;
}

// ── Prepare user data as JSON for JavaScript use ──────────────────────────
$user_data = [
    'id'    => $_SESSION['user_id'],
    'name'  => $_SESSION['user_name'],
    'email' => $_SESSION['user_email'],
    'role'  => $_SESSION['user_role']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Campus Connect — Lecturer Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Fraunces:wght@600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    body { visibility: hidden; }
  </style>
  <!-- Pass server user data to JavaScript -->
  <script>
    window.__SERVER_USER__ = <?php echo json_encode($user_data); ?>;
  </script>
  <style>

    :root {
      --blue:        #2563eb;
      --blue-dark:   #1d4ed8;
      --blue-light:  #eff6ff;
      --blue-mid:    #dbeafe;
      --gray-50:     #f9fafb;
      --gray-100:    #f3f4f6;
      --gray-200:    #e5e7eb;
      --gray-300:    #d1d5db;
      --gray-400:    #9ca3af;
      --gray-500:    #6b7280;
      --gray-600:    #4b5563;
      --gray-700:    #374151;
      --gray-900:    #111827;
      --green:       #059669;
      --green-light: #d1fae5;
      --amber:       #d97706;
      --amber-light: #fef3c7;
      --red:         #dc2626;
      --red-light:   #fee2e2;
      --nav-h:       62px;
      --sidebar-w:   240px;
      --radius:      12px;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: #f4f6fb;
      color: var(--gray-900);
      min-height: 100vh;
    }

    /* ═══════════════════════════════
       NAVBAR
    ═══════════════════════════════ */
    nav {
      position: fixed;
      top: 0; left: 0; right: 0;
      height: var(--nav-h);
      background: #fff;
      border-bottom: 1px solid var(--gray-200);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.5rem 0 1rem;
      z-index: 200;
    }
    .nav-left {
      display: flex;
      align-items: center;
      gap: 0;
    }
    .hamburger {
      width: 36px;
      height: 36px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: 5px;
      cursor: pointer;
      padding: 6px;
      border-radius: 8px;
      margin-right: 0.75rem;
      transition: background 0.15s;
    }
    .hamburger:hover { background: var(--gray-100); }
    .hamburger span {
      display: block;
      height: 1.5px;
      background: var(--gray-600);
      border-radius: 2px;
      transition: all 0.25s;
    }
    .brand {
      display: flex;
      align-items: center;
      gap: 9px;
      text-decoration: none;
      cursor: pointer;
    }
    .brand-logo {
      width: 36px;
      height: 36px;
      background: var(--blue);
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 700;
      font-size: 0.82rem;
      letter-spacing: -0.3px;
    }
    .brand-name {
      font-family: 'Fraunces', serif;
      font-size: 1.05rem;
      color: var(--gray-900);
      font-weight: 700;
    }
    .nav-right {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    .nav-user-pill {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 5px 12px 5px 6px;
      border-radius: 99px;
      border: 1px solid var(--gray-200);
      cursor: pointer;
      transition: background 0.15s;
      font-size: 0.82rem;
      font-weight: 500;
      color: var(--gray-700);
      background: white;
    }
    .nav-user-pill:hover { background: var(--gray-50); }
    .nav-avatar {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: var(--blue-mid);
      color: var(--blue-dark);
      font-size: 0.72rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .btn-logout {
      padding: 6px 14px;
      border-radius: 8px;
      border: 1px solid var(--gray-200);
      background: white;
      color: var(--gray-600);
      font-size: 0.82rem;
      font-weight: 500;
      font-family: inherit;
      cursor: pointer;
      transition: all 0.15s;
    }
    .btn-logout:hover { background: var(--red-light); color: var(--red); border-color: #fecaca; }

    /* ═══════════════════════════════
       LAYOUT
    ═══════════════════════════════ */
    .layout {
      display: flex;
      padding-top: var(--nav-h);
      min-height: 100vh;
    }

    /* ═══════════════════════════════
       SIDEBAR
    ═══════════════════════════════ */
    .sidebar {
      position: fixed;
      top: var(--nav-h);
      left: 0;
      bottom: 0;
      width: var(--sidebar-w);
      background: white;
      border-right: 1px solid var(--gray-200);
      padding: 1.5rem 0.75rem;
      overflow-y: auto;
      transition: transform 0.25s;
      z-index: 100;
      display: flex;
      flex-direction: column;
    }
    .sidebar-section-label {
      font-size: 0.68rem;
      font-weight: 600;
      color: var(--gray-400);
      text-transform: uppercase;
      letter-spacing: 0.08em;
      padding: 0 0.75rem;
      margin-bottom: 0.4rem;
      margin-top: 1.2rem;
    }
    .sidebar-section-label:first-child { margin-top: 0; }
    .nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 0.55rem 0.75rem;
      border-radius: 9px;
      cursor: pointer;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--gray-600);
      transition: all 0.15s;
      margin-bottom: 2px;
      text-decoration: none;
    }
    .nav-item:hover { background: var(--gray-100); color: var(--gray-900); }
    .nav-item.active { background: var(--blue-light); color: var(--blue); }
    .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
    .sidebar-bottom {
      margin-top: auto;
      padding-top: 1rem;
      border-top: 1px solid var(--gray-200);
    }

    /* ═══════════════════════════════
       MAIN CONTENT
    ═══════════════════════════════ */
    .content {
      margin-left: var(--sidebar-w);
      flex: 1;
      padding: 2rem 2rem 4rem;
      max-width: calc(100% - var(--sidebar-w));
    }

    /* ═══════════════════════════════
       PAGES
    ═══════════════════════════════ */
    .page { display: none; }
    .page.active { display: block; animation: fadeIn 0.2s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

    /* ═══════════════════════════════
       PAGE HEADER
    ═══════════════════════════════ */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 2rem;
    }
    .page-title {
      font-family: 'Fraunces', serif;
      font-size: 1.6rem;
      color: var(--gray-900);
      line-height: 1.2;
    }
    .page-sub { font-size: 0.875rem; color: var(--gray-500); margin-top: 0.3rem; }

    /* ═══════════════════════════════
       BUTTONS
    ═══════════════════════════════ */
    .btn {
      padding: 0.55rem 1.1rem;
      border-radius: 9px;
      font-size: 0.85rem;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      border: none;
      transition: all 0.15s;
    }
    .btn-primary { background: var(--blue); color: white; }
    .btn-primary:hover { background: var(--blue-dark); box-shadow: 0 3px 10px rgba(37,99,235,0.25); }
    .btn-secondary { background: white; color: var(--gray-700); border: 1px solid var(--gray-200); }
    .btn-secondary:hover { background: var(--gray-50); }
    .btn-amber { background: var(--amber); color: white; }
    .btn-amber:hover { background: #b45309; }
    .btn-danger { background: var(--red-light); color: var(--red); border: 1px solid #fecaca; }
    .btn-danger:hover { background: #fecaca; }
    .btn-sm { padding: 0.38rem 0.85rem; font-size: 0.78rem; }
    .btn-full { width: 100%; padding: 0.75rem; font-size: 0.9rem; }

    /* ═══════════════════════════════
       STATS GRID
    ═══════════════════════════════ */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
      margin-bottom: 2rem;
    }
    .stat-card {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      padding: 1.25rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .stat-icon {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .stat-icon svg { width: 22px; height: 22px; }
    .stat-icon.blue   { background: var(--blue-light); color: var(--blue); }
    .stat-icon.green  { background: var(--green-light); color: var(--green); }
    .stat-icon.amber  { background: var(--amber-light); color: var(--amber); }
    .stat-label { font-size: 0.75rem; color: var(--gray-500); font-weight: 500; margin-bottom: 0.2rem; }
    .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--gray-900); line-height: 1; }

    /* ═══════════════════════════════
       SECTION TITLE
    ═══════════════════════════════ */
    .section-title {
      font-size: 1rem;
      font-weight: 600;
      color: var(--gray-900);
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* ═══════════════════════════════
       COURSE CARDS
    ═══════════════════════════════ */
    .courses-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1rem;
    }
    .course-card {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      padding: 1.25rem 1.5rem;
      transition: box-shadow 0.15s, transform 0.15s;
    }
    .course-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.07); transform: translateY(-2px); }
    .course-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; }
    .course-card-badge {
      font-size: 0.68rem;
      font-weight: 600;
      padding: 3px 9px;
      border-radius: 99px;
      background: var(--blue-light);
      color: var(--blue);
    }
    .course-card-title { font-size: 0.975rem; font-weight: 600; margin-bottom: 0.2rem; line-height: 1.3; }
    .course-card-desc { font-size: 0.82rem; color: var(--gray-500); line-height: 1.5; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .course-card-meta { display: flex; gap: 1rem; font-size: 0.76rem; color: var(--gray-500); margin-bottom: 1rem; }
    .course-card-meta span { display: flex; align-items: center; gap: 4px; }
    .course-card-actions { display: flex; gap: 0.5rem; }

    /* ═══════════════════════════════
       EMPTY STATE
    ═══════════════════════════════ */
    .empty-state {
      background: white;
      border: 1.5px dashed var(--gray-200);
      border-radius: var(--radius);
      padding: 3.5rem 2rem;
      text-align: center;
      color: var(--gray-400);
    }
    .empty-icon { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.6; }
    .empty-state p { font-size: 0.9rem; margin-bottom: 1.25rem; color: var(--gray-500); }

    /* ═══════════════════════════════
       FORM CARD
    ═══════════════════════════════ */
    .form-wrap { max-width: 700px; }
    .form-card {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      padding: 2rem;
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
    .form-group { margin-bottom: 1.2rem; }
    .form-group label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--gray-700); margin-bottom: 0.4rem; }
    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 0.6rem 0.9rem;
      border: 1.5px solid var(--gray-200);
      border-radius: 9px;
      font-size: 0.875rem;
      font-family: inherit;
      outline: none;
      transition: border-color 0.15s, box-shadow 0.15s;
      color: var(--gray-900);
      background: white;
    }
    .form-group input:focus,
    .form-group textarea:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .form-group textarea { resize: vertical; min-height: 110px; }
    .form-group input::placeholder,
    .form-group textarea::placeholder { color: var(--gray-400); }
    .form-actions { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem; }

    /* ═══════════════════════════════
       COURSE DETAIL
    ═══════════════════════════════ */
    .detail-header {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      padding: 1.75rem 2rem;
      margin-bottom: 1.5rem;
    }
    .detail-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; }
    .detail-title { font-family: 'Fraunces', serif; font-size: 1.5rem; margin-bottom: 0.3rem; }
    .detail-code { font-size: 0.8rem; color: var(--blue); font-weight: 600; margin-bottom: 0.5rem; }
    .detail-desc { font-size: 0.875rem; color: var(--gray-600); line-height: 1.6; }
    .detail-meta { display: flex; gap: 1.5rem; font-size: 0.82rem; color: var(--gray-500); margin-top: 0.75rem; }
    .detail-actions { display: flex; gap: 0.6rem; flex-shrink: 0; }

    /* ═══════════════════════════════
       TABS
    ═══════════════════════════════ */
    .tabs { display: flex; border-bottom: 1px solid var(--gray-200); margin-bottom: 1.5rem; }
    .tab {
      padding: 0.7rem 1.25rem;
      font-size: 0.875rem;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      color: var(--gray-500);
      transition: all 0.15s;
      margin-bottom: -1px;
      font-weight: 500;
    }
    .tab:hover { color: var(--gray-700); }
    .tab.active { color: var(--blue); border-bottom-color: var(--blue); }
    .tab-content { display: none; }
    .tab-content.active { display: block; }

    /* ═══════════════════════════════
       MATERIAL ITEMS
    ═══════════════════════════════ */
    .material-item {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: 10px;
      padding: 1rem 1.25rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 0.65rem;
      transition: box-shadow 0.15s;
    }
    .material-item:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .mat-icon {
      width: 40px;
      height: 40px;
      background: var(--blue-light);
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .mat-icon svg { width: 20px; height: 20px; color: var(--blue); }
    .mat-info { flex: 1; min-width: 0; }
    .mat-name { font-weight: 600; font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mat-meta { font-size: 0.75rem; color: var(--gray-400); margin-top: 2px; }
    .mat-actions { display: flex; gap: 0.5rem; flex-shrink: 0; margin-left: auto; }

    .material-viewer {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: 14px;
      padding: 1.2rem;
      margin-top: 1.2rem;
    }
    .viewer-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1rem;
      flex-wrap: wrap;
    }
    .viewer-title { font-weight: 700; font-size: 1rem; }
    .viewer-sub { font-size: 0.82rem; color: var(--gray-500); margin-top: 0.2rem; }
    .viewer-body { min-height: 420px; border-radius: 12px; overflow: hidden; background: var(--gray-100); }
    #pdf-viewer { width: 100%; height: 100%; min-height: 420px; }

    .upload-zone-note { font-size: 0.8rem; color: var(--gray-500); margin-top: 0.55rem; }
    .upload-zone-warning { font-size: 0.8rem; color: var(--amber); margin-top: 0.35rem; }

    /* ═══════════════════════════════
       STUDENT ITEMS
    ═══════════════════════════════ */
    .student-item {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: 10px;
      padding: 0.9rem 1.25rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 0.65rem;
    }
    .stu-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: var(--blue-mid);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.8rem;
      color: var(--blue-dark);
      flex-shrink: 0;
    }
    .stu-name { font-weight: 600; font-size: 0.875rem; }
    .stu-email { font-size: 0.75rem; color: var(--gray-400); }

    /* ═══════════════════════════════
       UPLOAD ZONE
    ═══════════════════════════════ */
    .upload-zone {
      border: 2px dashed var(--gray-300);
      border-radius: 10px;
      padding: 2.5rem;
      text-align: center;
      cursor: pointer;
      transition: all 0.15s;
      background: var(--gray-50);
    }
    .upload-zone:hover,
    .upload-zone.dragover { border-color: var(--blue); background: var(--blue-light); }
    .upload-zone-icon { color: var(--gray-400); margin-bottom: 0.75rem; }
    .upload-zone-text { font-size: 0.875rem; color: var(--gray-600); }
    .upload-zone-text a { color: var(--blue); font-weight: 500; }
    .upload-zone-hint { font-size: 0.75rem; color: var(--gray-400); margin-top: 0.3rem; }
    .upload-zone-selected { font-size: 0.82rem; color: var(--green); font-weight: 600; margin-top: 0.5rem; }
    #file-input { display: none; }

    /* ═══════════════════════════════
       PROFILE
    ═══════════════════════════════ */
    .profile-card {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      padding: 2.5rem;
      max-width: 560px;
    }
    .profile-avatar-lg {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background: var(--blue-mid);
      color: var(--blue-dark);
      font-size: 1.6rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.25rem;
    }
    .profile-name { font-family: 'Fraunces', serif; font-size: 1.3rem; }
    .profile-role-badge {
      display: inline-block;
      background: var(--blue-light);
      color: var(--blue);
      font-size: 0.72rem;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 99px;
      margin-top: 0.4rem;
    }
    .profile-fields { margin-top: 1.75rem; }
    .profile-field {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.65rem 0;
      border-bottom: 1px solid var(--gray-100);
      font-size: 0.875rem;
    }
    .profile-field:last-child { border-bottom: none; }
    .profile-field label { color: var(--gray-500); font-weight: 500; }
    .profile-field span { font-weight: 600; color: var(--gray-900); }

    /* ═══════════════════════════════
       SEARCH BAR
    ═══════════════════════════════ */
    .search-bar {
      padding: 0.55rem 1rem;
      border: 1px solid var(--gray-200);
      border-radius: 9px;
      font-size: 0.875rem;
      font-family: inherit;
      outline: none;
      width: 260px;
      background: white;
      color: var(--gray-900);
    }
    .search-bar:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,0.08); }
    .search-bar::placeholder { color: var(--gray-400); }

    /* ═══════════════════════════════
       MODAL
    ═══════════════════════════════ */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.35);
      z-index: 500;
      align-items: center;
      justify-content: center;
    }
    .modal-overlay.show { display: flex; }
    .modal {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      width: 90%;
      max-width: 460px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal-title { font-family: 'Fraunces', serif; font-size: 1.2rem; margin-bottom: 0.75rem; }
    .modal-body { font-size: 0.875rem; color: var(--gray-600); line-height: 1.6; margin-bottom: 1.5rem; }
    .modal-actions { display: flex; justify-content: flex-end; gap: 0.75rem; }

    /* ═══════════════════════════════
       TOAST
    ═══════════════════════════════ */
    .toast {
      position: fixed;
      bottom: 1.5rem;
      right: 1.5rem;
      background: var(--gray-900);
      color: white;
      padding: 0.75rem 1.25rem;
      border-radius: 10px;
      font-size: 0.85rem;
      font-weight: 500;
      z-index: 9999;
      opacity: 0;
      transform: translateY(8px);
      transition: all 0.3s;
      pointer-events: none;
    }
    .toast.show { opacity: 1; transform: translateY(0); }
    .toast.success { background: var(--green); }
    .toast.error   { background: var(--red); }

    /* ═══════════════════════════════
       BADGE
    ═══════════════════════════════ */
    .badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 99px;
      font-size: 0.68rem;
      font-weight: 600;
    }
    .badge-blue  { background: var(--blue-light);  color: var(--blue); }
    .badge-green { background: var(--green-light); color: var(--green); }

    /* ═══════════════════════════════
       FOOTER
    ═══════════════════════════════ */
    footer {
      background: var(--gray-900);
      color: white;
      padding: 2.5rem 2rem 1.75rem;
      margin-left: var(--sidebar-w);
    }
    .footer-inner {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 2rem;
    }
    .footer-brand { font-family: 'Fraunces', serif; font-size: 1.1rem; margin-bottom: 0.4rem; }
    .footer-tag { font-size: 0.8rem; color: var(--gray-400); line-height: 1.6; max-width: 280px; }
    .footer-links { display: flex; gap: 1.5rem; flex-wrap: wrap; }
    .footer-links a { color: var(--gray-400); text-decoration: none; font-size: 0.82rem; cursor: pointer; }
    .footer-links a:hover { color: white; }
    .footer-bottom {
      max-width: 1100px;
      margin: 1.5rem auto 0;
      border-top: 1px solid #333;
      padding-top: 1rem;
      display: flex;
      justify-content: space-between;
      font-size: 0.75rem;
      color: var(--gray-500);
    }

    /* ═══════════════════════════════
       RESPONSIVE
    ═══════════════════════════════ */
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); }
      .content { margin-left: 0; max-width: 100%; padding: 1.5rem 1rem 3rem; }
      footer { margin-left: 0; }
      .stats-grid { grid-template-columns: 1fr; }
      .form-row { grid-template-columns: 1fr; }
      .detail-top { flex-direction: column; }
      .detail-actions { width: 100%; flex-wrap: wrap; }
    }
  </style>
</head>
<body>

<!-- ═══════════════════════════════════════
     NAVBAR
═══════════════════════════════════════ -->
<nav>
  <div class="nav-left">
    <div class="hamburger" onclick="toggleSidebar()" title="Toggle sidebar">
      <span></span><span></span><span></span>
    </div>
    <div class="brand" onclick="navigate('dashboard')">
      <div class="brand-logo">CC</div>
      <span class="brand-name">Campus Connect</span>
    </div>
  </div>
  <div class="nav-right">
    <div class="nav-user-pill" onclick="navigate('profile')">
      <div class="nav-avatar" id="nav-avatar">L</div>
      <span id="nav-username">Lecturer</span>
    </div>
    <button class="btn-logout" onclick="logout()">Log out</button>
  </div>
</nav>

<!-- ═══════════════════════════════════════
     LAYOUT
═══════════════════════════════════════ -->
<div class="layout">

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-section-label">Main</div>

    <a class="nav-item active" id="nav-dashboard" onclick="navigate('dashboard')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
      </svg>
      Dashboard
    </a>

    <a class="nav-item" id="nav-courses" onclick="navigate('courses')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
      </svg>
      My Courses
    </a>

    <a class="nav-item" id="nav-profile" onclick="navigate('profile')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
      </svg>
      Profile
    </a>

    <div class="sidebar-section-label">Quick Actions</div>
    <a class="nav-item" onclick="navigate('create-course')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
      </svg>
      Create Course
    </a>

    <div class="sidebar-bottom">
      <a class="nav-item" onclick="logout()" style="color: var(--red);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
          <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Log Out
      </a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="content">

    <!-- ══════════ PAGE: DASHBOARD ══════════ -->
    <div class="page active" id="page-dashboard">
      <div class="page-header">
        <div>
          <div class="page-title" id="dash-welcome">Welcome back</div>
          <div class="page-sub">Here's an overview of your teaching activity</div>
        </div>
        <button class="btn btn-primary" onclick="navigate('create-course')">+ New Course</button>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
            </svg>
          </div>
          <div><div class="stat-label">Total Courses</div><div class="stat-value" id="stat-courses">0</div></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
          </div>
          <div><div class="stat-label">Total Students</div><div class="stat-value" id="stat-students">0</div></div>
        </div>
        <div class="stat-card">
          <div class="stat-icon amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
              <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
          </div>
          <div><div class="stat-label">Materials Uploaded</div><div class="stat-value" id="stat-materials">0</div></div>
        </div>
      </div>

      <div class="section-title">
        My Courses
        <span style="font-size:0.78rem; color:var(--gray-400); font-weight:400;" id="dash-course-count"></span>
      </div>
      <div class="courses-grid" id="dashboard-courses"></div>
    </div>

    <!-- ══════════ PAGE: ALL COURSES ══════════ -->
    <div class="page" id="page-courses">
      <div class="page-header">
        <div>
          <div class="page-title">All Courses</div>
          <div class="page-sub">Browse all available courses</div>
        </div>
        <input class="search-bar" placeholder="Search courses…" oninput="filterCourses(this.value)" id="course-search">
      </div>
      <div class="courses-grid" id="courses-list"></div>
    </div>

    <!-- ══════════ PAGE: COURSE DETAIL ══════════ -->
    <div class="page" id="page-course-detail">
      <div class="detail-header">
        <div class="detail-top">
          <div style="flex:1; min-width:0;">
            <div class="detail-code" id="detail-code"></div>
            <div class="detail-title" id="detail-title"></div>
            <div class="detail-desc" id="detail-desc"></div>
            <div class="detail-meta">
              <span id="detail-enrolled"></span>
              <span id="detail-materials-count"></span>
            </div>
          </div>
          <div class="detail-actions" id="detail-actions"></div>
        </div>
      </div>
      <div class="tabs">
        <div class="tab active" id="tab-materials" onclick="switchTab('materials')">Course Materials</div>
        <div class="tab"       id="tab-students"  onclick="switchTab('students')">Enrolled Students</div>
      </div>
      <div class="tab-content active" id="tab-content-materials"><div id="materials-list"></div></div>
      <div class="tab-content"        id="tab-content-students"><div  id="students-list"></div></div>
      <div class="material-viewer" id="material-viewer" style="display:none;">
        <div class="viewer-header">
          <div>
            <div class="viewer-title" id="viewer-title"></div>
            <div class="viewer-sub" id="viewer-sub"></div>
          </div>
          <div class="viewer-actions">
            <button class="btn btn-secondary btn-sm" onclick="openMaterialInNewTab()">Open in new tab</button>
            <button class="btn btn-secondary btn-sm" onclick="closeMaterialViewer()">Close</button>
            <button class="btn btn-primary btn-sm" id="replace-pdf-btn" onclick="openReplaceMaterial(currentCourseId, selectedViewerMaterialId)">Replace PDF</button>
          </div>
        </div>
        <div class="viewer-body">
          <object id="pdf-viewer" type="application/pdf" width="100%" height="100%"></object>
        </div>
      </div>
    </div>

    <!-- ══════════ PAGE: CREATE / EDIT COURSE ══════════ -->
    <div class="page" id="page-create-course">
      <div class="page-header form-wrap">
        <div>
          <div class="page-title" id="course-form-title">Create New Course</div>
          <div class="page-sub"   id="course-form-sub">Set up a new course for students to enroll in</div>
        </div>
      </div>
      <div class="form-wrap">
        <div class="form-card">
          <input type="hidden" id="edit-course-id">
          <div class="form-row">
            <div class="form-group" style="margin:0;">
              <label>Course Title</label>
              <input type="text" id="course-title" placeholder="e.g. Introduction to Computer Science">
            </div>
            <div class="form-group" style="margin:0;">
              <label>Course Code</label>
              <input type="text" id="course-code" placeholder="e.g. CS101">
            </div>
          </div>
          <div class="form-group" style="margin-top:1.25rem;">
            <label>Course Description</label>
            <textarea id="course-desc" placeholder="Describe the course content and learning objectives…"></textarea>
          </div>
          <div class="form-actions">
            <button class="btn btn-secondary" onclick="navigate('dashboard')">Cancel</button>
            <button class="btn btn-primary"   id="save-course-btn" onclick="saveCourse()">Create Course</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════ PAGE: UPLOAD MATERIAL ══════════ -->
    <div class="page" id="page-upload-material">
      <div class="page-header form-wrap">
        <div>
          <div class="page-title">Upload Material</div>
          <div class="page-sub" id="upload-sub">Add a new learning resource</div>
        </div>
      </div>
      <div class="form-wrap">
        <div class="form-card">
          <input type="hidden" id="upload-course-id">
          <div class="form-group">
            <label>Material Title</label>
            <input type="text" id="material-title" placeholder="e.g. Week 1 Lecture Notes">
          </div>
          <div class="form-group">
            <label>Upload File</label>
            <div class="upload-zone" id="upload-zone"
                 onclick="document.getElementById('file-input').click()"
                 ondragover="handleDragOver(event)"
                 ondragleave="handleDragLeave()"
                 ondrop="handleDrop(event)">
              <div class="upload-zone-icon">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
              </div>
              <div class="upload-zone-text"><a>Click to upload</a> or drag and drop</div>
              <div class="upload-zone-hint">PDF, PPT, DOC, ZIP, MP4, AVI, MOV, WMV, FLV, WebM, MKV — up to 10 MB</div>
              <div class="upload-zone-selected" id="file-selected"></div>
              <div class="upload-zone-note" id="upload-note" style="display:none;">Leave the file input empty to keep the current document.</div>
              <div class="upload-zone-warning" id="upload-warning" style="display:none;"></div>
            </div>
            <input type="file" id="file-input" accept=".pdf,.ppt,.pptx,.doc,.docx,.zip,.mp4,.avi,.mov,.wmv,.flv,.webm,.mkv" onchange="fileSelected(this)">
          </div>
          <div class="form-actions">
            <button class="btn btn-secondary" onclick="cancelUpload()">Cancel</button>
            <button class="btn btn-primary"   onclick="uploadMaterial()">Upload Material</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════ PAGE: PROFILE ══════════ -->
    <div class="page" id="page-profile">
      <div class="page-header">
        <div class="page-title">Profile</div>
      </div>
      <div class="profile-card">
        <div class="profile-avatar-lg" id="profile-avatar">L</div>
        <div class="profile-name" id="profile-name">Lecturer</div>
        <div class="profile-role-badge">Lecturer / Faculty</div>
        <div class="profile-actions" style="margin-top:1rem;">
          <button class="btn btn-primary btn-sm" onclick="openProfileEditor()">Edit Profile</button>
        </div>
        <div class="profile-fields">
          <div class="profile-field"><label>Full Name</label>      <span id="pf-name">—</span></div>
          <div class="profile-field"><label>Email</label>          <span id="pf-email">—</span></div>
          <div class="profile-field"><label>Role</label>           <span id="pf-role">Lecturer</span></div>
          <div class="profile-field"><label>Courses Created</label><span id="pf-courses">—</span></div>
          <div class="profile-field"><label>Member Since</label>   <span id="pf-since">—</span></div>
        </div>
        <div class="profile-edit-form" id="profile-edit-form" style="display:none; margin-top:1.75rem;">
          <div class="form-group">
            <label>Full Name</label>
            <input type="text" id="edit-profile-name" class="form-control" placeholder="Enter full name">
          </div>
          <div class="form-group" style="margin-top:1rem;">
            <label>Email</label>
            <input type="email" id="edit-profile-email" class="form-control" placeholder="Enter email">
          </div>
          <div class="form-actions" style="margin-top:1.25rem; gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeProfileEditor()">Cancel</button>
            <button class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
          </div>
        </div>
      </div>
    </div>

  </main><!-- /content -->
</div><!-- /layout -->

<!-- ═══════════════════════════════════════
     DELETE MODAL
═══════════════════════════════════════ -->
<div class="modal-overlay" id="delete-modal">
  <div class="modal">
    <div class="modal-title">Delete Course</div>
    <div class="modal-body">Are you sure you want to delete this course? All materials and enrollment data will be permanently removed. This cannot be undone.</div>
    <div class="modal-actions">
      <button class="btn btn-secondary" onclick="closeModal('delete-modal')">Cancel</button>
      <button class="btn" style="background:var(--red);color:white;" onclick="confirmDeleteCourse()">Delete Course</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"></div>

<!-- ═══════════════════════════════════════
     FOOTER
═══════════════════════════════════════ -->
<footer>
  <div class="footer-inner">
    <div>
      <div class="footer-brand">Campus Connect</div>
      <div class="footer-tag">Empowering lecturers to collaborate with students in a secure, streamlined learning environment.</div>
    </div>
    <div class="footer-links">
      <a>Features</a>
      <a>How it works</a>
      <a>Support</a>
      <a>Get started</a>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© 2026 Campus Connect. All rights reserved.</span>
    <span>Built to keep your campus aligned.</span>
  </div>
</footer>

<!-- ═══════════════════════════════════════
     JAVASCRIPT
═══════════════════════════════════════ -->
<script>
  /* ── DATA LAYER ── */
  const DB = {
    get: (k) => { try { return JSON.parse(localStorage.getItem('cc_' + k)) || null; } catch { return null; } },
    set: (k, v) => localStorage.setItem('cc_' + k, JSON.stringify(v)),
    del: (k) => localStorage.removeItem('cc_' + k)
  };
  const getUsers       = ()  => DB.get('users')        || [];
  const saveUsers      = u   => DB.set('users', u);
  const getCourses     = ()  => DB.get('courses')      || [];
  const saveCourses    = c   => DB.set('courses', c);
  const getCurrentUser = ()  => DB.get('current_user') || window.__SERVER_USER__;
  const setCurrentUser = u   => DB.set('current_user', u);
  const uid            = ()  => Date.now().toString(36) + Math.random().toString(36).slice(2);
  const PREVIEW_MAX_BYTES = 2 * 1024 * 1024; // limit PDF preview data stored in localStorage

  function safeSaveCourses(courses) {
    try {
      saveCourses(courses);
      return true;
    } catch (error) {
      if (error && error.name === 'QuotaExceededError') {
        return false;
      }
      throw error;
    }
  }

  /* ── STATE ── */
  let currentCourseId          = null;
  let pendingDeleteCourseId    = null;
  let selectedFile             = null;
  let selectedMaterialId       = null;
  let selectedViewerMaterialId = null;
  let sidebarOpen              = true;

  /* ── INIT ── */
  window.addEventListener('DOMContentLoaded', () => {
    const storedUser = DB.get('current_user');
    const user = getCurrentUser();
    if (!user || user.role !== 'lecturer') {
      window.location.href = 'login_updated.php';
      return;
    }
    if (!storedUser && window.__SERVER_USER__) {
      setCurrentUser(window.__SERVER_USER__);
    }
    document.body.style.visibility = 'visible';
    initUI(user);
    navigate('dashboard');
  });


  function initUI(user) {
    const initials = user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
    document.getElementById('nav-avatar').textContent   = initials;
    document.getElementById('nav-username').textContent = user.name.split(' ')[0];
  }

  /* ── SIDEBAR TOGGLE ── */
  function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    sb.classList.toggle('open');
  }

  /* ── NAVIGATE ── */
  function navigate(page) {
    const user = getCurrentUser();
    if (!user) { window.location.href = 'login_updated.php'; return; }

    ['dashboard', 'courses', 'profile'].forEach(p => {
      const el = document.getElementById('nav-' + p);
      if (el) el.classList.toggle('active', p === page);
    });

    if      (page === 'dashboard')     renderDashboard(user);
    else if (page === 'courses')       renderAllCourses();
    else if (page === 'create-course') openCreateCourse();
    else if (page === 'profile')       renderProfile(user);

    showPage(page);
  }

  function showPage(name) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    const el = document.getElementById('page-' + name);
    if (el) el.classList.add('active');
  }

  /* ── LOGOUT ── */
  function logout() {
    DB.del('current_user');
    window.location.href = 'logout.php';
  }

  /* ── DASHBOARD ── */
  function renderDashboard(user) {
    document.getElementById('dash-welcome').textContent = 'Welcome back, ' + user.name.split(' ')[0];
    const courses = getCourses().filter(c => c.ownerId === user.id);
    let totalStudents = 0, totalMaterials = 0;
    courses.forEach(c => {
      totalStudents  += (c.students  || []).length;
      totalMaterials += (c.materials || []).length;
    });
    document.getElementById('stat-courses').textContent   = courses.length;
    document.getElementById('stat-students').textContent  = totalStudents;
    document.getElementById('stat-materials').textContent = totalMaterials;
    document.getElementById('dash-course-count').textContent = courses.length + ' course' + (courses.length !== 1 ? 's' : '');

    const container = document.getElementById('dashboard-courses');
    if (courses.length === 0) {
      container.innerHTML = `
        <div class="empty-state" style="grid-column:1/-1;">
          <div class="empty-icon">📚</div>
          <p>You haven't created any courses yet.</p>
          <button class="btn btn-primary" onclick="navigate('create-course')">Create your first course</button>
        </div>`;
      return;
    }
    container.innerHTML = courses.map(courseCard).join('');
  }

  function courseCard(c) {
    const enrolled  = (c.students  || []).length;
    const materials = (c.materials || []).length;
    return `
      <div class="course-card">
        <div class="course-card-top">
          <div class="course-card-badge">${esc(c.code)}</div>
        </div>
        <div class="course-card-title">${esc(c.title)}</div>
        <div class="course-card-desc">${esc(c.description || 'No description provided.')}</div>
        <div class="course-card-meta">
          <span>👤 ${enrolled} student${enrolled !== 1 ? 's' : ''}</span>
          <span>📄 ${materials} material${materials !== 1 ? 's' : ''}</span>
        </div>
        <div class="course-card-actions">
          <button class="btn btn-secondary btn-sm" onclick="openCourseDetail('${c.id}')">View</button>
          <button class="btn btn-amber btn-sm"     onclick="openEditCourse('${c.id}')">Edit</button>
          <button class="btn btn-danger btn-sm"    onclick="openDeleteModal('${c.id}')">Delete</button>
        </div>
      </div>`;
  }

  /* ── ALL COURSES ── */
  function renderAllCourses() {
    filterCourses(document.getElementById('course-search')?.value || '');
  }

  function filterCourses(query) {
    const all  = getCourses();
    const q    = query.toLowerCase();
    const list = q ? all.filter(c => c.title.toLowerCase().includes(q) || c.code.toLowerCase().includes(q)) : all;

    const container = document.getElementById('courses-list');
    if (list.length === 0) {
      container.innerHTML = `<div class="empty-state" style="grid-column:1/-1;"><div class="empty-icon">🔍</div><p>No courses found.</p></div>`;
      return;
    }
    container.innerHTML = list.map(c => `
      <div class="course-card">
        <div class="course-card-top">
          <div class="course-card-badge">${esc(c.code)}</div>
        </div>
        <div class="course-card-title">${esc(c.title)}</div>
        <div class="course-card-desc">${esc(c.description || 'No description provided.')}</div>
        <div class="course-card-meta">
          <span>👤 ${(c.students||[]).length} enrolled</span>
          <span>📄 ${(c.materials||[]).length} materials</span>
        </div>
        <div class="course-card-actions">
          <button class="btn btn-secondary btn-sm" onclick="openCourseDetail('${c.id}')">View</button>
          <button class="btn btn-amber btn-sm"     onclick="openEditCourse('${c.id}')">Edit</button>
          <button class="btn btn-danger btn-sm"    onclick="openDeleteModal('${c.id}')">Delete</button>
        </div>
      </div>`).join('');
  }

  /* ── COURSE DETAIL ── */
  function openCourseDetail(id) {
    currentCourseId = id;
    renderCourseDetail();
    showPage('course-detail');
  }

  function renderCourseDetail() {
    closeMaterialViewer();
    const course = getCourses().find(c => c.id === currentCourseId);
    if (!course) return;
    document.getElementById('detail-title').textContent          = course.title;
    document.getElementById('detail-code').textContent           = course.code;
    document.getElementById('detail-desc').textContent           = course.description || '';
    document.getElementById('detail-enrolled').textContent       = '👤 ' + (course.students  || []).length + ' students enrolled';
    document.getElementById('detail-materials-count').textContent = '📄 ' + (course.materials || []).length + ' materials';
    document.getElementById('detail-actions').innerHTML = `
      <button class="btn btn-secondary btn-sm" onclick="navigate('courses')">← Back</button>
      <button class="btn btn-primary btn-sm"   onclick="openUploadMaterial('${course.id}')">+ Upload Material</button>
      <button class="btn btn-amber btn-sm"     onclick="openEditCourse('${course.id}')">Edit</button>
      <button class="btn btn-danger btn-sm"    onclick="openDeleteModal('${course.id}')">Delete</button>`;
    renderMaterialsTab(course);
    renderStudentsTab(course);
    switchTab('materials');
  }

  function renderMaterialsTab(course) {
    const mats = course.materials || [];
    const container = document.getElementById('materials-list');
    if (mats.length === 0) {
      container.innerHTML = `
        <div class="empty-state">
          <div class="empty-icon">📄</div>
          <p>No materials uploaded yet.</p>
          <button class="btn btn-primary" onclick="openUploadMaterial('${course.id}')">Upload First Material</button>
        </div>`;
      return;
    }
    container.innerHTML = mats.map(m => `
      <div class="material-item">
        <div class="mat-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
          </svg>
        </div>
        <div class="mat-info">
          <div class="mat-name">${esc(m.title)}</div>
          <div class="mat-meta">${esc(m.filename)} · ${m.uploadedAt}</div>
        </div>
        <div class="mat-actions">
          <button class="btn btn-primary btn-sm" onclick="viewMaterial('${m.id}')">View</button>
          <button class="btn btn-amber btn-sm" onclick="openReplaceMaterial('${course.id}','${m.id}')">Edit</button>
          <button class="btn btn-danger btn-sm" onclick="deleteMaterial('${m.id}')">Remove</button>
        </div>
      </div>`).join('');
  }

  function renderStudentsTab(course) {
    const studs = course.students || [];
    const container = document.getElementById('students-list');
    if (studs.length === 0) {
      container.innerHTML = `<div class="empty-state"><div class="empty-icon">👤</div><p>No students enrolled yet.</p></div>`;
      return;
    }
    const allUsers = getUsers();
    container.innerHTML = studs.map(sid => {
      const s = allUsers.find(u => u.id === sid);
      if (!s) return '';
      const initials = s.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
      return `
        <div class="student-item">
          <div class="stu-avatar">${initials}</div>
          <div>
            <div class="stu-name">${esc(s.name)}</div>
            <div class="stu-email">${esc(s.email)}</div>
          </div>
        </div>`;
    }).join('');
  }

  function switchTab(tab) {
    ['materials', 'students'].forEach(t => {
      document.getElementById('tab-' + t).classList.toggle('active', t === tab);
      document.getElementById('tab-content-' + t).classList.toggle('active', t === tab);
    });
  }

  /* ── CREATE / EDIT COURSE ── */
  function openCreateCourse() {
    document.getElementById('edit-course-id').value = '';
    document.getElementById('course-title').value   = '';
    document.getElementById('course-code').value    = '';
    document.getElementById('course-desc').value    = '';
    document.getElementById('course-form-title').textContent = 'Create New Course';
    document.getElementById('course-form-sub').textContent   = 'Set up a new course for students to enroll in';
    document.getElementById('save-course-btn').textContent   = 'Create Course';
  }

  function openEditCourse(id) {
    const course = getCourses().find(c => c.id === id);
    if (!course) return;
    document.getElementById('edit-course-id').value = id;
    document.getElementById('course-title').value   = course.title;
    document.getElementById('course-code').value    = course.code;
    document.getElementById('course-desc').value    = course.description || '';
    document.getElementById('course-form-title').textContent = 'Edit Course';
    document.getElementById('course-form-sub').textContent   = 'Update your course details';
    document.getElementById('save-course-btn').textContent   = 'Save Changes';
    showPage('create-course');
  }

  function saveCourse() {
    const user  = getCurrentUser();
    const title = document.getElementById('course-title').value.trim();
    const code  = document.getElementById('course-code').value.trim();
    const desc  = document.getElementById('course-desc').value.trim();
    if (!title || !code) { showToast('Title and code are required', 'error'); return; }

    const editId  = document.getElementById('edit-course-id').value;
    const courses = getCourses();

    if (editId) {
      const idx = courses.findIndex(c => c.id === editId);
      if (idx !== -1) {
        courses[idx].title = title; courses[idx].code = code; courses[idx].description = desc;
        saveCourses(courses);
        showToast('Course updated!', 'success');
        currentCourseId = editId;
        renderCourseDetail();
        showPage('course-detail');
      }
    } else {
      courses.push({ id: uid(), title, code, description: desc, ownerId: user.id, students: [], materials: [], createdAt: new Date().toLocaleDateString() });
      saveCourses(courses);
      showToast('Course created!', 'success');
      navigate('dashboard');
    }
  }

  /* ── DELETE COURSE ── */
  function openDeleteModal(id) {
    pendingDeleteCourseId = id;
    document.getElementById('delete-modal').classList.add('show');
  }
  function confirmDeleteCourse() {
    saveCourses(getCourses().filter(c => c.id !== pendingDeleteCourseId));
    closeModal('delete-modal');
    showToast('Course deleted', 'success');
    navigate('dashboard');
  }

  /* ── UPLOAD MATERIAL ── */
  function openUploadMaterial(courseId) {
    selectedMaterialId = null;
    document.getElementById('upload-course-id').value    = courseId;
    document.getElementById('material-title').value      = '';
    document.getElementById('file-selected').textContent = '';
    document.getElementById('upload-note').style.display = 'none';
    document.getElementById('upload-warning').style.display = 'none';
    selectedFile = null;
    const course = getCourses().find(c => c.id === courseId);
    document.getElementById('upload-sub').textContent = 'Adding resource for ' + (course ? course.code + ' — ' + course.title : '');
    showPage('upload-material');
  }

  function openReplaceMaterial(courseId, materialId) {
    selectedMaterialId = materialId;
    document.getElementById('upload-course-id').value    = courseId;
    const course = getCourses().find(c => c.id === courseId);
    const material = course?.materials?.find(m => m.id === materialId);
    if (!material) return;
    document.getElementById('material-title').value      = material.title;
    document.getElementById('file-selected').textContent = 'Current file: ' + material.filename;
    document.getElementById('upload-note').textContent   = 'Leave the file input empty to keep the current document.';
    document.getElementById('upload-note').style.display = 'block';
    document.getElementById('upload-warning').style.display = 'none';
    selectedFile = null;
    document.getElementById('upload-sub').textContent = 'Updating material for ' + (course ? course.code + ' — ' + course.title : '');
    showPage('upload-material');
  }

  function cancelUpload() {
    selectedMaterialId = null;
    document.getElementById('upload-note').style.display = 'none';
    document.getElementById('upload-warning').style.display = 'none';
    if (currentCourseId) { renderCourseDetail(); showPage('course-detail'); }
    else navigate('dashboard');
  }

  function handleDragOver(e)  { e.preventDefault(); document.getElementById('upload-zone').classList.add('dragover'); }
  function handleDragLeave()  { document.getElementById('upload-zone').classList.remove('dragover'); }
  function handleDrop(e) {
    e.preventDefault();
    document.getElementById('upload-zone').classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) { selectedFile = file; document.getElementById('file-selected').textContent = '✓ ' + file.name; }
  }
  function fileSelected(input) {
    if (input.files[0]) {
      selectedFile = input.files[0];
      document.getElementById('file-selected').textContent = '✓ ' + selectedFile.name;
      if (selectedFile.type === 'application/pdf' || selectedFile.name.toLowerCase().endsWith('.pdf')) {
        const previewWarning = document.getElementById('upload-warning');
        if (selectedFile.size > PREVIEW_MAX_BYTES) {
          previewWarning.textContent = 'This PDF is too large for in-dashboard preview. It will still upload, but preview may not be available.';
          previewWarning.style.display = 'block';
        } else {
          previewWarning.style.display = 'none';
        }
      } else {
        document.getElementById('upload-warning').style.display = 'none';
      }
    }
  }

  async function uploadMaterial() {
    const title    = document.getElementById('material-title').value.trim();
    const courseId = document.getElementById('upload-course-id').value;
    if (!title) { showToast('Please enter a title', 'error'); return; }
    if (!selectedFile && !selectedMaterialId) { showToast('Please select a file', 'error'); return; }

    const courses = getCourses();
    const idx = courses.findIndex(c => c.id === courseId);
    if (idx === -1) return;
    courses[idx].materials = courses[idx].materials || [];

    if (selectedMaterialId) {
      const material = courses[idx].materials.find(m => m.id === selectedMaterialId);
      if (!material) return;
      material.title = title;
      if (selectedFile) {
        material.filename   = selectedFile.name;
        material.size       = selectedFile.size;
        material.uploadedAt = new Date().toLocaleDateString();
        if (selectedFile.type === 'application/pdf' || selectedFile.name.toLowerCase().endsWith('.pdf')) {
          if (selectedFile.size <= PREVIEW_MAX_BYTES) {
            material.fileData    = await readFileAsDataURL(selectedFile);
            material.contentType = selectedFile.type || 'application/pdf';
          } else {
            delete material.fileData;
            delete material.contentType;
            showToast('PDF is too large for preview, but material was updated.', 'info');
          }
        } else {
          delete material.fileData;
          delete material.contentType;
        }
      }
      if (!safeSaveCourses(courses)) {
        delete material.fileData;
        delete material.contentType;
        safeSaveCourses(courses);
        showToast('Material updated, but PDF preview data was skipped because storage is full.', 'info');
      } else {
        showToast('Material updated!', 'success');
      }
      selectedMaterialId = null;
      document.getElementById('upload-note').style.display = 'none';
    } else {
      const material = { id: uid(), title, filename: selectedFile.name, size: selectedFile.size, uploadedAt: new Date().toLocaleDateString() };
      if (selectedFile.type === 'application/pdf' || selectedFile.name.toLowerCase().endsWith('.pdf')) {
        if (selectedFile.size <= PREVIEW_MAX_BYTES) {
          material.fileData    = await readFileAsDataURL(selectedFile);
          material.contentType = selectedFile.type || 'application/pdf';
        } else {
          showToast('PDF is too large for preview, but material was uploaded.', 'info');
        }
      }
      courses[idx].materials.push(material);
      if (!safeSaveCourses(courses)) {
        material.fileData && delete material.fileData;
        material.contentType && delete material.contentType;
        safeSaveCourses(courses);
        showToast('Material uploaded, but preview data was skipped because local storage is full.', 'info');
      } else {
        showToast('Material uploaded!', 'success');
      }
    }

    currentCourseId = courseId;
    renderCourseDetail();
    showPage('course-detail');
  }

  function deleteMaterial(materialId) {
    const courses = getCourses();
    const idx = courses.findIndex(c => c.id === currentCourseId);
    if (idx === -1) return;
    courses[idx].materials = (courses[idx].materials || []).filter(m => m.id !== materialId);
    saveCourses(courses);
    closeMaterialViewer();
    showToast('Material removed');
    renderCourseDetail();
  }

  function viewMaterial(materialId) {
    const course = getCourses().find(c => c.id === currentCourseId);
    const material = course?.materials?.find(m => m.id === materialId);
    if (!material) return;
    if (!material.filename.toLowerCase().endsWith('.pdf')) {
      showToast('PDF preview is only available for PDF materials.', 'error');
      return;
    }
    if (!material.fileData) {
      showToast('PDF preview not available for this file. Please replace it with a PDF file.', 'error');
      return;
    }
    selectedViewerMaterialId = materialId;
    document.getElementById('viewer-title').textContent = material.title || material.filename;
    document.getElementById('viewer-sub').textContent   = material.filename + ' · ' + material.uploadedAt;
    const viewer = document.getElementById('pdf-viewer');
    viewer.data = material.fileData;
    viewer.setAttribute('type', material.contentType || 'application/pdf');
    document.getElementById('material-viewer').style.display = 'block';
  }

  function closeMaterialViewer() {
    selectedViewerMaterialId = null;
    document.getElementById('material-viewer').style.display = 'none';
    const viewer = document.getElementById('pdf-viewer');
    viewer.data = '';
  }

  function openMaterialInNewTab() {
    const course = getCourses().find(c => c.id === currentCourseId);
    const material = course?.materials?.find(m => m.id === selectedViewerMaterialId);
    if (!material || !material.fileData) {
      showToast('PDF not available to open in a new tab.', 'error');
      return;
    }
    window.open(material.fileData, '_blank');
  }

  function readFileAsDataURL(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onload  = () => resolve(reader.result);
      reader.onerror = () => reject(reader.error);
      reader.readAsDataURL(file);
    });
  }

  /* ── PROFILE ── */
  function renderProfile(user) {
    const initials = user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
    document.getElementById('profile-avatar').textContent = initials;
    document.getElementById('profile-name').textContent   = user.name;
    document.getElementById('pf-name').textContent        = user.name;
    document.getElementById('pf-email').textContent       = user.email;
    document.getElementById('pf-role').textContent        = 'Lecturer / Faculty';
    document.getElementById('pf-since').textContent       = user.since || 'N/A';
    const courses = getCourses().filter(c => c.ownerId === user.id);
    document.getElementById('pf-courses').textContent = courses.length + ' course' + (courses.length !== 1 ? 's' : 's');
  }

  function openProfileEditor() {
    const user = getCurrentUser();
    if (!user) return;
    document.getElementById('edit-profile-name').value  = user.name;
    document.getElementById('edit-profile-email').value = user.email;
    document.getElementById('profile-edit-form').style.display = 'block';
  }

  function closeProfileEditor() {
    document.getElementById('profile-edit-form').style.display = 'none';
  }

  function saveProfile() {
    const name  = document.getElementById('edit-profile-name').value.trim();
    const email = document.getElementById('edit-profile-email').value.trim();
    if (!name || !email) {
      showToast('Full name and email are required.', 'error');
      return;
    }

    const user = getCurrentUser();
    if (!user) return;

    user.name  = name;
    user.email = email;

    window.__SERVER_USER__ = user;
    setCurrentUser(user);
    renderProfile(user);
    initUI(user);
    showToast('Profile updated successfully.', 'success');
    closeProfileEditor();
  }

  /* ── MODAL ── */
  function closeModal(id) { document.getElementById(id).classList.remove('show'); }

  /* ── TOAST ── */
  function showToast(msg, type) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast' + (type ? ' ' + type : '');
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
  }

  /* ── ESC ── */
  function esc(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }
</script>
</body>
</html>