Basic PHP Shopping Site

Quick start:
- Install XAMPP/WAMP and enable Apache + MySQL
- Create a database (e.g., `shopdb`) and import `db/dump.sql`
- Update DB credentials in `includes/db.php`
- Place the `public` folder as your web root or point Apache to it
- Visit `/` to browse products

Features:
- Registration/login (passwords hashed for new users)
- Product listing and categories
- Session-based cart
- Checkout creating simple orders
- Order history

Notes:
- This is a minimal example for local development only.
- For production: enable HTTPS, use stronger input validation, CSRF protection, and a proper framework like Laravel.
