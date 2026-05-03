# Cosmetic Mart 🌸

An e-commerce platform for cosmetic products with integrated payment gateway, admin dashboard, and user management system.

## 📋 Table of Contents

- [Project Description](#project-description)
- [Tech Stack](#tech-stack)
- [Features](#features)
- [Prerequisites](#prerequisites)
- [Installation & Setup](#installation--setup)
- [Project Structure](#project-structure)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Usage](#usage)
- [Contributing](#contributing)
- [Troubleshooting](#troubleshooting)

## 📖 Project Description

Cosmetic Mart is a full-featured e-commerce application built with PHP and MySQL. It provides a complete shopping experience including product browsing, shopping cart management, secure checkout, and integrated eSewa payment processing. The platform includes a powerful admin dashboard for managing products, orders, and customer messages.

## 🛠️ Tech Stack

| Component | Technology |
|-----------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL 5.7+ |
| **Frontend** | HTML5, CSS3, JavaScript |
| **Server** | Apache (XAMPP/LAMP) |
| **Payment Gateway** | eSewa (Nepalese Payment Gateway) |
| **Session Management** | PHP Sessions |

## ✨ Features

### Customer Features
- 🛍️ **Product Browsing** - View cosmetic products with detailed information
- 🛒 **Shopping Cart** - Add/remove products and manage quantities
- 💳 **Secure Checkout** - Easy and secure order placement
- 💰 **eSewa Payment Integration** - Secure payment processing
- 👤 **User Accounts** - Register, login, and manage profile
- 🔐 **Password Recovery** - Forgot password functionality
- 📬 **Contact & Messaging** - Send messages to admin
- 📦 **Order Tracking** - View order history and status

### Admin Features
- 📊 **Admin Dashboard** - Overview of sales and orders
- 📦 **Product Management** - Add, edit, delete products
- 📋 **Order Management** - View and update order status
- 💬 **Message Management** - View and respond to customer messages
- 👥 **User Management** - View and manage users

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **XAMPP** (or LAMP stack)
  - Apache
  - MySQL
  - PHP 7.4+
- **Git** (for cloning the repository)
- **Text Editor or IDE** (VS Code, PHPStorm, etc.)
- **Web Browser** (Chrome, Firefox, Safari, etc.)

### System Requirements
- Windows/Linux/macOS
- Minimum 512MB RAM
- 50MB free disk space

## 🚀 Installation & Setup

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/CosmeticMartTest.git
cd CosmeticMartTest
```

### Step 2: Setup XAMPP

1. **Start XAMPP Control Panel**
   - Start Apache Server
   - Start MySQL Server

2. **Move Project to XAMPP**
   ```
   Copy the project to: C:\xampp\htdocs\CosmeticMartTest
   (or your XAMPP installation path)
   ```

### Step 3: Configure Database

1. **Access phpMyAdmin**
   - Open browser and go to `http://localhost/phpmyadmin`

2. **Create Database**
   ```sql
   CREATE DATABASE cosmetic_mart;
   USE cosmetic_mart;
   ```

3. **Import Database Schema**
   - Import the database SQL file (if available)
   - Or create tables manually (see [Database Setup](#database-setup))

### Step 4: Configure Application

1. **Setup Configuration File**
   ```bash
   cp includes/config.sample.php includes/config.php
   ```

2. **Edit `includes/config.php`** and add your eSewa credentials:
   ```php
   'esewa' => [
       'merchant_code' => 'YOUR_MERCHANT_CODE',
       'merchant_secret' => 'YOUR_MERCHANT_SECRET',
       'environment' => 'sandbox', // or 'production'
   ]
   ```

3. **Edit `includes/db_connect.php`** if needed:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = ""; // Add password if needed
   $dbname = "cosmetic_mart";
   ```

### Step 5: Access the Application

- **Customer Site**: `http://localhost/CosmeticMartTest/main/index.php`
- **Admin Panel**: `http://localhost/CosmeticMartTest/auth/admin_login.php`

## 📁 Project Structure

```
CosmeticMartTest/
├── admin/                    # Admin dashboard & management
│   ├── admin_dashboard.php   # Admin home page
│   ├── add_product.php       # Add new products
│   ├── edit_product.php      # Edit products
│   ├── delete_product.php    # Delete products
│   ├── view_products.php     # View all products
│   ├── view_orders.php       # View customer orders
│   ├── update_order_status.php # Update order status
│   ├── view_messages.php     # View customer messages
│   ├── delete_order.php      # Delete orders
│   └── admin_logout.php      # Admin logout
│
├── auth/                     # Authentication & Authorization
│   ├── login.php             # Customer login
│   ├── register.php          # Customer registration
│   ├── admin_login.php       # Admin login
│   ├── forgot_password.php   # Password recovery
│   ├── hash.php              # Password hashing utilities
│   └── logout.php            # User logout
│
├── main/                     # Main customer-facing pages
│   ├── index.php             # Home page
│   ├── products.php          # Products listing
│   ├── add_to_cart.php       # Add items to cart
│   ├── cart.php              # Shopping cart page
│   ├── checkout.php          # Checkout process
│   ├── esewa_payment.php     # eSewa payment integration
│   ├── order_success.php     # Order confirmation
│   ├── profile.php           # User profile
│   ├── about.php             # About page
│   └── contact.php           # Contact page
│
├── includes/                 # Shared configuration & utilities
│   ├── config.php            # Application configuration (eSewa, DB)
│   ├── config.sample.php     # Configuration sample template
│   └── db_connect.php        # Database connection
│
├── assets/                   # Static assets
│   └── image/                # Product images
│
├── users/                    # User data (if applicable)
│
├── test_connection.php       # Database connection test
├── test.php                  # Testing utilities
├── reset_password.php        # Password reset functionality
│
└── README.md                 # This file
```

## 🔧 Configuration

### eSewa Payment Gateway Setup

1. **Get eSewa Credentials**
   - Visit [eSewa Merchant Account](https://merchant.esewa.com.np/)
   - Register and get your merchant code and secret

2. **Update Configuration**
   - Edit `includes/config.php`
   - Add your credentials
   - Set environment to 'sandbox' for testing, 'production' for live

### Database Configuration

Edit `includes/db_connect.php`:

```php
$servername = "localhost";      // Database host
$username = "root";             // Database username
$password = "";                 // Database password (if any)
$dbname = "cosmetic_mart";      // Database name
```

## 📊 Database Setup

### Create Required Tables

```sql
-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Messages table
CREATE TABLE messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 📖 Usage

### For Customers

1. **Browse Products**
   - Go to `http://localhost/CosmeticMartTest/main/index.php`
   - Navigate to Products section

2. **Create Account**
   - Click on Register
   - Fill in your details

3. **Shopping**
   - Add products to cart
   - Proceed to checkout
   - Complete payment via eSewa

### For Admins

1. **Login to Admin Panel**
   - Go to `http://localhost/CosmeticMartTest/auth/admin_login.php`
   - Use admin credentials

2. **Manage Content**
   - Add/Edit/Delete products
   - View and manage orders
   - Respond to customer messages

3. **View Dashboard**
   - Access `admin_dashboard.php` for overview

## 🤝 Contributing

We welcome contributions! Follow these steps:

### 1. Fork the Repository
```bash
git clone https://github.com/yourusername/CosmeticMartTest.git
```

### 2. Create a Feature Branch
```bash
git checkout -b feature/your-feature-name
```

### 3. Make Changes
- Follow the existing code style
- Add comments for complex logic
- Test your changes locally

### 4. Commit Changes
```bash
git commit -m "Add: description of your changes"
```

Use commit prefixes:
- `Add:` for new features
- `Fix:` for bug fixes
- `Update:` for updates/improvements
- `Refactor:` for code refactoring
- `Docs:` for documentation changes

### 5. Push to Branch
```bash
git push origin feature/your-feature-name
```

### 6. Open Pull Request
- Describe your changes
- Reference any related issues
- Wait for review

## ✅ Code Style Guidelines

- Use meaningful variable and function names
- Add comments for complex logic
- Follow PHP PSR-2 coding standards
- Use prepared statements for database queries
- Always sanitize user inputs
- Use sessions for authentication

## 🐛 Troubleshooting

### Database Connection Issues

**Problem**: "Connection failed" error

**Solution**:
1. Ensure MySQL is running in XAMPP
2. Check credentials in `includes/db_connect.php`
3. Verify database name exists

```bash
# Test connection
php test_connection.php
```

### Session Issues

**Problem**: Login not working or sessions not persisting

**Solution**:
1. Ensure `session_start()` is at the top of files
2. Check PHP session settings in `php.ini`
3. Clear browser cookies and cache

### eSewa Payment Issues

**Problem**: Payment gateway not working

**Solution**:
1. Verify merchant code and secret in `includes/config.php`
2. Ensure environment is set correctly (sandbox vs production)
3. Check eSewa API status

### File Upload Issues

**Problem**: Unable to upload product images

**Solution**:
1. Check `assets/image/` folder permissions (755)
2. Verify file size limits in `php.ini`
3. Ensure correct image formats (jpg, png, gif)

## 📝 License

This project is licensed under the MIT License - see LICENSE file for details.

## 👥 Authors

- **Development Team** - Initial work on Cosmetic Mart project

## 📞 Support

For issues and questions:
- Open an issue on GitHub
- Contact the admin via the Contact page
- Check existing documentation

## 🔐 Security Notes

- **Never** commit `config.php` with real credentials to repository
- Always use HTTPS in production
- Implement proper input validation
- Use prepared statements for all database queries
- Keep dependencies updated
- Regular security audits

## 🚀 Future Enhancements

- [ ] Mobile app version
- [ ] Advanced product filtering
- [ ] Wishlist functionality
- [ ] Customer reviews and ratings
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Multi-language support
- [ ] API documentation

---

**Last Updated**: May 2026

**Status**: Active Development

For the latest updates, please check the [GitHub repository](https://github.com/Kritika-Duwal/CosmeticMartTest).
