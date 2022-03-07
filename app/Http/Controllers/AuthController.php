<?php
namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use InfluencerMicroservices\UserService;

class AuthController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function user(Request $request)
    {
        return new UserResource($this->userService->getUser());
    }
}
