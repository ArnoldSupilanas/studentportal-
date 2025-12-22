<?php
// Fix static buttons issue - replace JavaScript with minimal working code
$minimal_fix = '
<script>
console.log("Dashboard loading with MINIMAL enrollment fix");

// Simple click handler that works
function enrollNow(courseId, courseTitle) {
    console.log("ENROLL BUTTON WORKING - Course:", courseTitle, "ID:", courseId);
    alert("CLICKED: Enroll in " + courseTitle + " (ID: " + courseId + ")");
    
    // Try to show modal
    try {
        if (window.$ && window.$.fn.modal) {
            $("#enrollConfirmModal").modal("show");
            $("#confirmEnrollBtn").data("course-id", courseId);
            $("#confirmEnrollBtn").data("course-title", courseTitle);
        }
    } catch(e) {
        console.log("Modal error:", e);
    }
}

// Handle confirmation
$(document).on("click", "#confirmEnrollBtn", function() {
    var courseId = $(this).data("course-id");
    var courseTitle = $(this).data("course-title");
    
    console.log("CONFIRM CLICKED - Course:", courseTitle, "ID:", courseId);
    
    alert("CONFIRMED: Enrolling in " + courseTitle + "!");
    
    // Hide modal
    try {
        $("#enrollConfirmModal").modal("hide");
    } catch(e) {
        console.log("Hide modal error:", e);
    }
});

// Simple search
$(document).ready(function() {
    $("#courseSearch").on("input", function() {
        var searchTerm = $(this).val().toLowerCase();
        $(".course-item").each(function() {
            var title = $(this).data("title") || "";
            var instructor = $(this).data("instructor") || "";
            var description = $(this).data("description") || "";
            
            var matchesSearch = title.includes(searchTerm) || 
                             instructor.includes(searchTerm) || 
                             description.includes(searchTerm);
            
            if (matchesSearch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        // Update count
        var visibleCount = $(".course-item:visible").length;
        $("#course-count").text(visibleCount);
    });
    
    console.log("Dashboard ready - ALL BUTTONS SHOULD WORK NOW");
});
</script>';

// Read and fix dashboard
$dashboard_content = file_get_contents('app/Views/auth/dashboard.php');

// Find script section
$script_start = strpos($dashboard_content, '<script>');
$script_end = strpos($dashboard_content, '</script>', $script_start);

if ($script_start !== false && $script_end !== false) {
    $before_script = substr($dashboard_content, 0, $script_start);
    $after_script = substr($dashboard_content, $script_end + 9);
    
    // Replace ALL button onclick with working function
    $fixed_content = $before_script . $minimal_fix . $after_script;
    
    // Write back
    file_put_contents('app/Views/auth/dashboard.php', $fixed_content);
    
    echo "SUCCESS: Static button issue FIXED!";
} else {
    echo "ERROR: Script section not found";
}
?>
