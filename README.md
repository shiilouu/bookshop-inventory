# Bookshop Inventory System

PHP + MySQL inventory website for our Webdev and DB Admin finals.

Please read this before you start coding so we don't overwrite each other's work.

## Branches

We have 5 branches:

- `main` - final version. Don't touch this.
- `staging` - where I merge and test everyone's work. Don't push here directly.
- `group1-auth-catalog` - login, register, layout, categories, books, inventory monitoring
- `group2-transactions-alerts` - stock in, stock out, transaction history, low-stock alerts
- `group3-dashboard-db` - admin dashboard, database

Work only on your group's branch. When your part is ready, open a Pull Request to `staging` and I'll review and merge it.

## Who does what

**group1-auth-catalog**

Kimberly Homboy (Front-End)
- includes/header.php, sidebar.php, footer.php
- index.php, login.php, register.php (UI)
- modules/categories/manage-categories.php
- modules/books/manage-books.php, inventory-monitoring.php (UI)
- assets/css/style.css, assets/js/main.js

Benedict Rodriguez (Back-End)
- login.php, register.php, logout.php (PHP)
- auth/check_auth.php
- demo accounts

Lourd Leal Cruz (Back-End)
- modules/categories/category-action.php
- modules/books/book-action.php
- inventory-monitoring.php (PHP search and filter)

**group2-transactions-alerts**

Zeejay Isabela (Front-End)
- modules/transactions/stock-in.php, stock-out.php, transaction-history.php (UI)
- low-stock alert badge/panel
- modules/dashboard/admin-dashboard.php (UI)

Kenneth Fernandez (Back-End)
- modules/transactions/transaction-action.php
- transaction-history.php (PHP filters)

Jersha Tumalad (Data Admin)
- stored procedures, triggers, views (SQL files in database/)

**group3-dashboard-db**

Ace Molbog (Back-End)
- admin-dashboard.php (PHP, connects to the views)
- low-stock notifications (PHP)
- project documentation

Neo Penaflor (Data Admin)
- database/schema.sql
- ERD, data dictionary, online database setup

**Lead Dev (Shelo)**
- config/db.php
- reviewing and merging, deployment
- If you need to change anything in config/, tell me first.

Some files are shared between front-end and back-end (e.g. login.php). Front-end builds the HTML first, then back-end adds the PHP. Talk to each other before editing the same file.

Only `config/`, `auth/`, `includes/` and `modules/` exist right now. Just create the files and folders for your part.

## Naming rules

- Database tables, columns, procedures, triggers, views and form `name` attributes: snake_case (`minimum_stock`, `stock_transactions`)
- PHP files: kebab-case (`manage-books.php`, `stock-in.php`), except index.php, login.php, register.php, logout.php, db.php, check_auth.php
- PHP variables: camelCase (`$bookList`, `$isLoggedIn`, `$errorMsg`)
- No spaces or mixed styles in form names or SQL columns (not `bookTitle`, `Book_Title` or `min stock`)

Use the exact table, column, form and session names from our group doc so everyone's parts connect.

Session keys:
- `$_SESSION['user_id']`, `$_SESSION['full_name']`, `$_SESSION['username']`, `$_SESSION['role']` ('admin' or 'staff')
- `$_SESSION['success_msg']`, `$_SESSION['error_msg']` for messages

## Setup (first time only)

1. Install Git and send me your GitHub username so I can add you.

2. Clone the repo:

```
git clone https://github.com/OWNER/bookshop-inventory.git
cd bookshop-inventory
```

3. Switch to your group's branch:

```
git checkout group1-auth-catalog
```

4. Database setup:
   - Open XAMPP, start Apache and MySQL
   - Go to localhost/phpmyadmin and create a database named `bookshop_inventory`
   - In the config folder, copy `config.local.example.php` and rename it to `config.local.php`
   - If you're using default XAMPP settings, no need to edit it

`config.local.php` won't be uploaded to GitHub, so don't worry about passwords.

5. To connect a page to the database, put this at the top:

```php
require_once __DIR__ . '/../../config/db.php';  // files inside modules/<folder>/
require_once __DIR__ . '/config/db.php';        // files in the main folder
```

Then use `$conn` for your queries. Use prepared statements, not plain queries with variables inside.

The database is empty for now. Once Neo finishes schema.sql, import it in phpMyAdmin so we all have the same tables.

## Every time you work

Before you start:

```
git pull origin staging
```

When you're done:

```
git add .
git commit -m "what you did"
git push
```

## Pull Request

When your part is working:

1. Go to the repo on GitHub > Pull requests > New pull request
2. base: `staging`, compare: your group's branch
3. Create pull request

I'll check it and merge. If something needs fixing I'll comment on it.

## Rules

- Don't push to main or staging
- Only edit your own files
- Don't upload passwords
- Test your pages before making a PR
- Message me if you get stuck or get an error you can't fix

## Folder structure (once everything is added)

```
bookshop-inventory/
├── index.php
├── login.php
├── register.php
├── logout.php
├── config/
├── auth/
├── includes/
├── modules/
│   ├── categories/
│   ├── books/
│   ├── transactions/
│   └── dashboard/
├── assets/
└── database/
```
