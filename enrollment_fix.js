// Simple enrollment fix to add to dashboard
$(document).ready(function() {
    console.log('Enrollment fix loaded');
    
    // Direct event binding for enroll buttons
    $(document).on('click', '.enroll-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var courseId = $(this).data('course-id');
        var courseTitle = $(this).data('course-title');
        
        console.log('Enroll button clicked:', courseId, courseTitle);
        
        // Call enrollInCourse function
        if (typeof enrollInCourse === 'function') {
            enrollInCourse(courseId, courseTitle);
        } else {
            console.error('enrollInCourse function not found');
            alert('Enrollment function not available. Please refresh the page.');
        }
    });
    
    // Also handle modal confirmation
    $(document).on('click', '#confirmEnrollBtn', function() {
        var courseId = $(this).data('course-id');
        var courseTitle = $(this).data('course-title');
        
        console.log('Confirming enrollment for:', courseId, courseTitle);
        
        // Simulate enrollment (replace with actual AJAX call)
        $.post('<?= base_url('course/enroll') ?>', { 
            course_id: courseId,
            csrf_token: $('meta[name="csrf-token"]').attr('content'),
            csrf_hash: $('meta[name="csrf-hash"]').attr('content')
        })
        .done(function(data) {
            if (data.success) {
                // Show success message
                if (typeof showAlert === 'function') {
                    showAlert('success', data.message);
                } else {
                    alert('Successfully enrolled in ' + courseTitle + '!');
                }
                
                // Update button state
                $('.enroll-btn[data-course-id="' + courseId + '"]').prop('disabled', true)
                    .removeClass('btn-primary')
                    .addClass('btn-secondary')
                    .html('<i class="bi bi-check-circle me-1"></i>Enrolled');
                
                // Hide modal
                $('#enrollConfirmModal').modal('hide');
            } else {
                if (typeof showAlert === 'function') {
                    showAlert('danger', data.message);
                } else {
                    alert('Enrollment failed: ' + data.message);
                }
            }
        })
        .fail(function() {
            if (typeof showAlert === 'function') {
                showAlert('danger', 'An error occurred while enrolling. Please try again.');
            } else {
                alert('Enrollment failed. Please try again.');
            }
        });
    });
});
