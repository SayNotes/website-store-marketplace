<div align="center">

<img src="https://readme-typing-svg.demolab.com?font=Fira+Code&size=32&duration=3000&pause=1000&color=6C63FF&center=true&vCenter=true&width=600&lines=🛒+Website+Store+Marketplace;Buy+%26+Sell+Made+Simple" alt="Typing SVG" />

<br/>

<p>
  <img src="https://img.shields.io/badge/Status-Active-4CAF50?style=for-the-badge&logo=checkmarx&logoColor=white" />
  <img src="https://img.shields.io/badge/Version-1.0.0-6C63FF?style=for-the-badge&logo=git&logoColor=white" />
  <img src="https://img.shields.io/badge/License-MIT-FF6B6B?style=for-the-badge&logo=opensourceinitiative&logoColor=white" />
  <img src="https://img.shields.io/badge/PRs-Welcome-00BCD4?style=for-the-badge&logo=github&logoColor=white" />
</p>

<p><em>A modern web-based marketplace platform that empowers sellers to build their stores<br/>and enables buyers to discover and purchase products — all in one place.</em></p>

<a href="#-overview">Overview</a> •
<a href="#-key-features">Features</a> •
<a href="#-tech-stack">Tech Stack</a> •
<a href="#-installation">Installation</a> •
<a href="#-user-roles">User Roles</a>

---

</div>

## 📌 Overview

**Website Store Marketplace** is a full-featured e-commerce web platform built with simplicity and scalability in mind. Sellers can register, set up their store, and manage their entire product catalog — while buyers enjoy a smooth shopping experience with real-time search and an intuitive cart system. An Admin layer keeps the platform safe and well-managed.

---

## ✨ Key Features

<table>
  <tr>
    <td width="50px" align="center">🔐</td>
    <td><strong>Seller Login & Product Management</strong><br/>Sellers can securely log in and perform full CRUD operations on their products.</td>
  </tr>
  <tr>
    <td align="center">🛡️</td>
    <td><strong>Admin Control Panel</strong><br/>Admins can monitor all sellers and toggle account status between active and suspended.</td>
  </tr>
  <tr>
    <td align="center">🔍</td>
    <td><strong>Smart Product Search</strong><br/>Search across the marketplace by product name or seller — fast and intuitive.</td>
  </tr>
  <tr>
    <td align="center">🛒</td>
    <td><strong>Shopping Cart</strong><br/>Buyers can add items to cart, review selections, and proceed to checkout smoothly.</td>
  </tr>
  <tr>
    <td align="center">🔑</td>
    <td><strong>Role-Based Authentication</strong><br/>Secure, session-based authentication for Admin, Seller, and Buyer roles.</td>
  </tr>
</table>

---

## 🛠️ Tech Stack

<div align="center">

| Layer | Technology |
|:---:|:---:|
| **Frontend** | ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white) ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat-square&logo=css3&logoColor=white) ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black) |
| **Backend** | ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white) |
| **Database** | ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white) |
| **Version Control** | ![Git](https://img.shields.io/badge/Git-F05032?style=flat-square&logo=git&logoColor=white) ![GitHub](https://img.shields.io/badge/GitHub-181717?style=flat-square&logo=github&logoColor=white) |
| **Local Server** | ![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=flat-square&logo=xampp&logoColor=white) |

</div>

---

## ⚙️ Installation

### Prerequisites

> Make sure you have the following installed before getting started.

- ![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4-777BB4?style=flat-square&logo=php&logoColor=white)
- ![MySQL](https://img.shields.io/badge/MySQL-%3E%3D5.7-4479A1?style=flat-square&logo=mysql&logoColor=white)
- ![XAMPP](https://img.shields.io/badge/XAMPP_or_Laragon-FB7A24?style=flat-square&logo=xampp&logoColor=white)

### Setup Steps

**1. Clone the repository**
```bash
git clone https://github.com/your-username/website-store-marketplace.git
```

**2. Move to your local server directory**
```bash
# For XAMPP users
mv website-store-marketplace/ /xampp/htdocs/
```

**3. Import the database**
```
- Open phpMyAdmin
- Create a new database → marketplace_db
- Import file: database/marketplace_db.sql
```

**4. Configure the database connection**
```php
// config/db.php
$host     = 'localhost';
$db_name  = 'marketplace_db';
$username = 'root';
$password = '';
```

**5. Launch the app** 🚀
```
http://localhost/website-store-marketplace/
```

---

## 👥 User Roles

<div align="center">

| Role | Access Level | Capabilities |
|:---:|:---:|---|
| 👤 **Buyer** | Public | Browse products, search, add to cart, checkout |
| 🏪 **Seller** | Authenticated | Manage store, add / edit / delete products |
| 🛡️ **Admin** | Super | Monitor sellers, activate or suspend accounts |

</div>


---

## 📄 License

Distributed under the [MIT License](LICENSE). See `LICENSE` for more information.

---

<div align="center">

Made with ❤️ by **Your Name**

⭐ Star this repo if you find it helpful!

</div>