# 🛍️ SkillShop Online Marketplace

Welcome to **SkillShop**, a sophisticated, feature-rich e-commerce platform designed for a seamless buying and selling experience. This project is built using **PHP**, **MySQL**, and modern web technologies to provide a robust and scalable solution for online trade.

---

## 🌟 Key Features

### 👤 User Management
- **Advanced Authentication**: Secure login and registration for both Buyers and Sellers.
- **Dynamic Dashboards**: Dedicated interfaces for users to manage their activities.
- **Forgot Password**: Secure password recovery system using OTP (One-Time Password) via PHPMailer.
- **Profile Management**: Update personal information, addresses, and contact details.

### 🛒 Marketplace Functionality
- **Product Management**: Sellers can easily register, edit, and manage their products.
- **Smart Search**: Real-time product searching with advanced filtering options (Category, Price Range, Condition, etc.).
- **Watchlist & Cart**: Users can save items for later or add them to their cart for immediate purchase.
- **Product Views**: Detailed product pages with high-quality image displays.

### 💳 Payments & Transactions
- **Payhere Integration**: Secure payment processing for a smooth checkout experience.
- **Invoice Generation**: Automated invoice creation for every successful transaction.
- **Purchase History**: Track all past orders and sales directly from the dashboard.

### 💬 Communication
- **Real-time Messaging**: Built-in chat system allowing buyers and sellers to communicate directly.

---

## 🛠️ Technology Stack

| Layer | Technologies |
| :--- | :--- |
| **Frontend** | HTML5, CSS3, JavaScript, Bootstrap |
| **Backend** | PHP 8.x |
| **Database** | MySQL |
| **Payments** | Payhere SDK |
| **Email** | PHPMailer (SMTP) |

---

## ⚙️ Configuration Guide

To get this project running locally, you need to configure a few key files. Please pay close attention to the following highlighted lines:

### 1. Database Connection
File: `db/connection.php`
> [!IMPORTANT]
> **Line 9**: Update your database credentials here.
> ```php
> self::$connection = new mysqli("localhost", "root", "YOUR_PASSWORD", "skillshop_db", 3306);
> ```

### 2. Payment Gateway (Payhere)
File: `process/payhereProcess.php`
> [!IMPORTANT]
> **Lines 126 & 127**: Add your Payhere Merchant credentials.
> ```php
> $merchantId = "YOUR_MERCHANT_ID";
> $merchantSecret = "YOUR_MERCHANT_SECRET";
> ```

### 3. Email Settings (PHPMailer)
File: `process/PHPMailer/email.php`
> [!IMPORTANT]
> **Lines 21 & 22**: Set up your SMTP credentials for sending emails.
> ```php
> $mail->Username = 'your-email@gmail.com'; // Add your email
> $mail->Password = 'your-app-password';   // Add your App Password
> ```

---

## 🚀 Installation Steps

1. **Clone the Project**:
   ```bash
   git clone https://github.com/Dilshan615/Skillshop_Public.git
   ```
2. **Setup XAMPP**:
   - Move the project folder to `C:\xampp\htdocs\`.
   - Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. **Database Setup**:
   - Go to `phpMyAdmin` (http://localhost/phpmyadmin).
   - Create a new database named `skillshop_db`.
   - Import the `ER/skillshop_db.sql` and `ER/skillshop_data.sql` files.
4. **Configuration**:
   - Follow the [Configuration Guide](#%EF%B8%8F-configuration-guide) above to link your DB, Payment, and Email services.
5. **Run**:
   - Open your browser and navigate to `http://localhost/Skillshop_Public`.

---

## 🎨 Design Aesthetics

SkillShop is designed with a **premium user experience** in mind:
- **Responsive Layout**: Works flawlessly on desktops, tablets, and mobile devices.
- **Modern UI**: Clean, white-space oriented design with tactical use of gradients and micro-animations.
- **Intuitive UX**: Logic-driven navigation that makes shopping and selling effortless.

---

© 2026 SkillShop. All rights reserved. Developed with ❤️ by Dilshan.
