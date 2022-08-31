<?php

namespace App\Http\Controllers;

use App\Constants\RoleType;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    /**
     * @throws AuthorizationException
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);
        $data = $request->validated();
        //reset role to admin
        $data['role'] = RoleType::ADMIN;
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);
        return $this->respondWithResource(new UserResource($user), 'User created successfully');
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        $this->userRepository->deleteById($user->id);
        return $this->respondSuccess([], 'User deleted successfully');
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);
        $data = $request->validated();
        $this->userRepository->update($user->id, $data);
        return $this->respondWithResource(new UserResource($user->refresh()), 'User updated successfully');
    }
}
