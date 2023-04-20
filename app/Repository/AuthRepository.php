<?php

namespace App\Repository;

use JWTAuth;
use App\Interfaces\AuthInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthRepository implements AuthInterface
{
    public function login($request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return [
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                    'code' => HttpResponse::HTTP_UNAUTHORIZED
                ];
            }
        } catch (JWTException $e) {
            return [
                'success' => false,
                'message' => 'Authentication failed! Email or password is incorrect.',
                'code' => HttpResponse::HTTP_UNAUTHORIZED
            ];
        }

        return ['token' => $token, 'message' => __('Logged in successfully.')];
    }
}
