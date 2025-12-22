# Authentication System Setup - Laboratory Activity

## Completed Steps

### ✅ Step 1: Project Setup
- Project location: `c:\xampp\htdocs\ITE311-SUPILANAS`
- CodeIgniter 4 project verified

### ✅ Step 2: Database Migration
- Migration file created: `app/Database/Migrations/2025-10-23-025756_CreateUsersTable.php`
- Users table schema includes:
  - `id` (INT, Primary Key, Auto Increment)
  - `name` (VARCHAR 100)
  - `email` (VARCHAR 100, Unique)
  - `password` (VARCHAR 255)
  - `role` (VARCHAR 50, Default: 'user')
  - `created_at` (DATETIME)
  - `updated_at` (DATETIME)

### ✅ Step 3: Auth Controller
- File: `app/Controllers/Auth.php`
- Methods implemented:
  - `register()` - Handles user registration
  - `login()` - Handles user authentication
  - `logout()` - Destroys user session
  - `dashboard()` - Protected page for logged-in users

### ✅ Step 4: Registration Functionality
- Form validation for name, email, password, and password_confirm
- Password hashing using `password_hash()`
- Flash messages for success/error feedback
- Redirect to login page after successful registration

### ✅ Step 5: Login Functionality
- Email and password validation
- Database user lookup
- Password verification using `password_verify()`
- Session creation with userID, name, email, and role
- Welcome flash message
- Redirect to dashboard

### ✅ Step 6: Session Management
- Logout destroys session using `session()->destroy()`
- Dashboard checks for logged-in user
- Redirects to login if not authenticated

### ✅ Step 7: Views and Routes
- Views created in `app/Views/auth/`:
  - `register.php` - Bootstrap-styled registration form
  - `login.php` - Bootstrap-styled login form
  - `dashboard.php` - Protected dashboard page
- Routes configured in `app/Config/Routes.php`:
  - GET/POST `/register` → `Auth::register`
  - GET/POST `/login` → `Auth::login`
  - GET `/logout` → `Auth::logout`
  - GET `/dashboard` → `Auth::dashboard`

### ✅ Step 8: Testing Instructions

## How to Test the Application

### 1. Start Your Local Server and Database

**Start XAMPP:**
- Open XAMPP Control Panel
- Start **Apache** (for web server)
- Start **MySQL** (for database)

### 2. Run the Migration

Open a terminal in your project root and run:

```bash
php spark migrate
```

This will create the `users` table in your database.

### 3. Start the Development Server

```bash
php spark serve
```

The application will be available at: `http://localhost:8080`

### 4. Test the Complete Flow

#### A. Register a New User
1. Navigate to: `http://localhost:8080/register`
2. Fill in the registration form:
   - Full Name: Your Name
   - Email: your.email@example.com
   - Password: password123
   - Confirm Password: password123
3. Click "Register"
4. You should see a success message and be redirected to the login page

#### B. Login with New Credentials
1. Navigate to: `http://localhost:8080/login`
2. Enter your registered email and password
3. Click "Login"
4. You should see a welcome message and be redirected to the dashboard

#### C. Access the Protected Dashboard
1. After logging in, you should be on: `http://localhost:8080/dashboard`
2. The dashboard displays:
   - Your profile information (name, email, role)
   - Navigation bar with logout option
   - Dashboard cards for courses, assignments, and grades

#### D. Test Session Protection
1. While logged in, copy the dashboard URL
2. Click "Logout" in the navigation bar
3. Try to access the dashboard URL directly
4. You should be redirected to the login page with an error message

#### E. Test Re-access After Logout
1. After logging out, try to access: `http://localhost:8080/dashboard`
2. You should be redirected to the login page
3. Login again to access the dashboard

## Features Implemented

### Security Features
- ✅ Password hashing with `password_hash()`
- ✅ Password verification with `password_verify()`
- ✅ CSRF protection (CodeIgniter built-in)
- ✅ Session-based authentication
- ✅ Protected routes (dashboard requires login)

### Validation Features
- ✅ Name: Required, 3-100 characters
- ✅ Email: Required, valid format, unique in database
- ✅ Password: Required, minimum 6 characters
- ✅ Password Confirmation: Required, must match password

### User Experience Features
- ✅ Bootstrap 5 styling with gradient design
- ✅ Bootstrap Icons for visual elements
- ✅ Flash messages for feedback
- ✅ Form validation error display
- ✅ Responsive design
- ✅ User-friendly navigation

## Database Structure

The `users` table structure:

```sql
CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(50) DEFAULT 'user',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
);
```

## Session Data Stored

When a user logs in, the following data is stored in the session:
- `userID` - User's database ID
- `name` - User's full name
- `email` - User's email address
- `role` - User's role (default: 'user')
- `is_logged_in` - Boolean flag (true)

## Routes Summary

| Method | Route | Controller Method | Description |
|--------|-------|-------------------|-------------|
| GET | `/register` | `Auth::register` | Display registration form |
| POST | `/register` | `Auth::register` | Process registration |
| GET | `/login` | `Auth::login` | Display login form |
| POST | `/login` | `Auth::login` | Process login |
| GET | `/logout` | `Auth::logout` | Logout user |
| GET | `/dashboard` | `Auth::dashboard` | Protected dashboard |

## Troubleshooting

### Database Connection Error
If you see "Unable to connect to the database":
1. Make sure XAMPP MySQL is running
2. Check your `.env` file for correct database credentials
3. Verify the database exists in phpMyAdmin

### Migration Already Ran
If you see "Nothing to migrate":
- The migration has already been executed
- Check your database to verify the `users` table exists

### Session Not Working
If login doesn't persist:
1. Check that session is enabled in `app/Config/App.php`
2. Verify writable permissions on `writable/session/` directory

## Next Steps

You can extend this authentication system by:
1. Adding email verification
2. Implementing password reset functionality
3. Adding role-based access control (RBAC)
4. Creating user profile management
5. Adding remember me functionality
6. Implementing two-factor authentication (2FA)

## Laboratory Activity Completion

All steps from the laboratory activity have been successfully implemented:
- ✅ Database migration created and ready to run
- ✅ Auth controller with all required methods
- ✅ Registration functionality with validation
- ✅ Login functionality with password verification
- ✅ Session management and logout
- ✅ Bootstrap-styled views (register, login, dashboard)
- ✅ Routes configured
- ✅ Ready for testing

**Status: READY FOR TESTING** 🚀
