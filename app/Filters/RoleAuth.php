<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Role-Based Authorization Filter
 * Ensures user has the correct role to access specific routes
 */
class RoleAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('role');
        // Handle legacy "instructor" role
        if ($role === 'instructor') {
            $role = 'teacher';
        }
        $uri = service('uri')->getPath();

        // Debug logging
        log_message('debug', 'RoleAuth Filter - URI: ' . $uri);
        log_message('debug', 'RoleAuth Filter - Role: ' . ($role ?? 'NULL'));
        log_message('debug', 'RoleAuth Filter - Arguments: ' . json_encode($arguments));
        log_message('debug', 'RoleAuth Filter - Session Data: ' . json_encode([
            'is_logged_in' => $session->get('is_logged_in'),
            'logged_in' => $session->get('logged_in'),
            'user_id' => $session->get('userID') ?? $session->get('user_id'),
            'email' => $session->get('email')
        ]));

        // Normalize path
        $path = '/' . trim($uri, '/');

        // Public routes that don't require authentication
        $publicRoutes = ['/login', '/register', '/', '/about', '/contact', '/announcements'];
        
        // Allow access to public routes
        if (in_array($path, $publicRoutes)) {
            return; // Allow access to public routes
        }

        // Check if user is logged in
        if (!$session->get('is_logged_in') && !$session->get('logged_in')) {
            if ($request->isAJAX()) {
                return service('response')->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => 'Please login to access this page'
                ]);
            }
            session()->setFlashdata('error', 'Please login to access this page');
            return redirect()->to('/login');
        }

        // If arguments are provided, check if user has required role
        if (!empty($arguments)) {
            $allowedRoles = is_array($arguments) ? $arguments : [$arguments];
            
            if (!in_array($role, $allowedRoles)) {
                if ($request->isAJAX()) {
                    return service('response')->setStatusCode(403)->setJSON([
                        'success' => false,
                        'message' => 'Access Denied: You do not have permission to access this page'
                    ]);
                }
                session()->setFlashdata('error', 'Access Denied: You do not have permission to access this page');
                return redirect()->to('/dashboard');
            }
            
            return; // User has required role
        }

        // Path-based role checking (backward compatibility)
        if ($role === 'admin') {
            // Admin can access any route
            return;
        }

        if ($role === 'teacher') {
            // Teacher can access teacher routes and dashboard
            if (strpos($path, '/teacher') === 0 || $path === '/dashboard') {
                return;
            }
            if ($request->isAJAX()) {
                return service('response')->setStatusCode(403)->setJSON([
                    'success' => false,
                    'message' => 'Access Denied: Insufficient Permissions'
                ]);
            }
            session()->setFlashdata('error', 'Access Denied: Insufficient Permissions');
            return redirect()->to('/dashboard');
        }

        if ($role === 'student') {
            // Student can access student routes, announcements, and dashboard
            if (strpos($path, '/student') === 0 || $path === '/announcements' || $path === '/dashboard') {
                return;
            }
            if ($request->isAJAX()) {
                return service('response')->setStatusCode(403)->setJSON([
                    'success' => false,
                    'message' => 'Access Denied: Insufficient Permissions'
                ]);
            }
            session()->setFlashdata('error', 'Access Denied: Insufficient Permissions');
            return redirect()->to('/dashboard');
        }

        // Default deny - unknown role
        if ($request->isAJAX()) {
            return service('response')->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Access Denied: Invalid user role'
            ]);
        }
        session()->setFlashdata('error', 'Access Denied: Invalid user role');
        return redirect()->to('/login');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
