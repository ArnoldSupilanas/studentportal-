# Role-Based Access Control (RBAC) Implementation Guide

## Overview
This document provides comprehensive testing instructions and implementation details for the Role-Based Access Control system in the Student Portal application.

## Implementation Date
October 30, 2025

## Features Implemented

### 1. Database Schema Updates
- ✅ Updated users table migration to support roles: `admin`, `teacher`, `student`
- ✅ Role column with ENUM type for data integrity
- ✅ Default role set to `student` for new registrations

### 2. Enhanced Authentication System
- ✅ Secure password hashing with BCRYPT (cost: 12)
- ✅ Password complexity requirements:
  - Minimum 8 characters
  - At least one uppercase letter
  - At least one lowercase letter
  - At least one number
- ✅ CSRF protection enabled globally
- ✅ Session regeneration on login (prevents session fixation)
- ✅ Account status validation (active/inactive/suspended)
- ✅ Session timeout (2 hours)

### 3. Unified Dashboard with Role-Based Content
- ✅ Single dashboard route for all users (`/dashboard`)
- ✅ Conditional content display based on user role:
  - **Admin**: User management, system statistics, recent users table
  - **Teacher**: Course management, assignment creation, student overview
  - **Student**: Course enrollment, assignments, grades
- ✅ Role-specific data fetching from database

### 4. Dynamic Navigation System
- ✅ Template-based header with role-specific menu items
- ✅ Admin menu: Manage Users, Courses, Announcements, Reports, Settings
- ✅ Teacher menu: My Courses, Create Assignment, View Students, Grade Book
- ✅ Student menu: My Courses, Assignments, Grades
- ✅ User profile dropdown with logout functionality

### 5. Security Enhancements
- ✅ CSRF token randomization enabled
- ✅ Input validation and sanitization
- ✅ SQL injection prevention (using CodeIgniter Query Builder)
- ✅ XSS protection (using `esc()` function in views)
- ✅ Secure headers filter enabled
- ✅ Authentication filter for protected routes
- ✅ Role-based authorization filter

### 6. Route Configuration
- ✅ Organized routes by access level (public, protected, role-specific)
- ✅ Filter-based route protection
- ✅ RESTful route structure for future expansion

---

## Testing Instructions

### Prerequisites
1. Ensure XAMPP is running (Apache and MySQL)
2. Database is configured in `app/Config/Database.php`
3. Run migrations to update database schema

### Step 1: Run Database Migrations

```bash
# Navigate to project root
cd c:\xampp\htdocs\ITE311-SUPILANAS

# Run migrations
php spark migrate
```

Expected output:
```
Running: 2025-10-30-052600_UpdateUsersTableRoleColumn
Migrated: 2025-10-30-052600_UpdateUsersTableRoleColumn
```

### Step 2: Create Test Users

**Option A: Using phpMyAdmin**
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select your database
3. Go to the `users` table
4. Insert test users:

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

**Option B: Using Registration Form**
1. Navigate to: `http://localhost/ITE311-SUPILANAS/register`
2. Register new users (they will be created with `student` role by default)
3. Manually update role in database for admin/teacher testing

### Step 3: Test Authentication Flow

#### Test Case 1: User Registration
1. Navigate to: `http://localhost/ITE311-SUPILANAS/register`
2. Fill in the registration form:
   - Name: John Doe
   - Email: john@test.com
   - Password: Test1234 (must meet complexity requirements)
   - Confirm Password: Test1234
3. Click "Register"
4. **Expected Result**: 
   - Success message: "Registration successful! Please login."
   - Redirected to login page
   - User created in database with `student` role

#### Test Case 2: Login Validation
1. Navigate to: `http://localhost/ITE311-SUPILANAS/login`
2. Try logging in with incorrect credentials
3. **Expected Result**: Error message "Invalid email or password"
4. Try logging in with correct credentials
5. **Expected Result**: 
   - Success message: "Welcome back, [Name]!"
   - Redirected to `/dashboard`
   - Session created with user data and role

#### Test Case 3: Session Security
1. After logging in, check browser cookies
2. **Expected Result**: CSRF cookie present
3. Try accessing `/dashboard` without logging in (use incognito mode)
4. **Expected Result**: Redirected to `/login` with error message

### Step 4: Test Role-Based Dashboard Content

