<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Authentication Filter
 * Ensures user is logged in before accessing protected routes
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Debug logging
        log_message('debug', 'AuthFilter - Checking authentication');
        log_message('debug', 'AuthFilter - Session Data: ' . json_encode([
            'is_logged_in' => $session->get('is_logged_in'),
            'logged_in' => $session->get('logged_in'),
            'user_id' => $session->get('userID') ?? $session->get('user_id'),
            'email' => $session->get('email')
        ]));
        
        // Check if user is logged in
        $isLoggedIn = $session->get('is_logged_in') || $session->get('logged_in');
        $userId = $session->get('userID') ?? $session->get('user_id');
        
        if (!$isLoggedIn || !$userId) {
            // User is not logged in
            log_message('debug', 'AuthFilter - User not authenticated');
            
            if ($request->isAJAX()) {
                // Return JSON response for AJAX requests
                return service('response')->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => 'Please login to access this page'
                ]);
            }
            
            // Redirect to login page for regular requests
            session()->setFlashdata('error', 'Please login to access this page');
            return redirect()->to('/login');
        }
        
        log_message('debug', 'AuthFilter - User authenticated, allowing access');
        
        // Check for session timeout (optional - 2 hours)
        $loginTime = $session->get('login_time');
        if ($loginTime && (time() - $loginTime) > 7200) {
            // Session expired
            log_message('debug', 'AuthFilter - Session expired, destroying session');
            $session->destroy();
            
            if ($request->isAJAX()) {
                return service('response')->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => 'Your session has expired. Please login again.'
                ]);
            }
            
            session()->setFlashdata('error', 'Your session has expired. Please login again.');
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
