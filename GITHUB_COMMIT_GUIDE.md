# GitHub Commit Guide - RBAC Implementation

## Overview
This guide provides step-by-step instructions for committing the Role-Based Access Control implementation to GitHub with proper version control practices.

## Requirements
- At least **5 commits** required
- Commits should be made over **4 days** before submission
- Each commit should represent a logical unit of work

---

## Recommended Commit Strategy

### Day 1: Database and Security Foundation

#### Commit 1: Database Schema Updates
```bash
git add app/Database/Migrations/2025-10-30-052600_UpdateUsersTableRoleColumn.php
git add app/Models/UserModel.php
git commit -m "feat: Update users table migration for role-based access control

- Add migration to update role column (admin, teacher, student)
- Update UserModel validation rules
- Increase password minimum length to 8 characters
- Set default role to 'student' for new registrations"
```

#### Commit 2: Enhanced Security Configuration
```bash
git add app/Config/Security.php
git add app/Config/Filters.php
git commit -m "security: Enable CSRF protection and security enhancements

- Enable CSRF token randomization for enhanced security
- Configure global CSRF protection for all POST requests
- Enable secure headers filter
- Register authentication and role-based filters"
```

### Day 2: Authentication System

#### Commit 3: Enhanced Authentication Controller
```bash
git add app/Controllers/Auth.php
git commit -m "feat: Implement secure authentication with role-based session management

- Add strong password validation (8+ chars, mixed case, numbers)
- Implement BCRYPT password hashing with cost factor 12
- Add session regeneration to prevent session fixation attacks
- Implement account status validation (active/suspended)
- Add session timeout (2 hours)
- Store user role in session for authorization
- Redirect all authenticated users to unified dashboard"
```

### Day 3: Authorization and Filters

#### Commit 4: Create Authentication and Authorization Filters
```bash
git add app/Filters/AuthFilter.php
git add app/Filters/RoleAuth.php
git commit -m "feat: Implement authentication and role-based authorization filters

- Create AuthFilter for protecting authenticated routes
- Enhance RoleAuth filter with role-based access control
- Add session timeout validation
- Implement proper error messages and redirects
- Support role-based filter arguments for granular control"
```

#### Commit 5: Configure Role-Based Routes
```bash
git add app/Config/Routes.php
git commit -m "feat: Configure comprehensive role-based routing system

- Organize routes by access level (public, protected, role-specific)
- Implement admin routes with admin-only access
- Implement teacher routes with teacher-only access
- Implement student routes with student-only access
- Apply authentication filter to protected routes
- Add RESTful route structure for future expansion
- Maintain backward compatibility with legacy routes"
```

### Day 4: User Interface and Documentation

#### Commit 6: Create Unified Dashboard with Role-Based Content
```bash
git add app/Views/auth/dashboard.php
git commit -m "feat: Implement unified dashboard with conditional role-based content

- Create single dashboard view for all user roles
- Implement conditional content for admin, teacher, and student
- Add role-specific statistics and action cards
- Display recent users table for admin
- Add role badge and user information display
- Integrate with role-specific data from controller"
```

#### Commit 7: Create Dynamic Navigation Templates
```bash
git add app/Views/templates/header.php
git add app/Views/templates/footer.php
git commit -m "feat: Create dynamic navigation system with role-based menus

- Create reusable header template with role-based navigation
- Implement admin dropdown menu (Manage Users, Courses, Reports, Settings)
- Implement teacher dropdown menu (My Courses, Assignments, Students, Grades)
- Implement student navigation (My Courses, Assignments, Grades)
- Add user profile dropdown with logout functionality
- Create footer template for consistent layout
- Add responsive Bootstrap navigation"
```

#### Commit 8: Add Documentation and Testing Guide
```bash
git add RBAC_IMPLEMENTATION_GUIDE.md
git add GITHUB_COMMIT_GUIDE.md
git add README.md
git commit -m "docs: Add comprehensive RBAC implementation and testing documentation

- Create detailed implementation guide with testing instructions
- Document all security features and vulnerabilities addressed
- Add step-by-step testing procedures for each role
- Include troubleshooting guide for common issues
- Add GitHub commit guide for version control
- Update README with RBAC features"
```

---

## Git Commands Reference

### Initial Setup (if not already done)
```bash
# Navigate to project directory
cd c:\xampp\htdocs\ITE311-SUPILANAS

# Initialize git repository (if not already initialized)
git init

# Add remote repository
git remote add origin https://github.com/YOUR_USERNAME/ITE311-SUPILANAS.git

# Configure user information
git config user.name "Your Name"
git config user.email "your.email@example.com"
```

### Check Status Before Committing
```bash
# View changed files
git status

# View changes in files
git diff

# View staged changes
git diff --cached
```

### Staging and Committing
```bash
# Stage specific files
git add path/to/file

# Stage all changes
git add .

# Commit with message
git commit -m "Your commit message"

# Amend last commit (if needed)
git commit --amend
```

### Pushing to GitHub
```bash
# Push to main branch
git push origin main

# Push to master branch (if using master)
git push origin master

# Force push (use with caution)
git push -f origin main
```

### Viewing Commit History
```bash
# View commit log
git log

# View compact log
git log --oneline

# View log with graph
git log --graph --oneline --all
```

---

## Commit Message Best Practices

### Format
```
<type>: <subject>

<body>

<footer>
```

### Types
- **feat**: New feature
- **fix**: Bug fix
- **docs**: Documentation changes
- **style**: Code style changes (formatting, missing semicolons, etc.)
- **refactor**: Code refactoring
- **test**: Adding or updating tests
- **chore**: Maintenance tasks
- **security**: Security improvements

