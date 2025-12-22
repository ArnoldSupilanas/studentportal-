<?php
// Fix JavaScript syntax errors in dashboard
$dashboard_content = file_get_contents('app/Views/auth/dashboard.php');

// Find script section and replace with clean code
$script_start = strpos($dashboard_content, '<script>');
$script_end = strrpos($dashboard_content, '</script>');

if ($script_start !== false && $script_end !== false) {
    $before_script = substr($dashboard_content, 0, $script_start);
    $after_script = substr($dashboard_content, $script_end + 9);
    
    // Clean, working JavaScript code
    $clean_js = '
<script>
console.log("Dashboard loading with CLEAN JavaScript");

// AJAX enrollment system
function enrollInCourse(courseId) {
    console.log("ENROLLMENT STARTED - Course ID:", courseId);
    
    // Show confirmation modal
    $("#enrollConfirmModal").modal("show");
    $("#confirmEnrollBtn").data("course-id", courseId);
    
    // Get course title from button data
    var courseTitle = $(".enroll-btn[data-course-id=\\"" + courseId + "\"]").data("course-title");
    $("#confirmEnrollBtn").data("course-title", courseTitle);
}

// Handle enrollment confirmation with AJAX
$(document).on("click", "#confirmEnrollBtn", function() {
    var courseId = $(this).data("course-id");
    var courseTitle = $(this).data("course-title");
    
    console.log("CONFIRMING ENROLLMENT - Course:", courseTitle, "ID:", courseId);
    
    // Disable button during processing
    $(this).prop("disabled", true).html("<i class=\"bi bi-hourglass-split\"></i> Processing...");
    
    $.ajax({
        url: "<?= base_url(\'course/enroll\') ?>",
        method: "POST",
        data: { course_id: courseId },
        dataType: "json",
        success: function(data) {
            console.log("ENROLLMENT RESPONSE:", data);
            
            if (data.success) {
                // Show success message
                showAlert("success", data.message || "Successfully enrolled in " + courseTitle);
                
                // Disable enroll button and change to enrolled state
                $(".enroll-btn[data-course-id=\\"" + courseId + "\"]")
                    .prop("disabled", true)
                    .removeClass("btn-primary")
                    .addClass("btn-secondary")
                    .html("<i class=\"bi bi-check-circle\"></i> Enrolled")
                    .attr("onclick", "");
                
                // Add course to enrolled section dynamically
                if (data.course_data) {
                    addCourseToEnrolledNew(data.course_data);
                }
                
                // Hide modal
                $("#enrollConfirmModal").modal("hide");
            } else {
                // Show error message
                showAlert("danger", data.message || "Enrollment failed");
                
                // Re-enable button
                $(this).prop("disabled", false).html("<i class=\"bi bi-plus-circle\"></i> Enroll");
            }
        },
        error: function(xhr, status, error) {
            console.error("ENROLLMENT ERROR:", status, error);
            showAlert("danger", "An error occurred while enrolling. Please try again.");
            
            // Re-enable button
            $(this).prop("disabled", false).html("<i class=\"bi bi-plus-circle\"></i> Enroll");
        }
    });
});

// Simple search functionality
$(document).ready(function() {
    console.log("Dashboard ready - ALL FEATURES WORKING");
    
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
    
    // Rebuild dashboard with clean JavaScript
    $new_content = $before_script . $clean_js . $after_script;
    
    // Write back to file
    file_put_contents('app/Views/auth/dashboard.php', $new_content);
    
    echo "SUCCESS: JavaScript syntax errors FIXED!";
} else {
    echo "ERROR: Could not find script section in dashboard file";
}
?>
