<?php
// Fix courses data to include instructor_name field
echo "Fixing courses data to include instructor_name...\n";

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=ite311_supilanas', 'root', '');
    
    // Update existing courses to include instructor_name field
    $update = $pdo->prepare("UPDATE courses SET instructor_name = ? WHERE instructor_id = ?");
    
    // Mock instructor names based on instructor_id
    $instructors = [
        1 => 'John Smith',
        2 => 'Jane Doe'
    ];
    
    foreach ($instructors as $id => $name) {
        $update->execute([$name, $id]);
        echo "Updated instructor_name for instructor_id $id: $name\n";
    }
    
    echo "Courses data updated successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
