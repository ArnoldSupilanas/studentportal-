<!DOCTYPE html>
<html>
<head>
    <title>Login Test</title>
</head>
<body>
    <h1>Test Login Flow</h1>
    
    <h2>Test Credentials:</h2>
    <ul>
        <li>Admin: admin@lms.com / admin123</li>
        <li>Teacher: instructor@lms.com / instructor123</li>
        <li>Student: student@lms.com / student123</li>
    </ul>
    
    <h2>Quick Links:</h2>
    <ul>
        <li><a href="/index.php/login" target="_blank">Login Page</a></li>
        <li><a href="/index.php/debug" target="_blank">Debug Page</a></li>
        <li><a href="/index.php/debug/test-login" target="_blank">Simulate Login</a></li>
        <li><a href="/index.php/dashboard" target="_blank">Dashboard (requires login)</a></li>
    </ul>
    
    <h2>Manual Test Form:</h2>
    <form action="/index.php/login" method="post" target="_blank">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <label>Email: <input type="email" name="email" value="instructor@lms.com" required></label><br><br>
        <label>Password: <input type="password" name="password" value="instructor123" required></label><br><br>
        <button type="submit">Login</button>
    </form>
    
    <script>
        // Auto-fill CSRF token
        document.querySelector('input[name="<?= csrf_token() ?>"]').value = '<?= csrf_hash() ?>';
    </script>
</body>
</html>
