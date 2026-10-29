# Pastry System (PHP + PDO + MySQL)

Parent entity: **pastries**. Child entity: **ingredients**. Includes user registration, login, logout, and audit tracking (`added_by`, `last_updated_by`, `last_updated`).

## Setup (XAMPP/WAMP)
1. Copy this folder into `htdocs` (e.g. `htdocs/pastry_system`).
2. Start Apache and MySQL, open phpMyAdmin, and import `schema.sql`.
3. Edit `db.php` if your MySQL credentials differ.
4. Visit `http://localhost/pastry_system/register.php`, create an account, then log in.

## Files
- `schema.sql` - users, pastries, ingredients tables
- `register.php`, `login.php`, `logout.php` - authentication
- `auth.php` - session guard (redirects to login if not logged in)
- `index.php`, `edit_pastry.php` - parent CRUD
- `ingredients.php`, `edit_ingredient.php` - child CRUD
- `header.php`, `footer.php`, `style.css` - layout and styling

To adapt to your own business, rename the tables/columns in `schema.sql` and the matching queries.
