# Angeethi – Restaurant website (PHP + MySQL)

## XAMPP par chalane ke steps
1. XAMPP Control Panel kholein, **Apache** aur **MySQL** start karein.
2. Is `restaurant` folder ko `C:\xampp\htdocs\` mein copy karein.
3. Browser mein `http://localhost/phpmyadmin` kholein -> **Import** tab -> `database.sql` choose karein -> **Go**.
4. Website: `http://localhost/restaurant/`
5. Admin panel: `http://localhost/restaurant/admin/login.php`
   Username: `admin`  Password: `admin123` (viva se pehle badal lein)

## Features
- **User accounts:** sign up, log in, log out (customer ko login karke hi booking karni padti hai)
- **My bookings:** user apni bookings dekh sakta hai aur cancel kar sakta hai
- Admin bookings table mein customer ka email bhi dikhta hai
- Menu database se aata hai (categories + menu_items)
- Table booking form -> `reservations` table mein save (validation + prepared statements)
- Admin login (password_hash / password_verify, session, CSRF token)
- Admin: bookings confirm/cancel/delete, menu items add/hide/delete, status count

## Files
- `database.sql` – 5 tables: users, categories, menu_items, reservations, admins (import karne par database dobara ban jata hai, purana data delete hota hai)
- `register.php`, `login.php`, `logout.php`, `my-bookings.php` – customer pages
- `includes/` – shared header aur footer
- `config.php` – PDO connection + helper functions
- `index.php` – public website
- `admin/` – login, bookings dashboard, menu manager
- `assets/style.css` – styling

## Password badalna
php page mein `echo password_hash('naya_password', PASSWORD_DEFAULT);` chalayein aur `admins` table mein `password_hash` update karein.