#### Admin Dashboard Test
1. Login as admin user (admin@test.com / Test1234)
2. Navigate to: `http://localhost/ITE311-SUPILANAS/dashboard`
3. **Expected Results**:
   - Page title: "Admin Dashboard"
   - Statistics cards showing:
     - Total Users count
     - Total Students count
     - Total Teachers count
     - Admins count (1)
   - Admin action cards: Manage Users, Manage Courses, View Reports
   - Recent Users table with user data
   - Admin dropdown menu in navigation

#### Teacher Dashboard Test
1. Logout and login as teacher user (teacher@test.com / Test1234)
2. Navigate to: `http://localhost/ITE311-SUPILANAS/dashboard`
3. **Expected Results**:
   - Page title: "Teacher Dashboard"
   - Statistics cards showing:
     - Total Students count
     - My Courses (0)
     - Assignments (0)
   - Teacher action cards: My Courses, Create Assignment, View Students
   - Teacher dropdown menu in navigation

#### Student Dashboard Test
1. Logout and login as student user (student@test.com / Test1234)
2. Navigate to: `http://localhost/ITE311-SUPILANAS/dashboard`
3. **Expected Results**:
   - Page title: "Student Dashboard"
   - Student action cards: My Courses, Assignments, Grades
   - Student navigation menu items (no dropdown)
   - Info alert showing role-based access control message

### Step 5: Test Role-Based Navigation

#### Navigation Menu Tests
1. **As Admin**: Check navigation bar contains:
   - Home, Dashboard
   - Admin dropdown with: Manage Users, Manage Courses, Announcements, Reports, Settings
   - User profile dropdown with: Profile, Settings, Logout

2. **As Teacher**: Check navigation bar contains:
   - Home, Dashboard
   - Teacher dropdown with: My Courses, Create Assignment, View Students, Grade Book
   - User profile dropdown

3. **As Student**: Check navigation bar contains:
   - Home, Dashboard
   - My Courses, Assignments, Grades (direct links, no dropdown)
   - User profile dropdown

### Step 6: Test Access Control

#### Unauthorized Access Tests

**Test 1: Student trying to access Admin route**
1. Login as student
2. Try to access: `http://localhost/ITE311-SUPILANAS/admin/dashboard`
3. **Expected Result**: 
   - Redirected to `/dashboard`
   - Error message: "Access Denied: You do not have permission to access this page"

**Test 2: Student trying to access Teacher route**
1. Login as student
2. Try to access: `http://localhost/ITE311-SUPILANAS/teacher/dashboard`
3. **Expected Result**: 
   - Redirected to `/dashboard`
   - Error message: "Access Denied: Insufficient Permissions"

**Test 3: Teacher trying to access Admin route**
1. Login as teacher
2. Try to access: `http://localhost/ITE311-SUPILANAS/admin/users`
3. **Expected Result**: 
   - Redirected to `/dashboard`
   - Error message: "Access Denied: You do not have permission to access this page"

**Test 4: Unauthenticated user trying to access Dashboard**
1. Logout or use incognito mode
2. Try to access: `http://localhost/ITE311-SUPILANAS/dashboard`
3. **Expected Result**: 
   - Redirected to `/login`
   - Error message: "Please login to access the dashboard"

### Step 7: Test Security Features

#### CSRF Protection Test
1. Login to the application
2. Open browser developer tools (F12)
3. Go to Application/Storage > Cookies
4. **Expected Result**: `csrf_cookie_name` cookie present
5. Try submitting a form without CSRF token
6. **Expected Result**: Request blocked with CSRF error

#### Password Complexity Test
1. Navigate to registration page
2. Try registering with weak passwords:
   - "test" (too short)
   - "testtest" (no uppercase or number)
   - "TESTTEST" (no lowercase or number)
   - "Test1234" (valid)
3. **Expected Results**: Validation errors for weak passwords, success for valid password

#### Session Timeout Test
1. Login to the application
2. Wait for 2 hours (or modify timeout in AuthFilter.php for testing)
3. Try to access a protected page
4. **Expected Result**: 
   - Session expired
   - Redirected to login
   - Error message: "Your session has expired. Please login again."

#### Account Status Test
1. In database, set a user's status to 'suspended'
2. Try to login with that user
3. **Expected Result**: Error message "Your account has been suspended. Please contact administrator."

### Step 8: Test Logout Functionality

1. Login as any user
2. Click "Logout" from user dropdown menu
3. **Expected Results**:
   - Session destroyed
   - Redirected to `/login`
   - Cannot access protected pages without logging in again
   - Browser back button doesn't allow access to protected pages

---

## Security Vulnerabilities Addressed

### 1. SQL Injection Prevention
- ✅ Using CodeIgniter Query Builder (parameterized queries)
- ✅ No raw SQL queries with user input
- ✅ Input validation before database operations

