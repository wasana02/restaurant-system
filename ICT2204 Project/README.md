# 🌊 Una Beach Restaurant — Phase 3 Backend

**ICT 2204 | Rajarata University of Sri Lanka**

---

## 📁 What's in this folder (backend files only)

```
backend/
│── database.sql          ← Import this in phpMyAdmin FIRST
│── includes/
│   ├── db.php            ← Database connection
│   └── functions.php     ← Helper functions
│── auth/
│   ├── register.php      ← User registration
│   ├── login.php         ← User login
│   └── logout.php        ← Logout
│── index.php             ← Home (converted from home.html)
│── menu.php              ← Menu (converted from menu.html)
│── about.php             ← About (converted from about.html)
│── contact.php           ← Contact (saves to database)
│── dashboard.php         ← Reservations (login required)
└── README.md
```

---

## ⚙️ How to set up with WAMP

### Step 1 — Copy ALL your files
Copy everything (backend + your original css/, js/, images/ folders) into:
```
C:\wamp64\www\una-beach-restaurant\
```

### Step 2 — Import the database
1. Open **WAMP** → wait for green icon
2. Go to `http://localhost/phpmyadmin`
3. Click **Import** → Choose File → select `database.sql` → **Go**

### Step 3 — Open in browser
```
http://localhost/una-beach-restaurant/index.php
```

---

## ✅ Features

| Feature | File |
|---|---|
| User Registration | `auth/register.php` |
| User Login | `auth/login.php` |
| User Logout | `auth/logout.php` |
| Contact form → saves to DB | `contact.php` |
| Table reservations | `dashboard.php` |
| DB connection | `includes/db.php` |

---

## 🗄️ Database Tables
- **users** — registered accounts (hashed passwords)
- **messages** — contact form submissions
- **reservations** — table bookings linked to users
