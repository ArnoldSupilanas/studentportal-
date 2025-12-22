<?php
// Add instructor_name column to courses table
echo "Adding instructor_name column to courses table...\n";

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=ite311_supilanas', 'root', '');
    
    // Add instructor_name column
    $addColumn = "ALTER TABLE courses ADD COLUMN instructor_name VARCHAR(255) DEFAULT 'TBA'";
    
    if ($pdo->exec($addColumn)) {
        echo "instructor_name column added successfully!\n";
        
        // Update existing courses with instructor names
        $update = $pdo->prepare("UPDATE courses SET instructor_name = ? WHERE instructor_id = ?");
        
        $instructors = [
            1 => 'John Smith',
            2 => 'Jane Doe'
        ];
        
        foreach ($instructors as $id => $name) {
            $update->execute([$name, $id]);
            echo "Updated instructor_name for instructor_id $id: $name\n";
        }
        
        echo "Courses data updated successfully!\n";
        echo "Available courses should now display properly in dashboard.\n";
    } else {
        echo "Error adding instructor_name column\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
