<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use App\Http\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
class AuthController extends Controller
{
    /**
     * @var AuthInterface
     */
    protected $authInterface;
    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authInterface->login($request);

        return response()->json([
            'success' => Arr::get($data, 'success', true),
            'token' => Arr::get($data, 'token'),
            'user' => request()->user(),
            'message' => Arr::get($data, 'message'),
        ], Arr::get($data, 'code', Response::HTTP_OK));
    }

    public function logout(Request $request)
    {
        //Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ], Response::HTTP_OK);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
