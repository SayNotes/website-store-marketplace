# 🛒 Website Store Marketplace\

## 📌 Overview
The Website Store Marketplace is a web-based platform developed to facilitate online buying and selling activities. It provides sellers with the ability to establish stores, manage product inventories, and process customer orders. At the same time, buyers can conveniently browse, compare, and purchase products through an integrated system.

## 🚀 Key Features
- Penjual Login untuk CRUD produknya
- Admin login untuk mengawasi penjual mengatur aktif atau menangguhkan
- seraching untuk mencari produk berdasarkan penjual atau nama produk yang di jual 
- keranjang untuk menampung barang yang dibeli
- autentikasi

## 🛠️ Technology Stack
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Version Control**: Git & GitHub
# 🛒 Website Store Marketplace

<p align="center">
  <img src="https://img.shields.io/badge/Status-Active-brightgreen?style=for-the-badge" />
  <img src="https://img.shields.io/badge/Version-1.0.0-blue?style=for-the-badge" />
  <img src="https://img.shields.io/badge/License-MIT-yellow?style=for-the-badge" />
</p>

<p align="center">
  A web-based marketplace platform that empowers sellers to manage their stores and enables buyers to browse and purchase products seamlessly.
</p>

---

## 📌 Overview

**Website Store Marketplace** is a full-featured web platform designed to facilitate online buying and selling activities. Sellers can establish their own stores, manage product inventories, and process customer orders — while buyers can conveniently browse, compare, and purchase products through a unified, integrated system.

---

## 🚀 Key Features

| Feature | Description |
|---|---|
| 🔐 **Seller Authentication** | Secure login for sellers to create, read, update, and delete their own products |
| 🛡️ **Admin Dashboard** | Admin panel to monitor sellers and manage account status (active / suspended) |
| 🔍 **Product Search** | Search products by seller name or product name in real-time |
| 🛒 **Shopping Cart** | Add products to cart and manage purchases before checkout |
| 🔑 **User Authentication** | Role-based authentication system for Admin, Seller, and Buyer |

---

## 🛠️ Technology Stack

<p align="left">
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" />
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" />
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" />
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white" />
  <img src="https://img.shields.io/badge/GitHub-181717?style=for-the-badge&logo=github&logoColor=white" />
</p>

| Layer | Technology |
|---|---|
| **Frontend** | HTML5, CSS3, JavaScript |
| **Backend** | PHP |
| **Database** | MySQL |
| **Version Control** | Git & GitHub |


---

## ⚙️ Installation & Setup

### Prerequisites

- PHP >= 7.4
- MySQL >= 5.7
- A local server environment (e.g. [XAMPP](https://www.apachefriends.org/) or [Laragon](https://laragon.org/))

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/website-store-marketplace.git
   ```

2. **Move to your server's root directory**
   ```bash
   # Example for XAMPP
   mv website-store-marketplace/ /xampp/htdocs/
   ```

3. **Import the database**
   - Open `phpMyAdmin`
   - Create a new database named `marketplace_db`
   - Import the file `database/marketplace_db.sql`

4. **Configure the database connection**
   ```php
   // config/db.php
   $host     = 'localhost';
   $db_name  = 'marketplace_db';
   $username = 'root';
   $password = '';
   ```

5. **Run the application**
   Open your browser and navigate to:
   ```
   http://localhost/website-store-marketplace/
   ```

---

## 👥 User Roles

```
┌─────────────┬────────────────────────────────────────────────────┐
│    Role     │ Permissions                                        │
├─────────────┼────────────────────────────────────────────────────┤
│  👤 Buyer   │ Browse products, search, manage cart, checkout     │
│  🏪 Seller  │ Manage own store, add/edit/delete products         │
│  🛡️ Admin   │ Manage all sellers, suspend/activate accounts      │
└─────────────┴────────────────────────────────────────────────────┘
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork this repository
2. Create a new branch: `git checkout -b feature/your-feature-name`
3. Commit your changes: `git commit -m "feat: add your feature"`
4. Push to the branch: `git push origin feature/your-feature-name`
5. Open a Pull Request

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

<p align="center">Made with ❤️ by <strong>Your Name</strong></p>