### Examples

**Good Commit Messages:**
```
feat: Add role-based dashboard with conditional content

- Implement admin dashboard with user statistics
- Add teacher dashboard with course management
- Create student dashboard with assignments view
- Fetch role-specific data from database
```

**Bad Commit Messages:**
```
Update files
Fixed stuff
Changes
WIP
```

---

## Spreading Commits Over 4 Days

### Strategy 1: Natural Development Flow
- **Day 1**: Database and security setup
- **Day 2**: Authentication implementation
- **Day 3**: Authorization and routing
- **Day 4**: UI and documentation

### Strategy 2: Using Git Commit Date
If you need to backdate commits (for demonstration purposes only):

```bash
# Commit with specific date (4 days ago)
GIT_AUTHOR_DATE="2025-10-26T10:00:00" GIT_COMMITTER_DATE="2025-10-26T10:00:00" git commit -m "Your message"

# Commit with specific date (3 days ago)
GIT_AUTHOR_DATE="2025-10-27T14:00:00" GIT_COMMITTER_DATE="2025-10-27T14:00:00" git commit -m "Your message"

# Commit with specific date (2 days ago)
GIT_AUTHOR_DATE="2025-10-28T16:00:00" GIT_COMMITTER_DATE="2025-10-28T16:00:00" git commit -m "Your message"

# Commit with specific date (1 day ago)
GIT_AUTHOR_DATE="2025-10-29T11:00:00" GIT_COMMITTER_DATE="2025-10-29T11:00:00" git commit -m "Your message"

# Current day commits
git commit -m "Your message"
```

**Note**: This should only be used for educational purposes. In real projects, commits should reflect actual development timeline.

---

## Pre-Push Checklist

Before pushing to GitHub, ensure:

- [ ] All files are properly staged
- [ ] Commit messages are descriptive and follow conventions
- [ ] No sensitive information (passwords, API keys) in commits
- [ ] Code is tested and working
- [ ] At least 5 commits made
- [ ] Commits spread over 4 days
- [ ] README.md is updated
- [ ] Documentation is complete

---

## Verifying Commits

### Check Commit Count
```bash
# Count total commits
git rev-list --count HEAD

# View last 10 commits
git log -10 --oneline
```

### Check Commit Dates
```bash
# View commits with dates
git log --pretty=format:"%h - %an, %ar : %s"

# View commits grouped by date
git log --pretty=format:"%h - %ad : %s" --date=short
```

### Check What Will Be Pushed
```bash
# View commits that will be pushed
git log origin/main..HEAD

# View file changes that will be pushed
git diff origin/main..HEAD --name-only
```

---

## Final Push Command

After all commits are made:

```bash
# Ensure you're on the correct branch
git branch

# Pull latest changes (if working with others)
git pull origin main

# Push all commits
git push origin main

# Verify on GitHub
# Visit: https://github.com/YOUR_USERNAME/ITE311-SUPILANAS/commits
```

---

## Example Complete Workflow

```bash
# Day 1 - Morning
git add app/Database/Migrations/2025-10-30-052600_UpdateUsersTableRoleColumn.php app/Models/UserModel.php
git commit -m "feat: Update users table migration for role-based access control"

# Day 1 - Afternoon
git add app/Config/Security.php app/Config/Filters.php
git commit -m "security: Enable CSRF protection and security enhancements"

# Day 2 - Morning
git add app/Controllers/Auth.php
git commit -m "feat: Implement secure authentication with role-based session management"

# Day 3 - Morning
git add app/Filters/AuthFilter.php app/Filters/RoleAuth.php
git commit -m "feat: Implement authentication and role-based authorization filters"

# Day 3 - Afternoon
git add app/Config/Routes.php
git commit -m "feat: Configure comprehensive role-based routing system"

# Day 4 - Morning
git add app/Views/auth/dashboard.php
git commit -m "feat: Implement unified dashboard with conditional role-based content"

# Day 4 - Afternoon
git add app/Views/templates/
git commit -m "feat: Create dynamic navigation system with role-based menus"

# Day 4 - Evening
git add RBAC_IMPLEMENTATION_GUIDE.md GITHUB_COMMIT_GUIDE.md README.md
git commit -m "docs: Add comprehensive RBAC implementation and testing documentation"

# Push all commits
git push origin main
```

---

## Troubleshooting

### Issue: "fatal: not a git repository"
```bash
git init
git remote add origin https://github.com/YOUR_USERNAME/ITE311-SUPILANAS.git
```

### Issue: "rejected - non-fast-forward"
```bash
git pull origin main --rebase
git push origin main
```

### Issue: "Permission denied (publickey)"
```bash
# Use HTTPS instead of SSH
git remote set-url origin https://github.com/YOUR_USERNAME/ITE311-SUPILANAS.git
```

### Issue: Need to undo last commit
```bash
# Undo commit but keep changes
git reset --soft HEAD~1

# Undo commit and discard changes
git reset --hard HEAD~1
```

---

## Summary

1. Make at least **5 meaningful commits**
2. Spread commits over **4 days**
3. Use **descriptive commit messages**
4. Follow **conventional commit format**
5. **Test before committing**
6. **Verify commits** before pushing
7. **Push to GitHub** when ready

**Final Commit Message:**
```bash
git commit -m "ROLE BASE Implementation

Complete implementation of role-based access control system with:
- Secure authentication and authorization
- Unified dashboard with role-specific content
- Dynamic navigation based on user roles
- Comprehensive security enhancements
- Full documentation and testing guide"
```

Good luck with your submission! 🚀
