# Commitira To-Do App

A simple **PHP To-Do List app** with user authentication.  
Users can register, log in, and manage their personal tasks.

---

## Features

- User registration with secure password hashing
- Login / logout functionality
- Add, edit, delete, and mark tasks as complete
- Clean and simple UI using CSS & JS
- MySQL database integration

---

## How to Run

1. Clone the repository:

2. Import the database:
- Open `phpMyAdmin`.
- Create a database (e.g., `todo_app`).
- Import the SQL file located at: `database/todo_app.sql`.

3. Update database connection:
- Open `database.php`.
- Add your database connection details (host, username, password, database name).

4. Run the app:
- Place the project in your server directory (e.g., `htdocs` if using XAMPP).
- Open your browser at `http://localhost/Commitira-todo-app/`.

---

## Notes

- The `database.php` file is empty; you need to fill it with your DB connection settings.
- Make sure your server (e.g., XAMPP) is running Apache and MySQL.
