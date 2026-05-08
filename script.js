// Initialize AOS animations
AOS.init({
    duration: 800,
    once: true,
    offset: 100
});

// Branch Data Structure (Supports all departments)
let branchData = {
    // Computer Science
    "btech-cs": { notes: [], department: "Computer Science", course: "B.Tech CSE" },
    "bca": { notes: [], department: "Computer Science", course: "BCA" },
    // Management
    "bcom": { notes: [], department: "Management", course: "B.Com" },
    "bba": { notes: [], department: "Management", course: "BBA" },
    // Law
    "llb": { notes: [], department: "Law", course: "LL.B" },
    "ba-llb": { notes: [], department: "Law", course: "BA.LL.B" }
};

// Load data from localStorage
function loadBranchData() {
    const stored = localStorage.getItem("campus_branch_data_v2");
    if (stored) {
        try {
            const parsed = JSON.parse(stored);
            Object.keys(branchData).forEach(key => {
                if (parsed[key]) {
                    branchData[key] = parsed[key];
                }
            });
        } catch (e) { }
    } else {
        // Add sample data
        branchData["btech-cs"].notes = [
            { title: "Data Structures", subject: "CS301 - Semester 3", professor: "Dr. Rajesh Kumar", fileName: "ds_notes.pdf", date: new Date().toLocaleDateString() }
        ];
        // branchData["btech-cs"].notices = [
        //     { title: "Mid Semester Exams", description: "Mid sem exams from 20th April 2026", date: new Date().toLocaleDateString() }
        // ];
        saveBranchData();
    }
}

function saveBranchData() {
    localStorage.setItem("campus_branch_data_v2", JSON.stringify(branchData));
}

