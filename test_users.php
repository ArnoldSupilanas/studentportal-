<?php
require 'vendor/autoload.php';

$app = new CodeIgniter\CodeIgniter();
$app->initialize();
$db = \Config\Database::connect();

echo "=== Existing Users ===\n";
$query = $db->query('SELECT id, username, email, role FROM users LIMIT 10');
$results = $query->getResult();

foreach($results as $user) {
    echo "ID: {$user->id}, Username: {$user->username}, Email: {$user->email}, Role: {$user->role}\n";
}

echo "\n=== Existing Courses ===\n";
$query = $db->query('SELECT id, title, code FROM courses LIMIT 5');
$results = $query->getResult();

foreach($results as $course) {
    echo "ID: {$course->id}, Title: {$course->title}, Code: {$course->code}\n";
}

echo "\n=== Materials Table Check ===\n";
$query = $db->query('SELECT COUNT(*) as count FROM materials');
$result = $query->getRow();
echo "Total materials in database: {$result->count}\n";
?>
