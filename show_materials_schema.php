<?php
// Show materials table schema
$db = new mysqli('localhost', 'root', '', 'lms_supilanas');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "=== MATERIALS TABLE SCHEMA ===\n\n";

// Get table structure
$result = $db->query("DESCRIBE materials");
if ($result) {
    echo "Field\t\tType\t\t\tNull\tKey\tDefault\tExtra\n";
    echo "-----\t\t----\t\t\t----\t---\t-------\t-----\n";
    
    while ($row = $result->fetch_assoc()) {
        echo sprintf("%-15s\t%-20s\t%-5s\t%-5s\t%-10s\t%s\n", 
            $row['Field'], 
            $row['Type'], 
            $row['Null'], 
            $row['Key'], 
            $row['Default'] ?? 'NULL', 
            $row['Extra']
        );
    }
} else {
    echo "Error getting table structure: " . $db->error . "\n";
}

echo "\n=== MATERIALS TABLE DATA ===\n\n";

// Show sample data
$result = $db->query("SELECT * FROM materials ORDER BY id DESC LIMIT 5");
if ($result) {
    echo "ID\tCourse ID\tFile Name\t\t\t\tFile Path\t\t\t\tCreated At\n";
    echo "--\t--------\t---------\t\t\t\t---------\t\t\t\t----------\n";
    
    while ($row = $result->fetch_assoc()) {
        echo sprintf("%d\t%d\t\t%-30s\t%-40s\t%s\n", 
            $row['id'], 
            $row['course_id'], 
            $row['file_name'], 
            $row['file_path'], 
            $row['created_at']
        );
    }
    
    echo "\nTotal materials: " . $result->num_rows . " (showing last 5)\n";
    
    // Get total count
    $count_result = $db->query("SELECT COUNT(*) as total FROM materials");
    if ($count_result) {
        $count = $count_result->fetch_assoc();
        echo "Total materials in database: " . $count['total'] . "\n";
    }
} else {
    echo "Error getting table data: " . $db->error . "\n";
}

echo "\n=== FOREIGN KEY CONSTRAINTS ===\n\n";

// Show foreign key constraints
$result = $db->query("
    SELECT 
        CONSTRAINT_NAME, 
        COLUMN_NAME, 
        REFERENCED_TABLE_NAME, 
        REFERENCED_COLUMN_NAME
    FROM 
        INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE 
        TABLE_SCHEMA = 'lms_supilanas' 
        AND TABLE_NAME = 'materials' 
        AND REFERENCED_TABLE_NAME IS NOT NULL
");

if ($result) {
    echo "Constraint Name\t\tColumn\t\tReferenced Table\t\tReferenced Column\n";
    echo "---------------\t\t------\t\t----------------\t\t----------------\n";
    
    while ($row = $result->fetch_assoc()) {
        echo sprintf("%-20s\t%-15s\t%-20s\t%-15s\n", 
            $row['CONSTRAINT_NAME'], 
            $row['COLUMN_NAME'], 
            $row['REFERENCED_TABLE_NAME'], 
            $row['REFERENCED_COLUMN_NAME']
        );
    }
} else {
    echo "No foreign key constraints found or error: " . $db->error . "\n";
}

$db->close();
?>
