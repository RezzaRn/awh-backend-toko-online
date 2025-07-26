<?php

// app/Filters/AuthFilter.php
namespace App\Filters;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthFilter implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        $key = getenv('JWT_SECRET');
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return Services::response()
                ->setJSON([
                    'meta' => [
                        'code' => 401,
                        'status' => 'error',
                        'message' => 'Unauthorized: Missing or invalid token'
                    ],
                    'data' => null
                ])
                ->setStatusCode(401);
        }

        $token = $matches[1];

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $request->user = $decoded->data;
        } catch (\Exception $e) {
            return Services::response()
                ->setJSON([
                    'meta' => [
                        'code' => 401,
                        'status' => 'error',
                        'message' => 'Unauthorized: Token expired or invalid'
                    ],
                    'data' => null
                ])
                ->setStatusCode(401);
        }

        $uri = service('uri');
        $method = $request->getMethod();
        $path = $uri->getSegment(2);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
