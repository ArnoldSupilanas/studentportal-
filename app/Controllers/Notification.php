<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\API\ResponseTrait;

class Notification extends BaseController
{
    use ResponseTrait;
    
    protected $notificationModel;
    
    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }
    
    /**
     * Get unread notifications for the current user
     * AJAX endpoint
     */
    public function getUnread()
    {
        // Check if user is logged in
        if (!session()->get('is_logged_in')) {
            return $this->respond(['error' => 'Unauthorized'], 401);
        }
        
        $userId = session()->get('user_id');
        $notifications = $this->notificationModel->getUnreadNotifications($userId, 10);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        
        return $this->respond([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'timestamp' => time()
        ]);
    }
    
    /**
     * Get all notifications with pagination
     * AJAX endpoint
     */
    public function getAll()
    {
        if (!session()->get('is_logged_in')) {
            return $this->respond(['error' => 'Unauthorized'], 401);
        }
        
        $userId = session()->get('user_id');
        $page = $this->request->getVar('page') ?? 1;
        $limit = $this->request->getVar('limit') ?? 20;
        $offset = ($page - 1) * $limit;
        
        $notifications = $this->notificationModel->getUserNotifications($userId, $limit, $offset);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        
        return $this->respond([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'page' => $page,
            'limit' => $limit,
            'timestamp' => time()
        ]);
    }
    
    /**
     * Real-time polling for new notifications
     * AJAX endpoint for jQuery polling
     */
    public function poll()
    {
        if (!session()->get('is_logged_in')) {
            return $this->respond(['error' => 'Unauthorized'], 401);
        }
        
        $userId = session()->get('user_id');
        $lastId = $this->request->getVar('last_id') ?? 0;
        
        $notifications = $this->notificationModel->getRecentNotifications($userId, $lastId, 5);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        
        return $this->respond([
            'new_notifications' => $notifications,
            'unread_count' => $unreadCount,
            'last_id' => $lastId,
            'has_new' => count($notifications) > 0,
            'timestamp' => time()
        ]);
    }
    
    /**
     * Mark notification as read
     * AJAX endpoint
     */
    public function markAsRead($id)
    {
        if (!session()->get('is_logged_in')) {
            return $this->respond(['error' => 'Unauthorized'], 401);
        }
        
        $userId = session()->get('user_id');
        
        // Verify notification belongs to user
        $notification = $this->notificationModel->getNotificationById($id, $userId);
        if (!$notification) {
            return $this->respond(['error' => 'Notification not found'], 404);
        }
        
        $updated = $this->notificationModel->markAsRead($id);
        
        if ($updated) {
            $unreadCount = $this->notificationModel->getUnreadCount($userId);
            return $this->respond([
                'success' => true,
                'unread_count' => $unreadCount,
                'message' => 'Notification marked as read'
            ]);
        } else {
            return $this->respond(['error' => 'Failed to mark as read'], 500);
        }
    }
    
    /**
     * Mark all notifications as read
     * AJAX endpoint
     */
    public function markAllAsRead()
    {
        if (!session()->get('is_logged_in')) {
            return $this->respond(['error' => 'Unauthorized'], 401);
        }
        
        $userId = session()->get('user_id');
        $updated = $this->notificationModel->markAllAsRead($userId);
        
        if ($updated) {
            return $this->respond([
                'success' => true,
                'unread_count' => 0,
                'message' => 'All notifications marked as read'
            ]);
        } else {
            return $this->respond(['error' => 'Failed to mark all as read'], 500);
        }
    }
    
    /**
     * Create a new notification (for testing and system events)
     */
    public function create()
    {
        if (!session()->get('is_logged_in')) {
            return $this->respond(['error' => 'Unauthorized'], 401);
        }
        
        $userId = session()->get('user_id');
        $title = $this->request->getVar('title');
        $message = $this->request->getVar('message');
        $type = $this->request->getVar('type') ?? 'info';
        
        if (empty($title) || empty($message)) {
            return $this->respond(['error' => 'Title and message are required'], 400);
        }
        
        $notificationId = $this->notificationModel->createNotification($userId, $title, $message, $type);
        
        if ($notificationId) {
            return $this->respond([
                'success' => true,
                'notification_id' => $notificationId,
                'message' => 'Notification created successfully'
            ]);
        } else {
            return $this->respond(['error' => 'Failed to create notification'], 500);
        }
    }
    
    /**
     * Delete a notification
     * AJAX endpoint
     */
    public function delete($id)
    {
        if (!session()->get('is_logged_in')) {
            return $this->respond(['error' => 'Unauthorized'], 401);
        }
        
        $userId = session()->get('user_id');
        
        // Verify notification belongs to user
        $notification = $this->notificationModel->getNotificationById($id, $userId);
        if (!$notification) {
            return $this->respond(['error' => 'Notification not found'], 404);
        }
        
        $deleted = $this->notificationModel->delete($id);
        
        if ($deleted) {
            $unreadCount = $this->notificationModel->getUnreadCount($userId);
            return $this->respond([
                'success' => true,
                'unread_count' => $unreadCount,
                'message' => 'Notification deleted successfully'
            ]);
        } else {
            return $this->respond(['error' => 'Failed to delete notification'], 500);
        }
    }
    
    /**
     * Get notification statistics
     * AJAX endpoint
     */
    public function getStats()
    {
        if (!session()->get('is_logged_in')) {
            return $this->respond(['error' => 'Unauthorized'], 401);
        }
        
        $userId = session()->get('user_id');
        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        $allNotifications = $this->notificationModel->getUserNotifications($userId, 100);
        
        // Count by type
        $typeStats = [];
        foreach ($allNotifications as $notification) {
            $type = $notification['type'];
            if (!isset($typeStats[$type])) {
                $typeStats[$type] = 0;
            }
            $typeStats[$type]++;
        }
        
        return $this->respond([
            'unread_count' => $unreadCount,
            'total_count' => count($allNotifications),
            'type_stats' => $typeStats,
            'timestamp' => time()
        ]);
    }
}
