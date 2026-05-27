<div align="center">

<br/>

```
██████████████████████████████████████████
█                                        █
█      WEBSITE STORE MARKETPLACE         █
█                                        █
██████████████████████████████████████████
```

### A modern web marketplace — built for sellers, loved by buyers.

<br/>

[![Status](https://img.shields.io/badge/STATUS-ACTIVE-4CAF50?style=for-the-badge&labelColor=1a1a2e)](.)
[![Version](https://img.shields.io/badge/VERSION-1.0.0-6C63FF?style=for-the-badge&labelColor=1a1a2e)](.)
[![PHP](https://img.shields.io/badge/PHP-≥7.4-777BB4?style=for-the-badge&logo=php&logoColor=white&labelColor=1a1a2e)](.)
[![MySQL](https://img.shields.io/badge/MySQL-≥5.7-4479A1?style=for-the-badge&logo=mysql&logoColor=white&labelColor=1a1a2e)](.)
[![License](https://img.shields.io/badge/LICENSE-MIT-FF6B6B?style=for-the-badge&labelColor=1a1a2e)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-WELCOME-00BCD4?style=for-the-badge&labelColor=1a1a2e)](.)

<br/>

**[📌 Overview](#-overview) · [✨ Features](#-features) · [🛠️ Tech Stack](#%EF%B8%8F-tech-stack) · [⚙️ Installation](#%EF%B8%8F-installation) · [👥 User Roles](#-user-roles)**

<br/>

</div>

---

## 📌 Overview

> **Website Store Marketplace** is a full-featured e-commerce web platform built with simplicity and scalability in mind.

Sellers can register, set up their store, and manage their entire product catalog — while buyers enjoy a smooth shopping experience with real-time search and an intuitive cart system. An **Admin** layer keeps the platform safe and well-managed.

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│    BUYER    │     │   SELLER    │     │    ADMIN    │
│             │     │             │     │             │
│  Browse &   │     │  Manage     │     │  Monitor &  │
│  Purchase   │     │  Products   │     │  Control    │
└──────┬──────┘     └──────┬──────┘     └──────┬──────┘
       │                   │                   │
       └───────────────────┼───────────────────┘
                           │
                  ┌────────▼────────┐
                  │   MARKETPLACE   │
                  │    PLATFORM     │
                  └─────────────────┘

---

## ✨ Features

| # | Feature | Description |
|---|---------|-------------|
| 🔐 | **Seller Login & Product Management** | Sellers can securely log in and perform full CRUD operations on their products |
| 🛡️ | **Admin Control Panel** | Admins can monitor all sellers and toggle account status between active and suspended |
| 🔍 | **Smart Product Search** | Search across the marketplace by product name or seller — fast and intuitive |
| 🛒 | **Shopping Cart** | Buyers can add items to cart, review selections, and proceed to checkout smoothly |
| 🔑 | **Role-Based Authentication** | Secure, session-based authentication for Admin, Seller, and Buyer roles |
| 📦 | **Full CRUD Operations** | Create, read, update, and delete products from a clean seller dashboard |

---

## 🛠️ Tech Stack

```
╔══════════════════════════════════════════════════════════╗
║                      TECH STACK                          ║
╠══════════════════╦═══════════════════════════════════════╣
║   LAYER          ║   TECHNOLOGY                          ║
╠══════════════════╬═══════════════════════════════════════╣
║   Frontend       ║   HTML5 · CSS3 · JavaScript           ║
║   Backend        ║   PHP (≥ 7.4)                         ║
║   Database       ║   MySQL (≥ 5.7)                       ║
║   Local Server   ║   XAMPP / Laragon                     ║
║   Version Control║   Git · GitHub                        ║
╚══════════════════╩═══════════════════════════════════════╝
```

![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat-square&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)
![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=flat-square&logo=xampp&logoColor=white)
![Git](https://img.shields.io/badge/Git-F05032?style=flat-square&logo=git&logoColor=white)
![GitHub](https://img.shields.io/badge/GitHub-181717?style=flat-square&logo=github&logoColor=white)

---

## ⚙️ Installation

### Prerequisites

Make sure the following are installed before getting started:

- ✅ PHP `>= 7.4`
- ✅ MySQL `>= 5.7`
- ✅ XAMPP or Laragon

---

### Step 1 — Clone the Repository

```bash
git clone https://github.com/your-username/website-store-marketplace.git
```

### Step 2 — Move to Local Server Directory

```bash
# For XAMPP users
mv website-store-marketplace/ /xampp/htdocs/

# For Laragon users
mv website-store-marketplace/ C:/laragon/www/
```

### Step 3 — Import the Database

```
1. Open phpMyAdmin
2. Create a new database named:  marketplace_db
3. Click Import → choose file:   database/marketplace_db.sql
4. Click Go ✓
```

### Step 4 — Configure Database Connection

```php
// config/db.php

$host     = 'localhost';
$db_name  = 'marketplace_db';
$username = 'root';
$password = '';
```

### Step 5 — Launch the App 🚀

```
Open your browser and go to:

  http://localhost/website-store-marketplace/
```

---

## 👥 User Roles

```
┌────────────────────────────────────────────────────────────────┐
│                        USER ROLES                              │
├──────────────┬─────────────────┬──────────────────────────────┤
│   ROLE       │   ACCESS LEVEL  │   CAPABILITIES               │
├──────────────┼─────────────────┼──────────────────────────────┤
│  👤 Buyer    │   Public        │  Browse, search, cart,       │
│              │                 │  checkout                    │
├──────────────┼─────────────────┼──────────────────────────────┤
│  🏪 Seller   │   Authenticated │  Manage store, add / edit /  │
│              │                 │  delete products             │
├──────────────┼─────────────────┼──────────────────────────────┤
│  🛡️ Admin   │   Super         │  Monitor sellers, activate   │
│              │                 │  or suspend accounts         │
└──────────────┴─────────────────┴──────────────────────────────┘
```


---

## 📄 License

Distributed under the [MIT License](LICENSE). Free to use, modify, and distribute.

---

<div align="center">

<br/>

```
Made with ❤️ by
```

### 👑 Sultan &nbsp;&nbsp;×&nbsp;&nbsp; Joan 🎯

```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  Built with PHP · MySQL · HTML · CSS · JS
         Open Source · MIT License
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

⭐ **Star this repo if you found it useful!**

<br/>

</div>