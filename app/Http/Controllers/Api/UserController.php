<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Repository\UserRepository;
use App\Services\AuthenticationService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResponseTrait;
    public function register(UserRequest $request, UserRepository $userRepository)
    {
        $response = $userRepository->create(array_merge($request->validated(), ['is_admin' => 0]));

        return $this->jsonResponse(['data' => $response], 201);
    }

    public function login(Request $request, AuthenticationService $authenticationService)
    {
        $response = $authenticationService->login($request);
        return isset($response['success']) ? $this->jsonResponse(['data' => $response['success']]) : $this->jsonResponse($response, 401);
    }
}
