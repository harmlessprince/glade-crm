<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->userRepository = $repository;
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);
        $user = $this->userRepository->create($request->validated());
        return $this->respondWithResource(new UserResource($user), 'User created successfully');
    }
}
