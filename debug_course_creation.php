<?php
// Debug script to test course creation
session_start();

echo "<h2>Course Creation Debug</h2>";

// Check session
echo "<h3>Session Status:</h3>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Logged In: " . (isset($_SESSION['is_logged_in']) ? $_SESSION['is_logged_in'] : 'false') . "\n";
echo "Role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set') . "\n";
echo "</pre>";

// Test form submission
if ($_POST) {
    echo "<h3>POST Data Received:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    // Simulate course creation
    $name = $_POST['name'] ?? '';
    $code = $_POST['code'] ?? '';
    $teacher = $_POST['teacher'] ?? '';
    $status = $_POST['status'] ?? '';
    
    if ($name && $code && $teacher && $status) {
        $newCourse = [
            'id' => rand(100, 999),
            'name' => $name,
            'code' => $code,
            'teacher' => $teacher,
            'students' => 0,
            'status' => $status
        ];
        
        echo "<h3>Course Created Successfully:</h3>";
        echo "<pre>";
        print_r($newCourse);
        echo "</pre>";
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Course created successfully',
            'course' => $newCourse
        ]);
        exit;
    } else {
        echo "<h3>Validation Error:</h3>";
        echo "<p>All fields are required</p>";
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'All fields are required'
        ]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Creation Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Test Course Creation Form</h3>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Course Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Course Code</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacher" class="form-label">Teacher</label>
                        <input type="text" class="form-control" id="teacher" name="teacher" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Test Course Creation</button>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h3>AJAX Test</h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-success" onclick="testAjax()">Test AJAX Course Creation</button>
                <div id="ajaxResult" class="mt-3"></div>
            </div>
        </div>
    </div>

    <script>
        function testAjax() {
            const data = {
                name: 'Test Course',
                code: 'TEST101',
                teacher: 'Test Teacher',
                status: 'active'
            };
            
            fetch('debug_course_creation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(data)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('ajaxResult').innerHTML = 
                    '<div class="alert alert-success"><pre>' + JSON.stringify(data, null, 2) + '</pre></div>';
            })
            .catch(error => {
                document.getElementById('ajaxResult').innerHTML = 
                    '<div class="alert alert-danger">Error: ' + error + '</div>';
            });
        }
    </script>
</body>
</html>
