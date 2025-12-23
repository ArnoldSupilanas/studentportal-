/**
 * Notification System - AJAX and jQuery Implementation
 * Handles real-time notifications with Bootstrap UI
 */

class NotificationSystem {
    constructor() {
        this.pollingInterval = null;
        this.pollingDelay = 60000; // 60 seconds for real-time updates
        this.notificationBadge = $('#notification-badge');
        this.notificationDropdown = $('#notification-dropdown');
        this.notificationList = $('#notification-list');
        this.markAllReadBtn = $('#mark-all-read');
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadNotifications(); // Load notifications on page load
        this.startPolling(); // Start real-time polling
    }

    bindEvents() {
        // Mark notification as read when clicked
        $(document).on('click', '.notification-item', (e) => {
            e.preventDefault();
            const notificationId = $(e.currentTarget).data('notification-id');
            this.markAsRead(notificationId);
        });

        // Mark all notifications as read
        this.markAllReadBtn.on('click', (e) => {
            e.preventDefault();
            this.markAllAsRead();
        });

        // Refresh notifications manually
        $(document).on('click', '#refresh-notifications', (e) => {
            e.preventDefault();
            this.loadNotifications();
        });

        // Create test notification (for development)
        $(document).on('click', '#create-test-notification', (e) => {
            e.preventDefault();
            this.createTestNotification();
        });
    }

    // Load notifications via AJAX
    loadNotifications() {
        console.log('Loading notifications...');
        $.ajax({
            url: '/notifications/get',
            method: 'GET',
            dataType: 'json',
            success: (response) => {
                console.log('Notifications response:', response);
                if (response.success) {
                    this.updateNotificationBadge(response.unread_count);
                    this.renderNotifications(response.notifications);
                } else {
                    console.error('Failed to load notifications:', response.message);
                }
            },
            error: (xhr, status, error) => {
                console.error('AJAX error loading notifications:', error);
                console.error('Response text:', xhr.responseText);
            }
        });
    }

    // Get unread count only
    getUnreadCount() {
        $.ajax({
            url: '/notifications/getUnreadCount',
            method: 'GET',
            dataType: 'json',
            success: (response) => {
                if (response.unread_count !== undefined) {
                    this.updateNotificationBadge(response.unread_count);
                }
            },
            error: (xhr, status, error) => {
                console.error('AJAX error getting unread count:', error);
            }
        });
    }

    // Mark notification as read
    markAsRead(notificationId) {
        $.ajax({
            url: `/notifications/mark_as_read/${notificationId}`,
            method: 'POST',
            dataType: 'json',
            success: (response) => {
                if (response.success) {
                    this.updateNotificationBadge(response.unread_count);
                    this.removeNotificationFromList(notificationId);
                    this.showToast(response.message, 'success');
                } else {
                    this.showToast(response.message || 'Failed to mark as read', 'error');
                }
            },
            error: (xhr, status, error) => {
                console.error('AJAX error marking notification as read:', error);
                this.showToast('Failed to mark notification as read', 'error');
            }
        });
    }

    // Mark all notifications as read
    markAllAsRead() {
        $.ajax({
            url: '/notifications/markAllAsRead',
            method: 'POST',
            dataType: 'json',
            success: (response) => {
                if (response.success) {
                    this.updateNotificationBadge(0);
                    this.clearNotificationList();
                    this.showToast(response.message, 'success');
                } else {
                    this.showToast(response.message || 'Failed to mark all as read', 'error');
                }
            },
            error: (xhr, status, error) => {
                console.error('AJAX error marking all notifications as read:', error);
                this.showToast('Failed to mark all notifications as read', 'error');
            }
        });
    }

    // Create test notification
    createTestNotification() {
        const types = ['info', 'success', 'warning', 'error'];
        const randomType = types[Math.floor(Math.random() * types.length)];
        
        $.ajax({
            url: '/notifications/createTestNotification',
            method: 'POST',
            data: {
                title: 'Test Notification',
                message: 'This is a test notification created at ' + new Date().toLocaleTimeString(),
                type: randomType
            },
            dataType: 'json',
            success: (response) => {
                if (response.success) {
                    this.loadNotifications();
                    this.showToast('Test notification created', 'success');
                } else {
                    this.showToast(response.message || 'Failed to create test notification', 'error');
                }
            },
            error: (xhr, status, error) => {
                console.error('AJAX error creating test notification:', error);
                this.showToast('Failed to create test notification', 'error');
            }
        });
    }

    // Update notification badge
    updateNotificationBadge(count) {
        if (count > 0) {
            this.notificationBadge.text(count).show();
            this.notificationBadge.removeClass('d-none');
        } else {
            this.notificationBadge.hide();
            this.notificationBadge.addClass('d-none');
        }
    }