// Helper function for file download
function downloadFile(content, filename) {
    const blob = new Blob([content], { type: "application/octet-stream" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
    URL.revokeObjectURL(link.href);
}

let currentBranch = "btech-cs";
let currentRole = "student";

// Render branch UI
function renderBranchUI() {
    const data = branchData[currentBranch];
    const branchDisplay = `${data.department} - ${data.course}`;
    document.getElementById("branchTitle").innerHTML = `<i class="fas fa-building me-2"></i>${branchDisplay}`;

    // Render Notes
    const notesContainer = document.getElementById("notesListContainer");
    if (data.notes.length === 0) {
        notesContainer.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-inbox fa-3x mb-2 opacity-50"></i><br>No notes uploaded yet</div>';
    } else {
        notesContainer.innerHTML = data.notes.map((note, idx) => `
            <div class="note-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1"><i class="fas fa-file-pdf text-danger me-2"></i>${escapeHtml(note.title)}</h6>
                        <div class="small text-secondary mb-1">
                            <i class="fas fa-book me-1"></i>${escapeHtml(note.subject)} 
                            <i class="fas fa-user-graduate ms-2 me-1"></i>${escapeHtml(note.professor)}
                        </div>
                        <div class="small text-muted"><i class="fas fa-calendar-alt me-1"></i>${note.date || 'Recent'}</div>
                    </div>
<div class="d-flex gap-2">
    <button class="btn btn-sm btn-primary download-note-btn" data-index="${idx}">
        <i class="fas fa-download"></i>
    </button>

    ${currentRole === "lecturer" ? `
    <button class="btn btn-sm btn-warning edit-note-btn" data-index="${idx}">
        <i class="fas fa-edit"></i>
    </button>
    <button class="btn btn-sm btn-danger delete-note-btn" data-index="${idx}">
        <i class="fas fa-trash"></i>
    </button>
    ` : ""}
</div>

                </div>
            </div>
        `).join('');

        // Attach download events
        document.querySelectorAll('.download-note-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const idx = parseInt(btn.getAttribute('data-index'));
                const note = data.notes[idx];
                downloadFile(`This is ${note.title} - ${note.subject} by ${note.professor}\n\nDemo educational content.`, `${note.title.replace(/[^a-z0-9]/gi, '_')}.pdf`);
            });
        });
    }

    // Delete Note
    document.querySelectorAll('.delete-note-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const idx = parseInt(btn.getAttribute('data-index'));

            if (confirm("Are you sure you want to delete this note?")) {
                branchData[currentBranch].notes.splice(idx, 1);
                saveBranchData();
                renderBranchUI();
            }
        });
    });

    // Edit Note
    document.querySelectorAll('.edit-note-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const idx = parseInt(btn.getAttribute('data-index'));
            const note = branchData[currentBranch].notes[idx];

            document.getElementById("noteTitle").value = note.title;
            document.getElementById("noteSubject").value = note.subject;
            document.getElementById("professorName").value = note.professor;

            branchData[currentBranch].notes.splice(idx, 1);

            saveBranchData();
            renderBranchUI();

            alert("✏️ Now edit and click Upload");
        });
    });


    // Render Notices
    //  const noticesContainer = document.getElementById("noticesListContainer");
    // if(data.notices.length === 0) {
    //     noticesContainer.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-envelope-open-text fa-3x mb-2 opacity-50"></i><br>No notices posted yet</div>';
    // } else {
    //     noticesContainer.innerHTML = data.notices.map((notice, idx) => `
    //         <div class="notice-card">
    //             <div class="fw-bold mb-2"><i class="fas fa-bullhorn text-warning me-2"></i>${escapeHtml(notice.title)}</div>
    //             <div class="small text-secondary mb-2">${escapeHtml(notice.description)}</div>
    //             <div class="small text-muted"><i class="fas fa-calendar-alt me-1"></i>${notice.date || 'Recent'}</div>
    //         </div>
    //     `).join('');
    //  }

    // Toggle lecturer panel
    const lecturerPanel = document.getElementById("lecturerPanel");
    lecturerPanel.style.display = currentRole === "lecturer" ? "block" : "none";
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function (m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Upload Note
function uploadNote() {
    const file = document.getElementById("noteFile").files[0];
    const title = document.getElementById("noteTitle").value.trim();
    const subject = document.getElementById("noteSubject").value.trim();
    const professor = document.getElementById("professorName").value.trim();

    if (!file) { alert("Please select a file to upload."); return; }
    if (!title) { alert("Please enter subject name."); return; }
    if (!subject) { alert("Please enter subject code/semester."); return; }
    if (!professor) { alert("Please enter professor name."); return; }

    const newNote = {
        title: title,
        subject: subject,
        professor: professor,
        fileName: file.name,
        date: new Date().toLocaleDateString()
    };

    branchData[currentBranch].notes.push(newNote);
    saveBranchData();
    renderBranchUI();

    // Clear form
    document.getElementById("noteFile").value = "";
    document.getElementById("noteTitle").value = "";
    document.getElementById("noteSubject").value = "";
    document.getElementById("professorName").value = "";

    alert("✅ Note uploaded successfully!");
}

// Post Notice
// function postNotice() {
//     const title = document.getElementById("noticeTitle").value.trim();
//     const desc = document.getElementById("noticeDesc").value.trim();

//     if(!title) { alert("Please enter notice headline."); return; }

//     const newNotice = {
//         title: title,
//         description: desc || "No additional details",
//         date: new Date().toLocaleDateString()
//     };

//     branchData[currentBranch].notices.push(newNotice);
//     saveBranchData();
//     renderBranchUI();

//     document.getElementById("noticeTitle").value = "";
//     document.getElementById("noticeDesc").value = "";

//     alert("📢 Notice posted successfully!");
// }

// Show branch dashboard
function showBranchDashboard(branchKey) {
    currentBranch = branchKey;
    document.getElementById("homePageContent").style.display = "none";
    document.getElementById("globalFooter").style.display = "none";
    document.getElementById("branchDashboard").style.display = "block";

    currentRole = "student";
    document.getElementById("roleStudentBtn").classList.add("btn-primary");
    document.getElementById("roleStudentBtn").classList.remove("btn-outline-secondary");
    document.getElementById("roleLecturerBtn").classList.add("btn-outline-primary");
    document.getElementById("roleLecturerBtn").classList.remove("btn-primary");

    renderBranchUI();
    window.scrollTo(0, 0);
}

function showHomePage() {
    document.getElementById("homePageContent").style.display = "block";
    document.getElementById("globalFooter").style.display = "block";
    document.getElementById("branchDashboard").style.display = "none";
    window.scrollTo(0, 0);
}

// Initialize Bootstrap Modal
let loginModal;

document.addEventListener("DOMContentLoaded", function () {
    loginModal = new bootstrap.Modal(document.getElementById('loginModal'));

    // Course branch links
    document.querySelectorAll(".course-branch-link").forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            const branch = link.getAttribute("data-branch");
            if (branch) showBranchDashboard(branch);
        });
    });

    // Role toggles
    document.getElementById("roleLecturerBtn").addEventListener("click", () => {
        currentRole = "lecturer";
        renderBranchUI();
        document.getElementById("roleLecturerBtn").classList.add("btn-primary");
        document.getElementById("roleLecturerBtn").classList.remove("btn-outline-primary");
        document.getElementById("roleStudentBtn").classList.add("btn-outline-secondary");
        document.getElementById("roleStudentBtn").classList.remove("btn-primary");
    });

    document.getElementById("roleStudentBtn").addEventListener("click", () => {
        currentRole = "student";
        renderBranchUI();
        document.getElementById("roleStudentBtn").classList.add("btn-primary");
        document.getElementById("roleStudentBtn").classList.remove("btn-outline-secondary");
        document.getElementById("roleLecturerBtn").classList.add("btn-outline-primary");
        document.getElementById("roleLecturerBtn").classList.remove("btn-primary");
    });

    // Buttons
    document.getElementById("uploadNoteBtn").addEventListener("click", uploadNote);
    // document.getElementById("postNoticeBtn").addEventListener("click", postNotice);
    document.getElementById("backToHomeBtn").addEventListener("click", showHomePage);

    // Navbar buttons
    document.getElementById("loginBtnNav").addEventListener("click", () => loginModal.show());
    document.getElementById("getStartedBtnNav").addEventListener("click", () => loginModal.show());
    document.getElementById("heroGetStarted").addEventListener("click", () => loginModal.show());
    document.getElementById("heroExplore").addEventListener("click", () => loginModal.show());
    document.getElementById("startBuildingBtn").addEventListener("click", () => loginModal.show());

    // Dropdown submenu support
    document.querySelectorAll('.dropdown-submenu .dropdown-toggle').forEach(el => {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let submenu = this.nextElementSibling;
            if (submenu.classList.contains('show')) {
                submenu.classList.remove('show');
            } else {
                document.querySelectorAll('.dropdown-submenu .dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
                submenu.classList.add('show');
            }
        });
    });

    // Close submenus on outside click
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.dropdown-submenu')) {
            document.querySelectorAll('.dropdown-submenu .dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });

// Navbar smooth scroll fix (ADD HERE)
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');

            if (targetId.startsWith('#')) {
                e.preventDefault();

                if (document.getElementById("branchDashboard").style.display === "block") {
                    showHomePage();

                    setTimeout(() => {
                        document.querySelector(targetId)?.scrollIntoView({
                            behavior: "smooth"
                        });
                    }, 200);
                } else {
                    document.querySelector(targetId)?.scrollIntoView({
                        behavior: "smooth"
                    });
                }
            }
        });
    });
loadBranchData();
    showHomePage();
});