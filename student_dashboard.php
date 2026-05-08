<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login_updated.php");
    exit();
}

$name   = $_SESSION['user_name']  ?? 'Student';
$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Campus Connect - Student Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:           #f5f4f0;
      --surface:      #ffffff;
      --border:       #e2e0d8;
      --primary:      #2d5be3;
      --primary-dark: #1e44c8;
      --primary-light:#edf0fc;
      --text:         #1a1a2e;
      --muted:        #7a7888;
      --nav-h:        68px;
      --radius:       14px;
      --shadow:       0 2px 16px rgba(0,0,0,0.07);
    }

    body { font-family:'Sora',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; display:flex; flex-direction:column; }

    nav {
      height:var(--nav-h); background:var(--surface); border-bottom:1px solid var(--border);
      display:flex; align-items:center; padding:0 40px; position:sticky; top:0; z-index:100;
    }
    .logo { display:flex; align-items:center; gap:10px; font-weight:700; font-size:1.05rem; color:var(--text); text-decoration:none; }
    .logo-icon { width:38px; height:38px; background:var(--primary); border-radius:10px; display:grid; place-items:center; color:#fff; font-weight:700; font-size:0.85rem; flex-shrink:0; }
    .nav-links { margin-left:auto; display:flex; align-items:center; gap:6px; list-style:none; }
    .nav-links a { text-decoration:none; color:var(--muted); font-size:0.88rem; font-weight:500; padding:7px 14px; border-radius:8px; transition:background 0.18s,color 0.18s; cursor:pointer; }
    .nav-links a:hover, .nav-links a.active { background:var(--primary-light); color:var(--primary); }
    .nav-links .logout a { color:#c0392b; }
    .nav-links .logout a:hover { background:#fdecea; }

    main { flex:1; max-width:1140px; width:100%; margin:0 auto; padding:52px 32px 60px; }

    .welcome-block { margin-bottom:40px; animation:fadeUp 0.5s ease both; }
    .welcome-block h1 { font-size:2rem; font-weight:700; letter-spacing:-0.03em; }
    .welcome-block p  { margin-top:6px; color:var(--muted); font-size:0.92rem; }

    .stats-row { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:18px; margin-bottom:44px; animation:fadeUp 0.5s 0.1s ease both; }
    .stat-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:28px 26px; box-shadow:var(--shadow); position:relative; overflow:hidden; transition:transform 0.18s,box-shadow 0.18s; }
    .stat-card:hover { transform:translateY(-2px); box-shadow:0 6px 28px rgba(0,0,0,0.10); }
    .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--primary); border-radius:var(--radius) var(--radius) 0 0; }
    .stat-label { font-size:0.78rem; font-weight:600; text-transform:uppercase; letter-spacing:0.08em; color:var(--muted); margin-bottom:10px; }
    .stat-value { font-size:2.4rem; font-weight:700; font-family:'DM Mono',monospace; color:var(--text); line-height:1; }

    /* Tab strip */
    .tab-strip { display:flex; gap:4px; margin-bottom:28px; background:var(--surface); border:1px solid var(--border); border-radius:10px; padding:4px; width:fit-content; }
    .tab-strip button { padding:9px 22px; border-radius:8px; border:none; background:transparent; font-family:'Sora',sans-serif; font-size:0.86rem; font-weight:500; color:var(--muted); cursor:pointer; transition:all 0.18s; }
    .tab-strip button.active { background:var(--primary); color:#fff; box-shadow:0 2px 8px rgba(45,91,227,0.25); }

    .section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; }
    .section-header h2 { font-size:1.15rem; font-weight:700; letter-spacing:-0.02em; }
    .search-bar { padding:8px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:0.85rem; font-family:'Sora',sans-serif; outline:none; width:220px; background:var(--surface); color:var(--text); transition:border-color 0.18s; }
    .search-bar:focus { border-color:var(--primary); }
    .search-bar::placeholder { color:var(--muted); }

    /* Empty state */
    .empty-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:72px 32px; display:flex; flex-direction:column; align-items:center; gap:20px; box-shadow:var(--shadow); }
    .empty-icon { width:72px; height:72px; background:var(--primary-light); border-radius:50%; display:grid; place-items:center; }
    .empty-icon svg { color:var(--primary); }
    .empty-card p { color:var(--muted); font-size:0.95rem; text-align:center; }

    /* Course grid — NO display:none here (was the root bug) */
    .course-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:18px; }

    .course-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow); transition:transform 0.18s,box-shadow 0.18s; display:flex; flex-direction:column; }
    .course-card:hover { transform:translateY(-3px); box-shadow:0 8px 32px rgba(0,0,0,0.10); }

    .course-thumb { height:110px; background:linear-gradient(135deg,var(--primary) 0%,#5b8cff 100%); display:grid; place-items:center; font-size:2rem; position:relative; }
    .enrolled-badge { position:absolute; top:10px; right:10px; font-size:0.7rem; font-weight:600; padding:3px 10px; border-radius:99px; background:rgba(255,255,255,0.25); color:#fff; backdrop-filter:blur(4px); }

    .course-body { padding:18px 20px; flex:1; display:flex; flex-direction:column; gap:4px; }
    .course-body h3 { font-size:0.97rem; font-weight:600; }
    .course-code { font-size:0.78rem; color:var(--primary); font-weight:600; }
    .course-desc { font-size:0.82rem; color:var(--muted); flex:1; line-height:1.5; }
    .course-meta { display:flex; gap:12px; font-size:0.75rem; color:var(--muted); margin-top:6px; }
    .progress-bar { margin-top:10px; height:5px; background:var(--border); border-radius:99px; overflow:hidden; }
    .progress-fill { height:100%; background:var(--primary); border-radius:99px; }

    .card-actions { display:flex; gap:8px; margin-top:14px; }

    .btn-enroll { flex:1; padding:9px; background:var(--primary); color:#fff; border:none; border-radius:8px; font-family:'Sora',sans-serif; font-size:0.84rem; font-weight:600; cursor:pointer; transition:background 0.18s; }
    .btn-enroll:hover { background:var(--primary-dark); }

    .btn-unenroll { flex:1; padding:9px; background:#fee2e2; color:#dc2626; border:none; border-radius:8px; font-family:'Sora',sans-serif; font-size:0.84rem; font-weight:600; cursor:pointer; }
    .btn-unenroll:hover { background:#fecaca; }

    .btn-mats { flex:1; padding:9px; background:var(--primary-light); color:var(--primary); border:none; border-radius:8px; font-family:'Sora',sans-serif; font-size:0.84rem; font-weight:600; cursor:pointer; }
    .btn-mats:hover { background:#dbeafe; }

    /* Materials panel — collapsed by default */
    .materials-panel { display:none; margin-top:10px; border-top:1px solid var(--border); padding-top:10px; flex-direction:column; gap:6px; }
    .materials-panel.open { display:flex; }
    .mat-row { display:flex; align-items:center; gap:10px; padding:8px 10px; background:var(--bg); border-radius:8px; font-size:0.82rem; }
    .mat-row span { color:var(--muted); font-size:0.75rem; margin-left:auto; }

    .btn-primary { display:inline-flex; align-items:center; gap:8px; background:var(--primary); color:#fff; border:none; border-radius:9px; padding:13px 26px; font-family:'Sora',sans-serif; font-size:0.9rem; font-weight:600; cursor:pointer; transition:background 0.18s,transform 0.15s; box-shadow:0 2px 12px rgba(45,91,227,0.25); }
    .btn-primary:hover { background:var(--primary-dark); transform:translateY(-1px); }

    .toast { position:fixed; bottom:28px; right:28px; background:#1a1a2e; color:#fff; padding:14px 22px; border-radius:10px; font-size:0.87rem; font-weight:500; box-shadow:0 8px 32px rgba(0,0,0,0.25); transform:translateY(80px); opacity:0; transition:transform 0.3s ease,opacity 0.3s ease; z-index:300; pointer-events:none; }
    .toast.show    { transform:translateY(0); opacity:1; }
    .toast.success { background:#059669; }
    .toast.error   { background:#dc2626; }

    footer { background:var(--surface); border-top:1px solid var(--border); padding:40px; }
    .footer-inner { max-width:1140px; margin:0 auto; display:flex; align-items:flex-start; justify-content:space-between; gap:32px; flex-wrap:wrap; }
    .footer-brand p { max-width:280px; font-size:0.82rem; color:var(--muted); margin-top:8px; line-height:1.6; }
    .footer-links { display:flex; gap:40px; flex-wrap:wrap; }
    .footer-links a { text-decoration:none; color:var(--muted); font-size:0.85rem; transition:color 0.18s; }
    .footer-links a:hover { color:var(--primary); }

    @keyframes fadeUp { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }

    @media(max-width:600px) {
      nav{padding:0 18px} main{padding:36px 18px 48px}
      .welcome-block h1{font-size:1.5rem} footer{padding:32px 18px}
      .footer-inner{flex-direction:column}
    }
  </style>
</head>
<body>

<nav>
  <a href="#" class="logo"><div class="logo-icon">CC</div> Campus Connect</a>
  <ul class="nav-links">
    <li><a class="active" onclick="showTab('my')">Dashboard</a></li>
    <li><a onclick="showTab('browse')">Browse Courses</a></li>
    <li><a href="#">Profile</a></li>
    <li class="logout"><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<main>
  <div class="welcome-block">
    <h1>Welcome, <?= htmlspecialchars($name) ?> 👋</h1>
    <p>Here's an overview of your learning activity</p>
  </div>

  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-label">Enrolled Courses</div>
      <div class="stat-value" id="stat-enrolled">0</div>
    </div>
    <div class="stat-card" style="--primary:#16a085;">
      <div class="stat-label">Total Available</div>
      <div class="stat-value" id="stat-available">0</div>
    </div>
    <div class="stat-card" style="--primary:#e67e22;">
      <div class="stat-label">Materials</div>
      <div class="stat-value" id="stat-materials">0</div>
    </div>
  </div>

  <div class="tab-strip">
    <button id="btn-tab-my"     class="active" onclick="showTab('my')">My Courses</button>
    <button id="btn-tab-browse"               onclick="showTab('browse')">Browse All</button>
  </div>

  <!-- MY COURSES -->
  <div id="view-my">
    <div class="section-header"><h2>My Enrolled Courses</h2></div>
    <div class="empty-card" id="empty-my">
      <div class="empty-icon">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
        </svg>
      </div>
      <p>You haven't enrolled in any courses yet.</p>
      <button class="btn-primary" onclick="showTab('browse')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        Browse Available Courses
      </button>
    </div>
    <div class="course-grid" id="grid-my"></div>
  </div>

  <!-- BROWSE ALL -->
  <div id="view-browse" style="display:none;">
    <div class="section-header">
      <h2>All Available Courses</h2>
      <input class="search-bar" id="search-input" placeholder="Search by title or code…" oninput="renderBrowse()">
    </div>
    <div class="empty-card" id="empty-browse" style="display:none;">
      <div class="empty-icon">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
      </div>
      <p>No courses have been created by lecturers yet.<br>Check back soon!</p>
    </div>
    <div class="course-grid" id="grid-browse"></div>
  </div>
</main>

<footer>
  <div class="footer-inner">
    <div class="footer-brand">
      <a href="#" class="logo"><div class="logo-icon">CC</div> Campus Connect</a>
      <p>Empowering departments to collaborate with students in a secure, streamlined learning environment.</p>
    </div>
    <div class="footer-links">
      <a href="#">Features</a><a href="#">How it works</a><a href="#">Stories</a><a href="#">Get started</a>
    </div>
  </div>
</footer>

<div class="toast" id="toast"></div>

<script>
  const STUDENT_ID = '<?= addslashes($userId) ?>';

  /* ── Data helpers ── */
  function getAllCourses() {
    // Reads cc_courses — the exact key the lecturer dashboard writes to
    try { return JSON.parse(localStorage.getItem('cc_courses')) || []; }
    catch { return []; }
  }

  function saveAllCourses(arr) {
    localStorage.setItem('cc_courses', JSON.stringify(arr));
  }

  function getEnrolledIds() {
    // Per-student enrolled course ID list
    try { return JSON.parse(localStorage.getItem('cc_enrolled_' + STUDENT_ID)) || []; }
    catch { return []; }
  }

  function saveEnrolledIds(ids) {
    localStorage.setItem('cc_enrolled_' + STUDENT_ID, JSON.stringify(ids));
  }

  function isEnrolled(courseId) {
    return getEnrolledIds().includes(courseId);
  }

  /* ── Enroll ── */
  function enroll(courseId) {
    const courses = getAllCourses();
    const course  = courses.find(c => c.id === courseId);
    if (!course) return;

    // Save course ID to student's list
    const ids = getEnrolledIds();
    if (!ids.includes(courseId)) {
      ids.push(courseId);
      saveEnrolledIds(ids);
    }

    // Push student ID into course.students[] so lecturer sees it
    const idx = courses.findIndex(c => c.id === courseId);
    if (idx !== -1) {
      courses[idx].students = courses[idx].students || [];
      if (!courses[idx].students.includes(STUDENT_ID)) {
        courses[idx].students.push(STUDENT_ID);
        saveAllCourses(courses);
      }
    }

    showToast('✅ Enrolled in "' + esc(course.title) + '"!', 'success');
    renderAll();
  }

  /* ── Unenroll ── */
  function unenroll(courseId) {
    const courses = getAllCourses();
    saveEnrolledIds(getEnrolledIds().filter(id => id !== courseId));
    const idx = courses.findIndex(c => c.id === courseId);
    if (idx !== -1) {
      courses[idx].students = (courses[idx].students || []).filter(s => s !== STUDENT_ID);
      saveAllCourses(courses);
    }
    showToast('Unenrolled successfully');
    renderAll();
  }

  /* ── Stats ── */
  function updateStats() {
    const all         = getAllCourses();
    const enrolledIds = getEnrolledIds();
    let totalMats = 0;
    enrolledIds.forEach(id => {
      const c = all.find(c => c.id === id);
      if (c) totalMats += (c.materials || []).length;
    });
    document.getElementById('stat-enrolled').textContent  = enrolledIds.length;
    document.getElementById('stat-available').textContent = all.length;
    document.getElementById('stat-materials').textContent = totalMats;
  }

  /* ── My Courses ──
     KEY FIX: always read LIVE course objects from cc_courses
     instead of storing a snapshot at enrollment time.
     This means materials added later by lecturer appear immediately. */
  function renderMyCourses() {
    const all         = getAllCourses();
    const enrolledIds = getEnrolledIds();

    // Map enrolled IDs → live course objects (filter out deleted courses)
    const enrolled = enrolledIds.map(id => all.find(c => c.id === id)).filter(Boolean);

    const grid  = document.getElementById('grid-my');
    const empty = document.getElementById('empty-my');

    if (enrolled.length === 0) {
      empty.style.display = 'flex';
      grid.innerHTML = '';
      return;
    }
    empty.style.display = 'none';
    grid.innerHTML = enrolled.map(c => buildCard(c, true)).join('');
  }

  /* ── Browse All ── */
  function renderBrowse() {
    const all   = getAllCourses();
    const query = (document.getElementById('search-input')?.value || '').toLowerCase().trim();
    const list  = query
      ? all.filter(c =>
          c.title.toLowerCase().includes(query) ||
          c.code.toLowerCase().includes(query)  ||
          (c.description || '').toLowerCase().includes(query))
      : all;

    const grid  = document.getElementById('grid-browse');
    const empty = document.getElementById('empty-browse');

    if (list.length === 0) {
      empty.style.display = 'flex';
      grid.innerHTML = '';
      return;
    }
    empty.style.display = 'none';
    grid.innerHTML = list.map(c => buildCard(c, isEnrolled(c.id))).join('');
  }

  /* ── Build card HTML ── */
  function buildCard(c, enrolled) {
    const mats  = c.materials || [];
    const studs = (c.students || []).length;
    const pid   = 'panel-' + esc(c.id);

    const matsHTML = mats.length
      ? mats.map(m => {
          const isPDF   = m.filename && m.filename.toLowerCase().endsWith('.pdf');
          const hasData = !!m.fileData;
          const openBtn = (isPDF && hasData)
            ? `<button onclick="openMaterial('${esc(m.id)}','${esc(c.id)}')"
                 style="margin-left:auto;padding:5px 12px;background:var(--primary);color:#fff;
                        border:none;border-radius:6px;font-size:0.75rem;font-family:'Sora',sans-serif;
                        font-weight:600;cursor:pointer;white-space:nowrap;">Open PDF</button>`
            : `<span style="margin-left:auto;color:var(--muted);font-size:0.72rem;white-space:nowrap;">
                 ${isPDF ? 'Too large to preview' : 'No preview'}</span>`;
          return `
            <div class="mat-row">
              📄 <strong>${esc(m.title)}</strong>
              <span style="color:var(--muted);font-size:0.72rem;">${esc(m.filename)}</span>
              ${openBtn}
            </div>`;
        }).join('')
      : '<div class="mat-row" style="color:var(--muted);">No materials uploaded yet.</div>';

    const actions = enrolled
      ? `<div class="card-actions">
           <button class="btn-mats"     onclick="togglePanel('${esc(c.id)}')">📂 Materials (${mats.length})</button>
           <button class="btn-unenroll" onclick="unenroll('${esc(c.id)}')">Unenroll</button>
         </div>`
      : `<div class="card-actions">
           <button class="btn-enroll" onclick="enroll('${esc(c.id)}')">Enroll Now</button>
         </div>`;

    return `
      <div class="course-card">
        <div class="course-thumb">
          <span>📚</span>
          ${enrolled ? '<div class="enrolled-badge">✓ Enrolled</div>' : ''}
        </div>
        <div class="course-body">
          <div class="course-code">${esc(c.code)}</div>
          <h3>${esc(c.title)}</h3>
          <p class="course-desc">${esc(c.description || 'No description provided.')}</p>
          <div class="course-meta">
            <span>📄 ${mats.length} material${mats.length !== 1 ? 's' : ''}</span>
            <span>👤 ${studs} student${studs !== 1 ? 's' : ''}</span>
          </div>
          ${enrolled ? '<div class="progress-bar"><div class="progress-fill" style="width:0%;"></div></div>' : ''}
          ${actions}
          <div class="materials-panel" id="${pid}">${matsHTML}</div>
        </div>
      </div>`;
  }

  /* ── Toggle materials panel ── */
  function togglePanel(courseId) {
    const p = document.getElementById('panel-' + courseId);
    if (p) p.classList.toggle('open');
  }

  /* ── Open PDF material in new tab ── */
  function openMaterial(materialId, courseId) {
    const courses  = getAllCourses();
    const course   = courses.find(c => c.id === courseId);
    if (!course) return;
    const material = (course.materials || []).find(m => m.id === materialId);
    if (!material) return;

    if (!material.fileData) {
      showToast('No preview available. Ask your lecturer to re-upload it.', 'error');
      return;
    }

    const win = window.open('', '_blank');
    win.document.write(`
      <!DOCTYPE html><html>
      <head>
        <title>${material.title}</title>
        <style>
          *{margin:0;padding:0;box-sizing:border-box;}
          body{background:#1a1a1a;display:flex;flex-direction:column;height:100vh;font-family:sans-serif;}
          .bar{background:#2d5be3;color:white;padding:12px 20px;flex-shrink:0;}
          .bar-title{font-size:0.95rem;font-weight:600;}
          .bar-meta{font-size:0.78rem;opacity:0.7;margin-top:2px;}
          embed{flex:1;width:100%;border:none;}
        </style>
      </head>
      <body>
        <div class="bar">
          <div class="bar-title">${material.title}</div>
          <div class="bar-meta">${material.filename} &middot; Uploaded ${material.uploadedAt}</div>
        </div>
        <embed src="${material.fileData}" type="application/pdf" width="100%" style="height:calc(100vh - 58px);">
      </body></html>
    `);
    win.document.close();
  }

  /* ── Tabs ── */
  function showTab(tab) {
    document.getElementById('view-my').style.display     = tab === 'my'     ? '' : 'none';
    document.getElementById('view-browse').style.display = tab === 'browse' ? '' : 'none';
    document.getElementById('btn-tab-my').classList.toggle('active',     tab === 'my');
    document.getElementById('btn-tab-browse').classList.toggle('active', tab === 'browse');
    if (tab === 'browse') renderBrowse();
  }

  /* ── Render all ── */
  function renderAll() {
    updateStats();
    renderMyCourses();
    if (document.getElementById('view-browse').style.display !== 'none') renderBrowse();
  }

  /* ── Toast ── */
  function showToast(msg, type) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className   = 'toast' + (type ? ' ' + type : '');
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
  }

  /* ── Escape ── */
  function esc(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  /* ── Init ── */
  renderAll();
</script>
</body>
</html>