    // Render notifications in dropdown
    renderNotifications(notifications) {
        console.log('Rendering notifications:', notifications);
        
        if (notifications.length === 0) {
            this.notificationList.html(`
                <div class="dropdown-item text-muted text-center">
                    <small>No new notifications</small>
                </div>
            `);
            this.markAllReadBtn.hide();
            return;
        }

        let html = '';
        notifications.forEach(notification => {
            console.log('Processing notification:', notification);
            const typeIcon = this.getTypeIcon(notification.type);
            const typeClass = this.getTypeClass(notification.type);
            const timeAgo = this.getTimeAgo(notification.created_at);
            const alertClass = this.getAlertClass(notification.type);
            
            html += `
                <div class="alert ${alertClass} alert-dismissible fade show m-2" role="alert" style="cursor: pointer;" data-notification-id="${notification.id}">
                    <div class="d-flex align-items-start">
                        <div class="me-2">
                            <i class="${typeIcon} ${typeClass}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">${notification.title}</div>
                            <div class="small">${notification.message}</div>
                            <div class="small text-muted">${timeAgo}</div>
                        </div>
                        ${!notification.is_read ? '<div class="ms-2"><span class="badge bg-primary rounded-pill">New</span></div>' : ''}
                    </div>
                    <button type="button" class="btn-close mark-as-read-btn" data-notification-id="${notification.id}" aria-label="Mark as read"></button>
                </div>
            `;
        });

        console.log('Generated HTML:', html);
        this.notificationList.html(html);
        this.markAllReadBtn.show();
        
        // Add click handlers for notifications
        this.notificationList.find('[data-notification-id]').on('click', (e) => {
            if (!$(e.target).hasClass('mark-as-read-btn')) {
                const notificationId = $(e.currentTarget).data('notification-id');
                this.markAsRead(notificationId);
            }
        });
        
        // Add click handlers for mark as read buttons
        this.notificationList.find('.mark-as-read-btn').on('click', (e) => {
            e.stopPropagation();
            const notificationId = $(e.currentTarget).data('notification-id');
            this.markAsRead(notificationId);
        });
    }

    // Remove notification from list after marking as read
    removeNotificationFromList(notificationId) {
        const notificationElement = $(`[data-notification-id="${notificationId}"]`);
        notificationElement.fadeOut(300, () => {
            notificationElement.remove();
            
            // Check if list is empty
            if (this.notificationList.children().length === 0) {
                this.clearNotificationList();
            }
        });
    }

    // Clear notification list
    clearNotificationList() {
        this.notificationList.html(`
            <div class="dropdown-item text-muted text-center">
                <small>No new notifications</small>
            </div>
        `);
        this.markAllReadBtn.hide();
    }

    // Get icon for notification type
    getTypeIcon(type) {
        const icons = {
            'info': 'fas fa-info-circle',
            'success': 'fas fa-check-circle',
            'warning': 'fas fa-exclamation-triangle',
            'danger': 'fas fa-exclamation-circle'
        };
        return icons[type] || icons['info'];
    }

    // Get CSS class for notification type
    getTypeClass(type) {
        const classes = {
            'info': 'text-info',
            'success': 'text-success',
            'warning': 'text-warning',
            'danger': 'text-danger'
        };
        return classes[type] || classes['info'];
    }

    // Get Bootstrap alert class for notification type
    getAlertClass(type) {
        const classes = {
            'info': 'alert-info',
            'success': 'alert-success',
            'warning': 'alert-warning',
            'error': 'alert-danger',
            'danger': 'alert-danger'
        };
        return classes[type] || classes['info'];
    }

    // Convert timestamp to "time ago" format
    getTimeAgo(timestamp) {
        const now = new Date();
        const notificationTime = new Date(timestamp);
        const diffInSeconds = Math.floor((now - notificationTime) / 1000);

        if (diffInSeconds < 60) {
            return 'Just now';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return `${hours} hour${hours > 1 ? 's' : ''} ago`;
        } else {
            const days = Math.floor(diffInSeconds / 86400);
            return `${days} day${days > 1 ? 's' : ''} ago`;
        }
    }

    // Show toast notification
    showToast(message, type = 'info') {
        const toastId = 'toast-' + Date.now();
        const bgClass = type === 'error' ? 'bg-danger' : 'bg-success';
        
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;

        $('#toast-container').append(toastHtml);
        const toastElement = new bootstrap.Toast(document.getElementById(toastId));
        toastElement.show();

        // Remove toast element after it's hidden
        $('#' + toastId).on('hidden.bs.toast', () => {
            $('#' + toastId).remove();
        });
    }

    // Start polling for new notifications
    startPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }

        this.pollingInterval = setInterval(() => {
            this.loadNotifications(); // Load full notifications for real-time updates
        }, this.pollingDelay);
        
        console.log(`Started polling for notifications every ${this.pollingDelay / 1000} seconds`);
    }

    // Stop polling
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    }

    // Destroy and cleanup
    destroy() {
        this.stopPolling();
        this.markAllReadBtn.off('click');
        $(document).off('click', '.notification-item');
        $(document).off('click', '#refresh-notifications');
        $(document).off('click', '#create-test-notification');
    }
}

// Initialize notification system when DOM is ready
$(document).ready(() => {
    console.log('DOM ready, checking for notification elements...');
    
    // Check if notification elements exist
    const badgeExists = $('#notification-badge').length > 0;
    const listExists = $('#notification-list').length > 0;
    
    console.log('Notification badge exists:', badgeExists);
    console.log('Notification list exists:', listExists);
    
    if (badgeExists && listExists) {
        console.log('Notification elements found, initializing notification system...');
        window.notificationSystem = new NotificationSystem();
        console.log('Notification system initialized successfully');
    } else {
        console.log('Notification elements not found, skipping initialization');
        console.log('Available elements:', {
            badge: $('#notification-badge').length,
            list: $('#notification-list').length,
            dropdown: $('#notificationDropdown').length
        });
    }

    // Cleanup on page unload
    $(window).on('beforeunload', () => {
        if (window.notificationSystem) {
            console.log('Cleaning up notification system...');
            window.notificationSystem.destroy();
        }
    });
    
    // Handle page visibility changes for better performance
    document.addEventListener('visibilitychange', () => {
        if (window.notificationSystem) {
            if (document.hidden) {
                console.log('Page hidden, stopping notification polling');
                window.notificationSystem.stopPolling();
            } else {
                console.log('Page visible, resuming notification polling');
                window.notificationSystem.loadNotifications();
                window.notificationSystem.startPolling();
            }
        }
    });
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = NotificationSystem;
}
