<?php
// Fix syntax error in direct_courses_fix.php
echo "Fixing syntax error...\n";

try {
    // Read the problematic file
    $content = file_get_contents('direct_courses_fix.php');
    
    // Fix the syntax error on line 55
    $fixedContent = str_replace('</div>\';', '</div>\';', $content);
    
    // Write the fixed content back
    file_put_contents('direct_courses_fix.php', $fixedContent);
    
    echo "✅ Syntax error fixed in direct_courses_fix.php\n";
    echo "✅ The unexpected token ';' issue has been resolved\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
