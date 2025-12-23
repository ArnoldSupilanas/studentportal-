<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use App\Models\UserModel;

class Notifications extends BaseController
{
    protected $notificationModel;
    protected $userModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
    }

    // AJAX endpoint to get notifications
    public function getNotifications()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $notifications = $this->notificationModel->getRecentNotifications($userId, 5);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    // AJAX endpoint to get unread count
    public function getUnreadCount()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }

    // AJAX endpoint to mark notification as read
    public function markAsRead()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $notificationId = $this->request->getPost('notification_id');

        if (!$notificationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification ID required'
            ]);
        }

        // Verify notification belongs to user
        $notification = $this->notificationModel->find($notificationId);
        if (!$notification || $notification['user_id'] != $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid notification'
            ]);
        }

        $result = $this->notificationModel->markAsRead($notificationId);

        if ($result) {
            $unreadCount = $this->notificationModel->getUnreadCount($userId);
            return $this->response->setJSON([
                'success' => true,
                'unread_count' => $unreadCount,
                'message' => 'Notification marked as read'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to mark notification as read'
            ]);
        }
    }

    // AJAX endpoint to mark all notifications as read
    public function markAllAsRead()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $result = $this->notificationModel->markAllAsRead($userId);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'unread_count' => 0,
                'message' => 'All notifications marked as read'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to mark notifications as read'
            ]);
        }
    }

    // Create a new notification (for testing)
    public function createTestNotification()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $title = $this->request->getPost('title') ?? 'Test Notification';
        $message = $this->request->getPost('message') ?? 'This is a test notification';
        $type = $this->request->getPost('type') ?? 'info';

        $result = $this->notificationModel->createNotification($userId, $title, $message, $type);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Test notification created'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create notification'
            ]);
        }
    }

    // View all notifications page
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to('/auth/login');
        }

        $notifications = $this->notificationModel->getUserNotifications($userId, 20);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        $data = [
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'user' => $session->get()
        ];

        return view('notifications/index', $data);
    }

    /**
     * Get method - returns JSON response with unread count and notifications
     * Called via AJAX
     */
    public function get()
    {
        $session = session();
        $userId = $session->get('user_id') ?? $session->get('userID');

        if (!$userId || !$session->get('is_logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $notifications = $this->notificationModel->getRecentNotifications($userId, 5);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark as read method - accepts notification ID and marks it as read
     * Returns success/failure JSON response
     */
    public function mark_as_read($id = null)
    {
        $session = session();
        $userId = $session->get('user_id') ?? $session->get('userID');

        if (!$userId || !$session->get('is_logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get notification ID from URL parameter or POST data
        $notificationId = $id ?? $this->request->getPost('id');

        if (!$notificationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification ID required'
            ]);
        }

        // Verify notification belongs to user
        $notification = $this->notificationModel->find($notificationId);
        if (!$notification || $notification['user_id'] != $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid notification'
            ]);
        }

        $result = $this->notificationModel->markAsRead($notificationId);

        if ($result) {
            $unreadCount = $this->notificationModel->getUnreadCount($userId);
            return $this->response->setJSON([
                'success' => true,
                'unread_count' => $unreadCount,
                'message' => 'Notification marked as read'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to mark notification as read'
            ]);
        }
    }
}
