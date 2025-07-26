<?php

// app/Filters/AuthFilter.php
namespace App\Filters;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class RoleFilter implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        $user = $request->user;
        $method = $request->getMethod();
        $role = $user->role ?? null;
        $uri = service('uri');

        if ($role == 'customer' && $method != 'GET' && $uri->getSegment(2) == 'products') {
            return Services::response()
                ->setJSON([
                    'meta' => [
                        'code' => 403,
                        'status' => 'error',
                        'message' => 'Unauthorized to access this resource'
                    ],
                    'data' => null
                ])
                ->setStatusCode(403);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
