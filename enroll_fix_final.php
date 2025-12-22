<?php
// Final enrollment fix - replace the entire JavaScript section in dashboard
$javascript_fix = '
<script>
// Working enrollment functions
function enrollInCourseSimple(courseId, courseTitle) {
    console.log("ENROLL BUTTON CLICKED - Course ID:", courseId, "Title:", courseTitle);
    alert("Enrolling in: " + courseTitle + " (ID: " + courseId + ")");
    
    // Show enrollment modal if it exists
    if (typeof $("#enrollConfirmModal").modal === "function") {
        $("#enrollConfirmModal").modal("show");
        $("#confirmEnrollBtn").data("course-id", courseId);
        $("#confirmEnrollBtn").data("course-title", courseTitle);
    }
}

function enrollInCourseFinal(courseId, courseTitle) {
    console.log("FINAL ENROLL FUNCTION CALLED");
    enrollInCourseSimple(courseId, courseTitle);
}

// Simple document ready
$(document).ready(function() {
    console.log("Dashboard loaded with FINAL enrollment fix");
    
    // Search functionality
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
        updateCourseCount();
    });
    
    // Clear search
    $("#clearSearch").on("click", function() {
        $("#courseSearch").val("");
        $(".course-item").show();
        updateCourseCount();
    });
    
    // Update course count
    function updateCourseCount() {
        var visibleCount = $(".course-item:visible").length;
        $("#course-count").text(visibleCount);
    }
});
</script>';

// Read the dashboard file
$dashboard_content = file_get_contents('app/Views/auth/dashboard.php');

// Find the script section and replace it
$script_start = strpos($dashboard_content, '<script>');
$script_end = strpos($dashboard_content, '</script>', $script_start);

if ($script_start !== false && $script_end !== false) {
    $before_script = substr($dashboard_content, 0, $script_start);
    $after_script = substr($dashboard_content, $script_end + 9);
    
    $new_content = $before_script . $javascript_fix . $after_script;
    
    // Write the fixed content back
    file_put_contents('app/Views/auth/dashboard.php', $new_content);
    
    echo "SUCCESS: Dashboard enrollment functionality has been fixed!";
} else {
    echo "ERROR: Could not find script section in dashboard file";
}
?>
