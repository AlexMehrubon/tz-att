<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexUserRequest;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

final class UserController extends Controller
{
    public function __construct(
        protected readonly UserService $userService
    )
    {
    }

    public function index(IndexUserRequest $request): JsonResponse
    {
        $users = $this->userService->getAll(
            filters:  $request->filters(),
            perPage: $request->perPage(),
            sort: $request->sort(),
        );

        return response()->json($users);
    }
}
