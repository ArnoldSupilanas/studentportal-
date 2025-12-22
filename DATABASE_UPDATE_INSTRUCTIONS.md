# Database Update Instructions

## Quick Fix: Update User Roles

Since you already have a `users` table with 'instructor' role, you need to update it to 'teacher' for the RBAC system to work correctly.

### Option 1: Using phpMyAdmin (Recommended - 1 minute)

1. **Open phpMyAdmin**: `http://localhost/phpmyadmin`

2. **Select your database**: `lms_supilanas`

3. **Click on SQL tab** at the top

4. **Run this SQL command**:
```sql
UPDATE users SET role = 'teacher' WHERE role = 'instructor';
```

5. **Verify the update** by running:
```sql
SELECT id, first_name, last_name, email, role FROM users;
```

You should see all 'instructor' roles changed to 'teacher'.

### Option 2: Using MySQL Command Line

```bash
mysql -u root -p
USE lms_supilanas;
UPDATE users SET role = 'teacher' WHERE role = 'instructor';
SELECT id, first_name, last_name, email, role FROM users;
EXIT;
```

### Option 3: Manual Update in phpMyAdmin

1. Open phpMyAdmin
2. Select database `lms_supilanas`
3. Click on `users` table
4. Click "Browse" tab
5. Find rows with role = 'instructor'
6. Click "Edit" (pencil icon)
7. Change 'instructor' to 'teacher'
8. Click "Go" to save

## After Update

Your users table should have these roles:
- **admin** - for administrators
- **teacher** - for teachers (previously 'instructor')
- **student** - for students

## Test the Application

After updating:

1. **Login as teacher**:
   - Email: instructor@lms.com (or whatever email has teacher role)
   - Password: (your existing password)
   - You should see the Teacher Dashboard

2. **Login as admin**:
   - Email: admin@lms.com
   - You should see the Admin Dashboard with user statistics

3. **Login as student**:
   - Email: student@lms.com
   - You should see the Student Dashboard

## Current Users in Your Database

Based on the database check, you have:

| ID | Name | Email | Current Role | Should Be |
|----|------|-------|--------------|-----------|
| 1 | John Admin | admin@lms.com | admin | ✅ admin |
| 2 | Jane Instructor | instructor@lms.com | instructor | ⚠️ teacher |
| 3 | Bob Student | student@lms.com | student | ✅ student |
| 4 | Alice Smith | alice@lms.com | student | ✅ student |
| 5 | Charlie Brown | charlie@lms.com | student | ✅ student |
| 6 | Admin User | admin@example.com | admin | ✅ admin |

**Only user #2 needs to be updated from 'instructor' to 'teacher'.**

## Quick SQL Command (Copy & Paste)

```sql
-- Update instructor to teacher
UPDATE users SET role = 'teacher' WHERE role = 'instructor';

-- Verify the change
SELECT id, CONCAT(first_name, ' ', last_name) AS name, email, role 
FROM users 
ORDER BY role, id;
```

## Done!

After running the SQL update, your RBAC system will work correctly with:
- Admin users seeing admin dashboard
- Teacher users seeing teacher dashboard  
- Student users seeing student dashboard

---

**Next Step**: Test the application by logging in with different user roles!