### 2. Cross-Site Scripting (XSS) Prevention
- ✅ All user output escaped using `esc()` function
- ✅ HTML special characters converted to entities
- ✅ Content Security Policy headers enabled

### 3. Cross-Site Request Forgery (CSRF) Prevention
- ✅ CSRF tokens on all forms
- ✅ Token randomization enabled
- ✅ Token validation on POST requests
- ✅ Automatic token regeneration

### 4. Session Security
- ✅ Session regeneration on login (prevents session fixation)
- ✅ Session timeout (2 hours)
- ✅ Secure session configuration
- ✅ HttpOnly and Secure flags on cookies (in production)

### 5. Password Security
- ✅ Strong password requirements (8+ chars, mixed case, numbers)
- ✅ BCRYPT hashing with cost factor 12
- ✅ No password storage in plain text
- ✅ Password confirmation on registration

### 6. Authentication & Authorization
- ✅ Proper authentication checks before granting access
- ✅ Role-based authorization on all protected routes
- ✅ Account status validation
- ✅ Redirect to login for unauthenticated users

### 7. Input Validation
- ✅ Server-side validation for all forms
- ✅ Email format validation
- ✅ Data type validation
- ✅ Length constraints enforced

---

## File Structure

```
ITE311-SUPILANAS/
├── app/
│   ├── Config/
│   │   ├── Filters.php          (Filter registration and CSRF config)
│   │   ├── Routes.php           (Role-based route configuration)
│   │   └── Security.php         (CSRF and security settings)
│   ├── Controllers/
│   │   └── Auth.php             (Enhanced authentication controller)
│   ├── Database/
│   │   └── Migrations/
│   │       └── 2025-10-30-052600_UpdateUsersTableRoleColumn.php
│   ├── Filters/
│   │   ├── AuthFilter.php       (Authentication filter)
│   │   └── RoleAuth.php         (Role-based authorization filter)
│   ├── Models/
│   │   └── UserModel.php        (User model with validation)
│   └── Views/
│       ├── auth/
│       │   ├── dashboard.php    (Unified role-based dashboard)
│       │   ├── login.php        (Login form with CSRF)
│       │   └── register.php     (Registration form with CSRF)
│       └── templates/
│           ├── header.php       (Dynamic navigation header)
│           └── footer.php       (Footer template)
└── RBAC_IMPLEMENTATION_GUIDE.md (This file)
```

---

## Common Issues and Troubleshooting

### Issue 1: CSRF Token Mismatch
**Symptom**: Form submission fails with CSRF error
**Solution**: 
- Clear browser cookies
- Ensure `<?= csrf_field() ?>` is present in all forms
- Check that CSRF protection is enabled in `Config/Filters.php`

### Issue 2: Redirect Loop
**Symptom**: Page keeps redirecting
**Solution**:
- Check filter configuration in `Config/Filters.php`
- Ensure login/register routes are not protected by auth filter
- Clear session data

### Issue 3: Role Not Detected
**Symptom**: User logged in but role-based content not showing
**Solution**:
- Check session data: `var_dump(session()->get());`
- Verify role is stored in session during login
- Check database for correct role value

### Issue 4: Migration Fails
**Symptom**: Migration error when running `php spark migrate`
**Solution**:
- Check if users table exists
- Verify database connection in `Config/Database.php`
- Try rolling back: `php spark migrate:rollback`
- Re-run migration

---

## Next Steps for Enhancement

1. **Email Verification**: Add email verification for new registrations
2. **Password Reset**: Implement forgot password functionality
3. **Two-Factor Authentication**: Add 2FA for enhanced security
4. **Activity Logging**: Track user actions for audit trail
5. **Role Permissions**: Implement granular permissions within roles
6. **API Authentication**: Add JWT tokens for API routes
7. **Rate Limiting**: Prevent brute force attacks on login
8. **Account Lockout**: Lock account after multiple failed login attempts

---

## Conclusion

The Role-Based Access Control system has been successfully implemented with comprehensive security measures. All users are redirected to a unified dashboard that displays role-specific content and functionality. The system prevents unauthorized access through authentication and authorization filters, and protects against common web vulnerabilities.

**Implementation Status**: ✅ Complete
**Security Status**: ✅ Secured
**Testing Status**: ⏳ Ready for Testing

---

## Support

For issues or questions, please refer to:
- CodeIgniter 4 Documentation: https://codeigniter.com/user_guide/
- Project Repository: [Your GitHub Repository URL]
