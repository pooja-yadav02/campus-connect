# 🎓 Campus Connect

> A modern department management system that bridges the gap between lecturers and students — built for real campus workflows.

---

## 📌 Overview

Campus Connect is a full-stack web application designed to streamline academic communication and course management. It provides role-based dashboards for **lecturers** and **students**, enabling seamless course creation, material uploads, and enrollment management — all in one place.

---

## ✨ Features

### 👨‍🏫 Lecturer Dashboard
- Create, edit, and delete courses
- Upload course materials (PDF, PPT, DOC, ZIP, MP4, and more)
- View enrolled students per course
- In-dashboard PDF preview
- Profile management

### 👨‍🎓 Student Dashboard
- Browse and search all available courses
- Enroll / unenroll from courses
- Access and preview uploaded materials
- Track enrolled course stats

### 🔐 Authentication
- Secure registration and login (PHP sessions)
- Role-based access control (Student / Lecturer)
- Remember me functionality
- Flash messages for errors and success

---

## 🛠️ Tech Stack

| Layer       | Technology                        |
|-------------|-----------------------------------|
| Frontend    | HTML5, CSS3, Bootstrap 5          |
| Fonts/Icons | Google Fonts, Font Awesome 6      |
| Animations  | AOS (Animate on Scroll)           |
| Backend     | PHP (Sessions, Flash messages)    |
| Storage     | localStorage (client-side data)   |
| Auth        | PHP Session-based authentication  |

---

---

## 👥 User Roles

| Role     | Capabilities                                                  |
|----------|---------------------------------------------------------------|
| Lecturer | Create courses · Upload materials · View enrolled students    |
| Student  | Browse courses · Enroll/Unenroll · View & preview materials   |

---

## 📸 Screenshots

> *(Add screenshots of your landing page, student dashboard, and lecturer dashboard here)*

---

## ⚠️ Known Limitations

- Course and material data is stored in **localStorage** — data is browser/device specific and clears on cache reset
- PDF preview is limited to files **under 2MB** due to localStorage size constraints
- No database integration yet — a future version will use MySQL

---

## 🔮 Future Improvements

- [ ] MySQL database integration
- [ ] Assignment submission system
- [ ] Real-time notifications
- [ ] Admin panel for user management
- [ ] Email verification on registration
- [ ] Cloud file storage (AWS S3 / Firebase)
- [ ] Mobile app version

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a new branch (`git checkout -b feature/your-feature`)
3. Commit your changes (`git commit -m 'Add your feature'`)
4. Push to the branch (`git push origin feature/your-feature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the **MIT License** — see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

Built with ❤️ for campus communities.  
Feel free to reach out or open an issue for any bugs or suggestions.

---

> © 2026 Campus Connect — Bridging learning & collaboration
