# 🎫 Kiwi Ticket Management

## 🔧 Prerequisites

Make sure the following tools are installed on your machine:

- PHP >= 8.2  
- Composer  
- MySQL  
- Laravel CLI (`php artisan`)  

---

## ⚙️ Project Setup

1. **Install PHP dependencies using Composer:**

composer install

2. **Setup env:**

cp .env.example .env

3. **Generate application key:**

php artisan key:generate


4. **Configure your database settings in the .env file:**

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

5. **Create tables and seed the database:**

php artisan migrate:fresh --seed

6. **Running the Application:**

php artisan serve







# group_kiwi_ticketing
