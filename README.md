# M&N Electronics 🛒

M&N Electronics is a web-based electronics shopping application developed using **PHP, MySQL, HTML, CSS, and JavaScript**, and hosted locally using **XAMPP**. The platform supports both **user** and **admin** roles, offering complete e-commerce functionality including product browsing, cart management, order processing, and admin controls.

---

## 🚀 Features

### 👤 User Features
- User registration and login
- Browse electronic products
- Add products to cart and update quantities
- Place orders with confirmation message
- View order history under **My Orders**
- Update personal profile details
- Secure logout

### 🛠️ Admin Features
- Admin dashboard after login
- Add, edit, and delete products
- View and manage all user orders
- Update delivery status of orders
- View and manage registered users
- Modify user details (name, email, role)
- Update admin profile details
- Logout functionality

---

## 🖥️ Technologies Used

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Server:** Apache (XAMPP)

---

## 📂 Project Structure

```
M&N-Electronics/
│
├── images/
│   ├── products/        # Product images
│   └── users/           # User profile images
│
├── admin_dashboard.php
├── admin_orders.php
├── admin_product_delete.php
├── admin_product_form.php
├── admin_products.php
├── cart.php
├── carousel.php
├── conn.php
├── connection.php
├── database.sql
├── delete.php
├── edit.php
├── edit_user.php
├── footer.php
├── header.php
├── index.php
├── listUsers.php
├── logout.php
├── menu.php
├── my_orders.php
├── products.php
├── registration.php
├── update.php
├── update_user.php
│
└── README.md
```

---

## ⚙️ Setup Instructions (Using XAMPP)

### 1️⃣ Install XAMPP
Download and install XAMPP from:
https://www.apachefriends.org/

Start **Apache** and **MySQL** from the XAMPP Control Panel.

---

### 2️⃣ Clone or Copy Project
Place the project folder inside:
```
xampp/htdocs/
```

---

### 3️⃣ Database Setup
1. Open **phpMyAdmin**
   ```
   http://localhost/phpmyadmin
   ```
2. Create a new database (e.g., `online_electronics_store`)
3. Import the `database.sql` file included in the project

---

### 4️⃣ Configure Database Connection
Check and update credentials in:
- `conn.php`
- `connection.php`

---

### 5️⃣ Run the Application
Open your browser and go to:
```
http://localhost/M&N-Electronics/index.php
```

---

## 👩‍💻 Authors
**Namita Sampath, Mahima Krishnamurthy**  

---

Screenshots:
<img width="1873" height="783" alt="Screenshot 2026-01-21 143631" src="https://github.com/user-attachments/assets/09dd57fd-10ae-4281-bcb0-5be2c7620e82" />

<img width="1906" height="812" alt="image" src="https://github.com/user-attachments/assets/d7e214a8-1535-4579-89fd-eb6ad0478a48" />

<img width="1884" height="765" alt="image" src="https://github.com/user-attachments/assets/280965c5-4d35-4814-b39d-039a86fe2312" />
