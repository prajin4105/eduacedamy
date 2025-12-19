# EduAcademy – Laravel E-Learning Platform

EduAcademy is a modern e-learning management system built with **Laravel** and **Filament**.  
It provides a complete online education solution with student, instructor, and admin functionalities through both **Filament dashboards** and **RESTful APIs**.

---

## 🚀 Core Functionalities

### 🧑‍💼 Admin Panel (Filament)
- Manage **courses**, **categories**, **students**, **instructors**, **plans**, and **subscriptions**
- Create and manage **tests** and **certificates**
- Track **enrollments** and **user progress**
- Access a full-featured **dashboard**
- Handle authentication for multiple roles (Admin, Instructor, Student)

---
    
### 👨‍🏫 Instructor Panel
- Create and manage **courses** with lessons and tests  
- View and manage **enrollments** and student performance  
- Track progress via the instructor **dashboard**  
- Secure **login/logout** system for instructors

---

### 🎓 Student APIs
- Register, log in, and manage **profiles**
- Browse **courses** and **categories**
- Enroll in courses and track progress
- Manage **wishlists**, **reviews**, and **subscriptions**
- Generate and download **course completion certificates**
- **Verify certificates** through a unique certificate number
- Password recovery using OTP-based system

---

### 💳 Payment & Subscription
- Secure **payment API** for order creation and subscription purchase  
- Manage **plans**, **subscriptions**, and **renewals**  
- Cancel or check subscription status via dedicated endpoints

---

### 📈 Progress Tracking
- Track **time spent** and **video completion**
- Update and display **course progress**
- Generate certificates automatically upon completion

---

### 🧾 Certificates System
- Auto-generate course completion certificates  
- Download and verify certificates using `/api/verify-certificate/{certificateNumber}`  

---

### 🧩 API Highlights
| Module | Key Endpoints |
|--------|----------------|
| **Auth** | `/api/register`, `/api/login`, `/api/logout`, `/api/forgot-password`, `/api/reset-password` |
| **Courses** | `/api/courses`, `/api/categories`, `/api/courses/{slug}` |
| **Enrollments** | `/api/enrollments`, `/api/courses/{courseId}/enrollment-status` |
| **Certificates** | `/api/certificates`, `/api/verify-certificate/{number}` |
| **Subscriptions** | `/api/subscriptions`, `/api/subscriptions/subscribe`, `/api/subscriptions/status` |
| **Tests** | `/api/test/{courseId}`, `/api/test/submit`, `/api/test/status/{courseId}` |
| **Reviews** | `/api/courses/{course}/reviews`, `/api/reviews/{review}` |
| **Wishlist** | `/api/wishlist`, `/api/wishlist/{course}` |
| **Payments** | `/api/create-order` |

---

### ⚙️ Tech Stack
- **Framework:** Laravel 10  
- **Admin:** FilamentPHP  
- **Frontend Integration:** Livewire / API-driven Frontend  
- **Auth:** Laravel Sanctum  
- **Database:** MySQL  
- **Payments:** Razorpay API  

---

### 📜 License
Released under the **MIT License**.  

