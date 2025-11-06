# 🚗 Driving School Management System (DSMS)

A **web-based Driving School Management System** developed using **Laravel**, **PostgreSQL**, **Vue.js**, and **Bootstrap 5**.  
This system streamlines the operations of a driving school — from student registration and course management to instructor assignment and payment tracking — offering a modern, responsive, and user-friendly experience for all users.

---

## 📋 Table of Contents

- [Features](#features)
- [System Users](#system-users)
- [Tech Stack](#tech-stack)
- [System Overview](#system-overview)
- [Database Schema Overview](#database-schema-overview)
- [Installation Guide](#installation-guide)
- [Usage](#usage)
- [API Endpoints (Sample)](#api-endpoints-sample)
- [Future Improvements](#future-improvements)
- [Screenshots](#screenshots)
- [License](#license)
- [Author](#author)

---

## ✨ Features

✅ **Admin Dashboard**  
- Manage branches, instructors, secretaries, and students  
- Create and manage course packages and add-ons  
- Track student enrollment and learning progress  
- Generate detailed financial and performance reports  

✅ **Instructor Panel**  
- View assigned students and class schedules  
- Record attendance and lesson progress  
- Communicate updates to students  

✅ **Student Portal**  
- Register and select a preferred branch  
- Enroll in a course package and pay (full or installment)  
- Track lessons, schedules, and performance  
- Receive notifications and updates  

✅ **Payments**  
- Supports full and installment payment options  
- Integrated with payment gateways (future: Pesapal, Flutterwave)  
- Displays receipts and transaction history  

✅ **Responsive Design**  
- Built with **Bootstrap 5** and **Vue.js** for a smooth and modern UI experience  
- Works seamlessly on desktops, tablets, and mobile devices  

---

## 👥 System Users

| Role | Description |
|------|--------------|
| **Admin** | Manages branches, instructors, courses, and overall system operations. |
| **Instructor** | Oversees student lessons, attendance, and evaluations. |
| **Student** | Registers, enrolls in courses, and tracks learning progress. |
| **Secretary (Branch Admin)** | Manages branch-level operations, student enrollments, and payments. |

---

## 🧰 Tech Stack

| Layer | Technology |
|-------|-------------|
| **Backend** | [Laravel 11](https://laravel.com/) |
| **Database** | [PostgreSQL](https://www.postgresql.org/) |
| **Frontend** | [Vue.js 3](https://vuejs.org/) + [Bootstrap 5](https://getbootstrap.com/) |
| **API Communication** | RESTful APIs with Axios |
| **Authentication** | Laravel Sanctum |
| **Version Control** | Git & GitHub |
| **Deployment** | Laravel Sail / Docker / Shared Hosting |

---

## 🧩 System Overview

The system consists of two main layers:

1. **Backend (Laravel + PostgreSQL)**  
   - Exposes RESTful API endpoints  
   - Handles authentication, data management, and business logic  

2. **Frontend (Vue.js + Bootstrap)**  
   - Fetches data via Axios from Laravel APIs  
   - Offers a clean, responsive UI for all roles  

---

## 🗄️ Database Schema Overview

**Core Tables:**
- `branches` — branch information (location, name, contact, etc.)  
- `users` — stores admins, instructors, students, and secretaries  
- `courses` — available driving courses and fees  
- `add_ons` — optional course add-ons  
- `enrollments` — link between students and their chosen courses  
- `payments` — student payments (full or installment)  
- `attendance` — student attendance tracking  
- `schedules` — driving lesson timetable  

---

## ⚙️ Installation Guide

### 1. Clone the Repository
```bash
git clone https://github.com/Oriana-Hope-Benjamin/DSMS.git
cd dsms

