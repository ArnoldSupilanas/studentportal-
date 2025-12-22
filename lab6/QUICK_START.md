# Quick Start Guide - RBAC Implementation

## 🚀 Get Started in 5 Minutes

### Step 1: Run Database Migration (1 minute)
```bash
cd c:\xampp\htdocs\ITE311-SUPILANAS
php spark migrate
```

### Step 2: Create Test Users (2 minutes)
Open phpMyAdmin: `http://localhost/phpmyadmin`

Run this SQL:
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

**Password for all:** `Test1234`

### Step 3: Test the Application (2 minutes)

1. **Open:** `http://localhost/ITE311-SUPILANAS/login`

2. **Test Admin:**
   - Login: admin@test.com / Test1234
   - See admin dashboard with user statistics
   - Check admin menu in navigation

3. **Test Teacher:**
   - Logout and login: teacher@test.com / Test1234
   - See teacher dashboard
   - Check teacher menu in navigation

4. **Test Student:**
   - Logout and login: student@test.com / Test1234
   - See student dashboard
   - Check student menu items

### Step 4: Test Security Features

✅ **Try accessing admin route as student:**
   - Login as student
   - Visit: `http://localhost/ITE311-SUPILANAS/admin/dashboard`
   - Should be blocked with error message

✅ **Test password validation:**
   - Go to: `http://localhost/ITE311-SUPILANAS/register`
   - Try weak password: "test123" (should fail)
   - Try strong password: "Test1234" (should work)

✅ **Test CSRF protection:**
   - Forms have CSRF tokens
   - Check browser cookies for csrf_cookie_name

## 📝 What to Test

### ✅ Authentication
- [ ] Register new user
- [ ] Login with correct credentials
- [ ] Login with wrong credentials (should fail)
- [ ] Access dashboard without login (should redirect)

### ✅ Authorization
- [ ] Student cannot access admin routes
- [ ] Student cannot access teacher routes
- [ ] Teacher cannot access admin routes
- [ ] Admin can access all routes

### ✅ Dashboard Content
- [ ] Admin sees: user stats, management cards, recent users table
- [ ] Teacher sees: student stats, course management cards
- [ ] Student sees: course, assignment, grade cards

### ✅ Navigation
- [ ] Admin has admin dropdown menu
- [ ] Teacher has teacher dropdown menu
- [ ] Student has direct menu links (no dropdown)
- [ ] All have user profile dropdown with logout

### ✅ Security
- [ ] CSRF tokens present on forms
- [ ] Password must meet complexity requirements
- [ ] Session expires after 2 hours
- [ ] Logout destroys session

## 🐛 Common Issues

**Issue:** Migration fails
```bash
# Solution: Check database connection
# Edit: app/Config/Database.php
# Then run: php spark migrate:refresh
```

**Issue:** Can't login
```bash
# Solution: Check if user exists in database
# Verify password hash is correct
# Clear browser cookies
```

**Issue:** Dashboard shows wrong content
```bash
# Solution: Check session data
# Logout and login again
# Verify role in database
```

## 📚 Full Documentation

- **Detailed Testing:** See `RBAC_IMPLEMENTATION_GUIDE.md`
- **Git Workflow:** See `GITHUB_COMMIT_GUIDE.md`
- **Project Info:** See `README.md`

## 🎯 Next Steps

1. ✅ Test all features (use checklist above)
2. ✅ Read full documentation
3. ✅ Commit to GitHub (follow GITHUB_COMMIT_GUIDE.md)
4. ✅ Submit project

---

**Need Help?** Check `RBAC_IMPLEMENTATION_GUIDE.md` for detailed troubleshooting.

**Ready to commit?** Follow the 8-commit strategy in `GITHUB_COMMIT_GUIDE.md`.
