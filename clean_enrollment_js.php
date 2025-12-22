// Clean, working enrollment JavaScript
console.log("Dashboard loaded with WORKING enrollment system");

// Enrollment function
function enrollInCourse(courseId) {
    console.log("ENROLLMENT STARTED - Course ID:", courseId);
    
    // Show confirmation modal
    $("#enrollConfirmModal").modal("show");
    $("#confirmEnrollBtn").data("course-id", courseId);
    
    // Get course title from button data
    var courseTitle = $(".enroll-btn[data-course-id='" + courseId + "']").data("course-title");
    $("#confirmEnrollBtn").data("course-title", courseTitle);
}

// Handle enrollment confirmation
$(document).on("click", "#confirmEnrollBtn", function() {
    var courseId = $(this).data("course-id");
    var courseTitle = $(this).data("course-title");
    console.log("CONFIRMING ENROLLMENT - Course:", courseTitle, "ID:", courseId);
    
    // Disable button during processing
    $(this).prop("disabled", true).html('<i class="bi bi-hourglass-split"></i> Processing...');
    
    $.ajax({
        url: "<?= base_url('course/enroll') ?>",
        method: "POST",
        data: { course_id: courseId },
        dataType: "json",
        success: function(data) {
            console.log("ENROLLMENT RESPONSE:", data);
            
            if (data.success) {
                // Show success message
                showAlert("success", data.message || "Successfully enrolled in " + courseTitle);
                
                // Disable enroll button and change to enrolled state
                $(".enroll-btn[data-course-id='" + courseId + "']")
                    .prop("disabled", true)
                    .removeClass("btn-primary")
                    .addClass("btn-secondary")
                    .html('<i class="bi bi-check-circle"></i> Enrolled')
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
                $(this).prop("disabled", false).html('<i class="bi bi-plus-circle"></i> Enroll');
            }
        },
        error: function(xhr, status, error) {
            console.error("ENROLLMENT ERROR:", status, error);
            showAlert("danger", "An error occurred while enrolling. Please try again.");
            
            // Re-enable button
            $(this).prop("disabled", false).html('<i class="bi bi-plus-circle"></i> Enroll');
        }
    });
});

// Add course to enrolled section dynamically
function addCourseToEnrolledNew(courseData) {
    console.log("Adding course to enrolled section:", courseData);
    
    var courseHtml = '<div class="list-group-item mb-2">' +
        '<div class="d-flex w-100 justify-content-between align-items-center">' +
            '<div class="flex-grow-1">' +
                '<h6 class="mb-1 text-success">' +
                    '<i class="bi bi-book"></i> ' +
                    courseData.title +
                    '<span class="badge bg-light text-dark ms-2">Enrolled</span>' +
                '</h6>' +
                '<small class="text-muted">Instructor: ' + (courseData.instructor_name || 'TBA') + '</small>' +
            '</div>' +
            '<div class="text-end">' +
                '<button class="btn btn-sm btn-outline-danger unenroll-btn" data-course-id="' + courseData.course_id + '">' +
                    '<i class="bi bi-x-circle"></i> Unenroll' +
                '</button>' +
            '</div>' +
        '</div>';
    
    // Add to enrolled courses section
    $("#enrolledCoursesList").prepend(courseHtml);
    
    // Update enrolled courses count
    var enrolledCount = $("#enrolledCoursesList .list-group-item").length;
    $("#enrolled-count").text(enrolledCount);
}

// Show alert function
function showAlert(type, message) {
    var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
        '<i class="bi bi-' + (type === 'success' ? 'check-circle' : 'exclamation-triangle') + '"></i> ' +
        message +
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
        '</div>';
    
    $("#alert-container").append(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $(".alert").alert("close");
    }, 5000);
}

// Simple search functionality
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
        updateCourseCount();
    });
    
    // Clear search
    $("#clearSearch").on("click", function() {
        $("#courseSearch").val("");
        $(".course-item").show();
        updateCourseCount();
    });
    
    // Update course count function
    function updateCourseCount() {
        var visibleCount = $(".course-item:visible").length;
        $("#course-count").text(visibleCount);
    }
});
