# ITE311-SUPILANAS - Student Portal with Role-Based Access Control

**Owner:** Arnold Supilanas  
**Course:** ITE311 - Web Systems and Technologies  
**Framework:** CodeIgniter 4  
**Implementation Date:** October 2025

## 🎯 Project Overview

A comprehensive Student Portal web application built with CodeIgniter 4, featuring a robust Role-Based Access Control (RBAC) system. The application provides a unified dashboard that dynamically displays content based on user roles (Admin, Teacher, Student) with enhanced security measures.

## ✨ Key Features

### 🔐 Role-Based Access Control (RBAC)
- **Three User Roles**: Admin, Teacher, Student
- **Unified Dashboard**: Single dashboard route with role-specific content
- **Dynamic Navigation**: Menu items change based on user role
- **Authorization Filters**: Route-level access control

### 🛡️ Security Features
- **CSRF Protection**: Enabled globally with token randomization
- **Strong Password Policy**: 8+ characters, mixed case, numbers required
- **BCRYPT Hashing**: Password encryption with cost factor 12
- **Session Security**: Session regeneration, timeout (2 hours), fixation prevention
- **Input Validation**: Server-side validation for all forms
- **XSS Prevention**: Output escaping using `esc()` function
- **SQL Injection Prevention**: Parameterized queries via Query Builder
- **Account Status Validation**: Active/Inactive/Suspended status checks

### 👥 User Roles & Capabilities

#### Admin
- View system statistics (total users, students, teachers)
- Manage users (create, edit, delete)
- Manage courses and content
- Access system reports and analytics
- Full system access

#### Teacher
- View student statistics
- Manage courses and assignments
- Create and grade assignments
- View student roster
- Access grade book

#### Student
- View enrolled courses
- Submit assignments
- Check grades and progress
- Access course materials

### 🎨 User Interface
- **Responsive Design**: Bootstrap 5 framework
- **Modern UI**: Gradient backgrounds, card-based layout
- **Role-Specific Dashboards**: Customized content per role
- **Dynamic Templates**: Reusable header/footer components
- **Bootstrap Icons**: Professional iconography

## 📋 Prerequisites

- **PHP**: Version 8.1 or higher
- **Web Server**: Apache (XAMPP recommended)
- **Database**: MySQL/MariaDB
- **Composer**: For dependency management
- **Git**: For version control

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/ArnoldSupilanas/ITE311-SUPILANAS.git
cd ITE311-SUPILANAS
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment
```bash
# Copy environment file
cp env .env

# Edit .env file and configure:
# - Database connection
# - Base URL
# - Environment (development/production)
```

### 4. Database Setup
```bash
# Run migrations
php spark migrate

# (Optional) Run seeders
php spark db:seed UserSeeder
```

### 5. Start Development Server
```bash
php spark serve
```

Access the application at: `http://localhost:8080`

## 🔧 Configuration

### Database Configuration
Edit `app/Config/Database.php`:
```php
public array $default = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'your_database_name',
    'DBDriver' => 'MySQLi',
];
```

### Security Configuration
- **CSRF Protection**: Enabled in `app/Config/Filters.php`
- **Session Timeout**: 2 hours (configurable in `app/Filters/AuthFilter.php`)
- **Password Policy**: Configured in `app/Controllers/Auth.php`

## 📁 Project Structure

```
ITE311-SUPILANAS/
├── app/
│   ├── Config/
│   │   ├── Filters.php          # Filter registration
│   │   ├── Routes.php           # Route definitions
│   │   └── Security.php         # Security settings
│   ├── Controllers/
│   │   └── Auth.php             # Authentication controller
│   ├── Database/
│   │   └── Migrations/          # Database migrations
│   ├── Filters/
│   │   ├── AuthFilter.php       # Authentication filter
│   │   └── RoleAuth.php         # Authorization filter
│   ├── Models/
│   │   └── UserModel.php        # User model
│   └── Views/
│       ├── auth/
│       │   ├── dashboard.php    # Unified dashboard
│       │   ├── login.php        # Login form
│       │   └── register.php     # Registration form
│       └── templates/
│           ├── header.php       # Header template
│           └── footer.php       # Footer template
├── public/
│   └── index.php                # Application entry point
├── RBAC_IMPLEMENTATION_GUIDE.md # Testing guide
├── GITHUB_COMMIT_GUIDE.md       # Git workflow guide
└── README.md                    # This file
```

