-- Update existing 'instructor' role to 'teacher'
UPDATE users SET role = 'teacher' WHERE role = 'instructor';

-- Verify the update
SELECT id, first_name, last_name, email, role, status FROM users;
