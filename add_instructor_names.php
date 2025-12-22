<?php
// Add instructor_name field to courses
echo "Adding instructor_name field to courses...\n";

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=ite311_supilanas', 'root', '');
    
    // Update courses with instructor names based on instructor_id
    $instructors = [
        1 => 'John Smith',
        2 => 'Jane Doe'
    ];
    
    foreach ($instructors as $id => $name) {
        $update = $pdo->prepare("UPDATE courses SET instructor_name = ? WHERE instructor_id = ?");
        $update->execute([$name, $id]);
        echo "Updated instructor_name for instructor_id $id: $name\n";
    }
    
    echo "Instructor names added successfully!\n";
    echo "Available courses should now display properly.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
