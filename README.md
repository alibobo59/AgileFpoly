# User Management CRUD System

A simple PHP CRUD (Create, Read, Update, Delete) application for user management following the DAO (Data Access Object) pattern with entity, view, controller, and routing components.

## Project Structure

```
/Agile
├── config/
│   └── database.php         # Database connection configuration
├── controller/
│   └── UserController.php   # Controller for handling user operations
├── dao/
│   └── UserDAO.php          # Data Access Object for database operations
├── entity/
│   └── User.php             # User entity class
├── view/
│   ├── layout/
│   │   └── main.php         # Main layout template
│   └── user/
│       ├── create.php       # Create user form
│       ├── edit.php         # Edit user form
│       ├── index.php        # List all users
│       └── view.php         # View user details
├── index.php                # Main entry point and router
└── README.md                # Project documentation
```

## Setup Instructions

1. Make sure you have XAMPP installed and running.
2. Create a MySQL database named `agile_user_db`.
3. Place the project files in your XAMPP htdocs directory (e.g., `/Applications/XAMPP/xamppfiles/htdocs/Fpoly/Agile/`).
4. Access the application through your web browser: `http://localhost/Fpoly/Agile/`

## Features

- **Create**: Add new users with username, email, and password
- **Read**: View a list of all users and individual user details
- **Update**: Edit existing user information
- **Delete**: Remove users from the system

## Architecture

- **Entity**: Represents the data structure
- **DAO (Data Access Object)**: Handles database operations
- **Controller**: Contains business logic and connects DAO with views
- **View**: User interface components
- **Routing**: Simple URL-based routing system

## Security Features

- Password hashing using PHP's built-in password_hash function
- Input sanitization to prevent XSS attacks
- Prepared statements to prevent SQL injection

## Requirements

- PHP 7.0 or higher
- MySQL 5.6 or higher
- XAMPP or similar PHP development environment
