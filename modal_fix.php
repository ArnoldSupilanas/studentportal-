<?php
// Fix the Confirm Enrollment modal functionality
$modal_fix = '
// Working enrollment functions with modal support
function enrollInCourseSimple(courseId, courseTitle) {
    console.log("ENROLL BUTTON CLICKED - Course ID:", courseId, "Title:", courseTitle);
    
    // Show enrollment modal
    $("#enrollConfirmModal").modal("show");
    $("#confirmEnrollBtn").data("course-id", courseId);
    $("#confirmEnrollBtn").data("course-title", courseTitle);
}

// Handle enrollment confirmation
$(document).on("click", "#confirmEnrollBtn", function() {
    var courseId = $(this).data("course-id");
    var courseTitle = $(this).data("course-title");
    
    console.log("CONFIRM ENROLLMENT CLICKED - Course ID:", courseId, "Title:", courseTitle);
    
    // Simulate enrollment (replace with actual AJAX call)
    $.post("<?= base_url(\'course/enroll\') ?>", { course_id: courseId })
    .done(function(data) {
        if (data.success) {
            alert("Successfully enrolled in " + courseTitle + "!");
            
            // Disable the enroll button
            $(".enroll-btn[data-course-id=\\"" + courseId + "\"]").prop("disabled", true)
                .removeClass("btn-primary")
                .addClass("btn-secondary")
                .html("<i class=\"bi bi-check-circle me-1\"></i>Enrolled")
                .attr("onclick", "");
            
            // Hide modal
            $("#enrollConfirmModal").modal("hide");
        } else {
            alert("Enrollment failed: " + data.message);
        }
    })
    .fail(function() {
        alert("Enrollment failed. Please try again.");
    });
});

// Simple document ready
$(document).ready(function() {
    console.log("Dashboard loaded with WORKING modal enrollment");
    
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

// Remove the old enrollInCourseFinal function to avoid conflicts
if (typeof window.enrollInCourseFinal !== "undefined") {
    delete window.enrollInCourseFinal;
}
</script>';

// Read the dashboard file
$dashboard_content = file_get_contents('app/Views/auth/dashboard.php');

// Find and replace the JavaScript section
$script_start = strpos($dashboard_content, '<script>');
$script_end = strpos($dashboard_content, '</script>', $script_start);

if ($script_start !== false && $script_end !== false) {
    $before_script = substr($dashboard_content, 0, $script_start);
    $after_script = substr($dashboard_content, $script_end + 9);
    
    $new_content = $before_script . $modal_fix . $after_script;
    
    // Write the fixed content back
    file_put_contents('app/Views/auth/dashboard.php', $new_content);
    
    echo "SUCCESS: Modal enrollment functionality has been fixed!";
} else {
    echo "ERROR: Could not find script section in dashboard file";
}
?>
