<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
        {
            // 🔐 Cek sudah login atau belum
            if (!session()->get('id_user')) {
                return redirect()->to('/');
            }

            // 🔐 Kalau ada parameter role
            if (!empty($arguments)) {

                $userRole = session()->get('role');

                if (!in_array($userRole, $arguments)) {
                    return redirect()->to('/login');
                }
            }
        }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
