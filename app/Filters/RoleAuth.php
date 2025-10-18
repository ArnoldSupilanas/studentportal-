<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('role');
        $uri = service('uri')->getPath();

        // Normalize
        $path = '/' . trim($uri, '/');

        if (!$session->get('isLoggedIn')) {
            // Not logged in -> redirect to announcements
            return redirect()->to('/announcements')->with('error', 'Access Denied: Insufficient Permissions');
        }

        if ($role === 'admin') {
            // allow any /admin route
            if (strpos($path, '/admin') === 0) {
                return;
            }
            // admin can also access other pages; allow
            return;
        }

        if ($role === 'teacher') {
            if (strpos($path, '/teacher') === 0) {
                return;
            }
            return redirect()->to('/announcements')->with('error', 'Access Denied: Insufficient Permissions');
        }

        if ($role === 'student') {
            if (strpos($path, '/student') === 0 || $path === '/announcements' || $path === 'announcements') {
                return;
            }
            return redirect()->to('/announcements')->with('error', 'Access Denied: Insufficient Permissions');
        }

        // Default deny
        return redirect()->to('/announcements')->with('error', 'Access Denied: Insufficient Permissions');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No-op
    }
}
