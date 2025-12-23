<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'title', 'message', 'type', 'is_read', 'read_at'];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    // Get unread notifications for a user
    public function getUnreadNotifications($userId)
    {
        return $this->where('user_id', $userId)
                   ->where('is_read', false)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    // Get all notifications for a user with pagination
    public function getUserNotifications($userId, $limit = 10, $offset = 0)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll($limit, $offset);
    }

    // Get unread count for a user
    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                   ->where('is_read', false)
                   ->countAllResults();
    }

    // Mark notification as read
    public function markAsRead($notificationId)
    {
        return $this->update($notificationId, [
            'is_read' => true,
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Mark all notifications as read for a user
    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)
                   ->where('is_read', false)
                   ->set([
                       'is_read' => true,
                       'read_at' => date('Y-m-d H:i:s')
                   ])
                   ->update();
    }

    // Create a new notification
    public function createNotification($userId, $title, $message, $type = 'info')
    {
        return $this->insert([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Get recent notifications for AJAX
    public function getRecentNotifications($userId, $limit = 5)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll($limit);
    }
}
