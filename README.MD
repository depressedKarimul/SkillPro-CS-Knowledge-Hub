# SkillPro - CS Knowledge Hub

![License](https://img.shields.io/badge/license-MIT-blue.svg) 
![PHP](https://img.shields.io/badge/backend-PHP-777BB4.svg?logo=php&logoColor=white) 
![MySQL](https://img.shields.io/badge/database-MySQL-4479A1.svg?logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/styling-Tailwind_CSS-38B2AC.svg?logo=tailwind-css&logoColor=white)
![DaisyUI](https://img.shields.io/badge/ui-DaisyUI-5A0EF8.svg)

> **Empowering the next generation of Computer Science professionals.**

**SkillPro** is a robust, full-featured **E-Learning Management System (LMS)** designed to bridge the gap between students and expert instructors. With a focus on Computer Science, it offers a rich interactive environment for mastering Development, IT, and Design skills.

---

## 📑 Table of Contents
- [✨ Key Features](#-key-features)
  - [🎓 Student Experience](#-student-experience)
  - [👨‍🏫 Instructor Portal](#-instructor-portal)
  - [🛠 Admin Control](#-admin-control)
- [📝 Quizzes & Certification](#-quizzes--certification)
- [🤖 Smart Features](#-smart-features)
- [💻 Tech Stack](#-tech-stack)
- [🚀 Installation & Setup](#-installation--setup)
- [📂 Project Structure](#-project-structure)
- [🤝 Contributing](#-contributing)

---

## ✨ Key Features

### 🎓 Student Experience
*   **Course Discovery**: Intuitive search and filtering by categories like *Development*, *IT & Software*, and *Design*.
*   **Immersive Learning**: High-quality video playback for course content.
*   **Progress Tracking**: Monitor your learning journey course by course.
*   **Review System**: Share feedback through a 5-star rating and review system.
*   **Secure Profile**: Manage personal details, bio, and avatar.

### 👨‍🏫 Instructor Portal
*   **Course Creation**: Easy-to-use tools to upload videos, set prices, and write descriptions.
*   **Student Management**: View enrollment statistics and student progress.
*   **Profile Showcase**: Build a professional brand with a dedicated instructor profile page.
*   **Earnings**: (Future Scope) Track revenue from course sales.

### 🛠 Admin Control
*   **User Management**: Full control to view, verify, or remove Students and Instructors.
*   **Content Moderation**: Ability to delete inappropriate courses or spam reviews.
*   **Global Search**: Powerful search functionality to find any user or course across the platform.

---

## 📝 Quizzes & Certification

SkillPro goes beyond just video watching. It ensures mastery through rigorous testing.

*   **Dynamic Quizzes**: Courses feature integrated quizzes supporting multiple question types:
    *   *Multiple Choice Questions (MCQ)*
    *   *True/False*
    *   *Short Answer*
*   **Instant Feedback**: Get immediate results after submission.
*   **Automated Certification**: Complete a quiz with **100% accuracy** to instantly generate and receive a Course Completion Certificate. 🏆

---

## 🤖 Smart Features

*   **💬 Integrated Chatbot**: 24/7 support powered by **n8n workflow automation**, ready to answer platform-related queries instantly.
*   **🌓 Dark/Light Mode**: A stunning, fully responsive UI that adapts to your preference, built with **DaisyUI** and **Tailwind CSS**.
*   **📨 Internal Messaging**: A built-in email-style messaging system allows seamless communication (with file attachments!) between students and instructors.

---

## 💻 Tech Stack

| Component | Technology | Description |
| :--- | :--- | :--- |
| **Frontend** | HTML5, CSS3, JavaScript | Core structure and interactivity |
| **Styling** | **Tailwind CSS** + **DaisyUI** | Modern, responsive, and themeable UI |
| **Backend** | **PHP** (Native) | Robust server-side logic |
| **Database** | **MySQL** | Relational data management |
| **Automation** | **n8n** | AI Chatbot workflow integration |

---

## 🚀 Installation & Setup

Follow these steps to get SkillPro running on your local machine.

### Prerequisites
*   [XAMPP](https://www.apachefriends.org/index.html) (or WAMP/MAMP) installed.
*   Basic understanding of PHP/MySQL.

### Step-by-Step

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/depressedKarimul/SkillPro-CS-Knowledge-Hub.git
    ```

2.  **Move to Server Directory**
    *   Copy the `SkillPro-CS-Knowledge-Hub` folder.
    *   Paste it into your XAMPP `htdocs` folder (e.g., `C:\xampp\htdocs\`).

3.  **Database Setup**
    *   Start **Apache** and **MySQL** in XAMPP Control Panel.
    *   Go to `http://localhost/phpmyadmin`.
    *   Create a new database named **`skillpro2`**.
    *   Import the SQL file found in the `database/` folder of this project.

4.  **Configure Connection**
    *   Open `database.php` and verify these settings:
        ```php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "skillpro2"; // Ensure this matches your DB name
        ```

5.  **Launch** 🚀
    *   Open your browser and visit:
        ```
        http://localhost/SkillPro-CS-Knowledge-Hub/index.php
        ```

---

## 📂 Project Structure

```bash
SkillPro/
├── admin.php           # Admin Dashboard
├── index.php           # Landing Page
├── student.php         # Student Dashboard
├── instructor.php      # Instructor Dashboard
├── quiz_page.php       # Quiz Logic
├── Messaging/          # Internal Chat System
├── database/           # SQL Import Files
├── Styles/             # CSS Files
├── Js/                 # JavaScript Files
└── Images/             # Asset storage
```

---

## 🤝 Contributing

We welcome contributions!
1.  Fork the repo.
2.  Create your feature branch (`git checkout -b feature/AmazingFeature`).
3.  Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4.  Push to the branch (`git push origin feature/AmazingFeature`).
5.  Open a Pull Request.

---

**Built with ❤️ by the SkillPro Team**