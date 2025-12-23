<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\NotificationModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $notificationModel;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->notificationModel = new NotificationModel();

        // Initialize session
        $this->session = \Config\Services::session();
    }

    /**
     * Get unread notification count for current user
     */
    protected function getUnreadNotificationCount()
    {
        $userId = $this->session->get('user_id') ?? $this->session->get('userID');
        
        if ($userId && $this->session->get('is_logged_in')) {
            return $this->notificationModel->getUnreadCount($userId);
        }
        
        return 0;
    }

    /**
     * Pass notification count to view
     */
    protected function passNotificationData()
    {
        $data = [
            'unread_notification_count' => $this->getUnreadNotificationCount()
        ];
        
        // Share data with all views
        \Config\Services::renderer()->setData($data, 'raw');
    }
}
