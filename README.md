<div align="center">

<img src="https://readme-typing-svg.demolab.com?font=Fira+Code&weight=700&size=28&duration=3000&pause=1000&color=6C63FF&center=true&vCenter=true&width=650&lines=🛒+Website+Store+Marketplace;Buy+%26+Sell+Made+Simple;Built+with+PHP+%2B+MySQL" alt="Typing SVG" />

<br/><br/>

<p>
  <img src="https://img.shields.io/badge/Status-Active-4CAF50?style=for-the-badge&logo=checkmarx&logoColor=white" />
  <img src="https://img.shields.io/badge/Version-1.0.0-6C63FF?style=for-the-badge&logo=git&logoColor=white" />
  <img src="https://img.shields.io/badge/License-MIT-FF6B6B?style=for-the-badge&logo=opensourceinitiative&logoColor=white" />
  <img src="https://img.shields.io/badge/PRs-Welcome-00BCD4?style=for-the-badge&logo=github&logoColor=white" />
</p>

<p>
  <strong>A modern web-based marketplace platform that empowers sellers to build their stores<br/>and enables buyers to discover, purchase, and track their orders — all in one place.</strong>
</p>

<br/>

[![Overview](https://img.shields.io/badge/📌_Overview-grey?style=flat-square)](#-overview)
[![Features](https://img.shields.io/badge/✨_Features-grey?style=flat-square)](#-key-features)
[![Tech Stack](https://img.shields.io/badge/🛠️_Tech_Stack-grey?style=flat-square)](#️-tech-stack)
[![Installation](https://img.shields.io/badge/⚙️_Installation-grey?style=flat-square)](#️-installation)
[![User Roles](https://img.shields.io/badge/👥_User_Roles-grey?style=flat-square)](#-user-roles)

</div>

<br/>

---

## 📌 Overview

**Website Store Marketplace** is a full-featured e-commerce web platform built with simplicity and scalability in mind. Sellers can register, set up their store, and manage their entire product catalog — while buyers enjoy a smooth shopping experience with real-time search, an intuitive cart system, and complete transaction history tracking. An Admin layer keeps the platform safe and well-managed.

---

## ✨ Key Features

<br/>

<div align="center">

|  | Feature | Description |
|:---:|:---|:---|
| 🔐 | **Seller Login & Product Management** | Sellers can securely log in and perform full CRUD operations on their products |
| 🛡️ | **Admin Control Panel** | Admins can monitor all sellers and toggle account status between active and suspended |
| 🔍 | **Smart Product Search** | Search across the marketplace by product name or seller — fast and intuitive |
| 🛒 | **Shopping Cart** | Buyers can add items to cart, review selections, and proceed to checkout smoothly |
| 🔑 | **Role-Based Authentication** | Secure, session-based authentication system for Admin, Seller, and Buyer |
| 📋 | **Transaction History** | Buyers and sellers can view a complete log of past orders and transaction details |

</div>

<br/>

---

## 🛠️ Tech Stack

<br/>

<div align="center">

| Layer | Technology |
|:---:|:---:|
| **Frontend** | ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white) ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat-square&logo=css3&logoColor=white) ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black) |
| **Backend** | ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white) |
| **Database** | ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white) |
| **Version Control** | ![Git](https://img.shields.io/badge/Git-F05032?style=flat-square&logo=git&logoColor=white) ![GitHub](https://img.shields.io/badge/GitHub-181717?style=flat-square&logo=github&logoColor=white) |
| **Local Server** | ![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=flat-square&logo=xampp&logoColor=white) |

</div>

<br/>

---

## ⚙️ Installation

### Prerequisites

> Pastikan semua tools berikut sudah terinstall sebelum memulai.

<div align="center">

| Tool | Minimum Version |
|:---:|:---:|
| ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white) | `>= 7.4` |
| ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white) | `>= 5.7` |
| ![XAMPP](https://img.shields.io/badge/XAMPP_or_Laragon-FB7A24?style=flat-square&logo=xampp&logoColor=white) | Latest |

</div>

<br/>

### 🔧 Setup Steps

**`Step 1`** — Clone the repository
```bash
git clone https://github.com/your-username/website-store-marketplace.git
```

**`Step 2`** — Move to your local server directory
```bash
# For XAMPP users (Windows)
move website-store-marketplace C:\xampp\htdocs\

# For XAMPP users (Linux / Mac)
mv website-store-marketplace/ /opt/lampp/htdocs/
```

**`Step 3`** — Import the database
```
1. Open phpMyAdmin at → http://localhost/phpmyadmin
2. Create a new database named → marketplace_db
3. Click Import → select file → database/marketplace_db.sql
4. Click Go ✅
```

**`Step 4`** — Configure the database connection
```php
// config/db.php
$host     = 'localhost';
$db_name  = 'marketplace_db';
$username = 'root';
$password = '';
```

**`Step 5`** — Launch the application 🚀
```
http://localhost/website-store-marketplace/
```

<br/>

---

## 👥 User Roles

<br/>

<div align="center">

| Role | Access | Capabilities |
|:---:|:---:|:---|
| 👤 **Buyer** | `Public` | Browse products · Search · Add to cart · Checkout · View transaction history |
| 🏪 **Seller** | `Authenticated` | Manage store · Add / Edit / Delete products · View incoming orders |
| 🛡️ **Admin** | `Super` | Monitor all sellers · Activate or suspend accounts · Oversee platform activity |

</div>

<br/>

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/your-username/website-store-marketplace/issues).

```bash
# 1. Fork the repository
# 2. Create your feature branch
git checkout -b feature/amazing-feature

# 3. Commit your changes  
git commit -m "feat: add amazing feature"

# 4. Push to the branch
git push origin feature/amazing-feature

# 5. Open a Pull Request 🎉
```

---

## 📄 License

Distributed under the [MIT License](LICENSE). See `LICENSE` for more information.

---

<div align="center">


<br/>

⭐ **Give a star if this project helped you!** ⭐

<br/>

![Wave](https://capsule-render.vercel.app/api?type=waving&color=6C63FF&height=100&section=footer)

</div>