## 🧪 Testing

### Test Users
Create test users with different roles:

```sql
-- Admin User
INSERT INTO users (first_name, last_name, email, password, role, status, created_at, updated_at)
VALUES ('Admin', 'User', 'admin@test.com', '$2y$12$LQv3c1yycEPICh0k.0Y.5.Wj2fusr4qPm.NU8Qlagrh69Q9msr8CC', 'admin', 'active', NOW(), NOW());

-- Teacher User
INSERT INTO users (first_name, last_name, email, password, role, status, created_at, updated_at)
VALUES ('Teacher', 'Smith', 'teacher@test.com', '$2y$12$LQv3c1yycEPICh0k.0Y.5.Wj2fusr4qPm.NU8Qlagrh69Q9msr8CC', 'teacher', 'active', NOW(), NOW());

-- Student User
INSERT INTO users (first_name, last_name, email, password, role, status, created_at, updated_at)
VALUES ('Student', 'Jones', 'student@test.com', '$2y$12$LQv3c1yycEPICh0k.0Y.5.Wj2fusr4qPm.NU8Qlagrh69Q9msr8CC', 'student', 'active', NOW(), NOW());
```

**Password for all test users:** `Test1234`

### Testing Checklist
- [ ] User registration with password validation
- [ ] Login with different roles
- [ ] Dashboard displays role-specific content
- [ ] Navigation menu changes per role
- [ ] Unauthorized access is blocked
- [ ] CSRF protection on forms
- [ ] Session timeout works correctly
- [ ] Logout destroys session

For detailed testing instructions, see [RBAC_IMPLEMENTATION_GUIDE.md](RBAC_IMPLEMENTATION_GUIDE.md)

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

## 📚 Documentation

- **[RBAC Implementation Guide](RBAC_IMPLEMENTATION_GUIDE.md)**: Comprehensive testing and implementation details
- **[GitHub Commit Guide](GITHUB_COMMIT_GUIDE.md)**: Version control best practices
- **[CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/)**: Framework documentation

## 🔒 Security Vulnerabilities Addressed

✅ **SQL Injection**: Prevented via parameterized queries  
✅ **XSS (Cross-Site Scripting)**: Output escaping with `esc()`  
✅ **CSRF (Cross-Site Request Forgery)**: Token-based protection  
✅ **Session Fixation**: Session regeneration on login  
✅ **Brute Force**: Account status validation  
✅ **Weak Passwords**: Strong password policy enforced  
✅ **Unauthorized Access**: Role-based authorization filters  

## 🛠️ Technologies Used

- **Backend**: CodeIgniter 4 (PHP Framework)
- **Frontend**: Bootstrap 5, Bootstrap Icons
- **Database**: MySQL/MariaDB
- **Authentication**: Session-based with BCRYPT
- **Version Control**: Git/GitHub

## 📝 Usage

### For Students
1. Register an account at `/register`
2. Login at `/login`
3. Access dashboard at `/dashboard`
4. View courses, assignments, and grades

### For Teachers
1. Login with teacher credentials
2. Access teacher dashboard
3. Manage courses and assignments
4. View and grade student work

### For Administrators
1. Login with admin credentials
2. Access admin dashboard
3. Manage users, courses, and system settings
4. View system reports and analytics

## 🚧 Future Enhancements

- [ ] Email verification for new registrations
- [ ] Password reset functionality
- [ ] Two-factor authentication (2FA)
- [ ] Activity logging and audit trail
- [ ] API endpoints with JWT authentication
- [ ] Rate limiting for login attempts
- [ ] Account lockout after failed attempts
- [ ] File upload for assignments
- [ ] Real-time notifications

## 📄 License

This project is developed for educational purposes as part of ITE311 coursework.

## 👨‍💻 Author

**Arnold Supilanas**  
ITE311 - Web Systems and Technologies  
October 2025

## 🙏 Acknowledgments

- CodeIgniter 4 Framework Team
- Bootstrap Team for UI Components
- Course Instructor for guidance and requirements

---

## 📞 Support

For issues or questions:
1. Check the [RBAC Implementation Guide](RBAC_IMPLEMENTATION_GUIDE.md)
2. Review [CodeIgniter 4 Documentation](https://codeigniter.com/user_guide/)
3. Contact the project maintainer

---

**Built with ❤️ using CodeIgniter 4**
