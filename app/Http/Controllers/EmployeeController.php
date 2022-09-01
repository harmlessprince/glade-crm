<?php

namespace App\Http\Controllers;

use App\Constants\RoleType;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeeResourceCollection;
use App\Http\Resources\EmptyResource;
use App\Models\Company;
use App\Models\Employee;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{

    private EmployeeRepositoryInterface $employeeRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository, UserRepositoryInterface $userRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Company $company): JsonResponse
    {
        $this->authorize('viewAny', Employee::class);
        $employees = $this->employeeRepository->getEmployees($company->id);
        return $this->respondWithResourceCollection(new EmployeeResourceCollection($employees));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmployeeRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $this->authorize('create', Employee::class);
        $user = $this->userRepository->findById($request->user_id);
        if ($user) {
            if ($user->role != RoleType::EMPLOYEE) {
                return $this->respondError('The supplied user does not have a role of type employee');
            }
            if ($user->employee()->exists()) {
                return $this->respondError('The supplied user already has a company and employee profile');
            }
        }
        $employee = $this->employeeRepository->create($request->validated());
        return $this->respondWithResource(new EmptyResource($employee), 'Employee created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param Employee $employee
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Employee $employee): JsonResponse
    {
//        dd('kk');
        $this->authorize('view', $employee);
        return $this->respondWithResource(new EmployeeResource($employee->load('company')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $this->authorize('update', $employee);
        $this->employeeRepository->update($employee->id, $request->validated());
        return $this->respondWithResource(new EmployeeResource($employee->refresh()->load('company')), 'Employee profile updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Employee $employee
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $this->authorize('delete', $employee);
        $employee->delete();
        return $this->respondSuccess([],'Employee deleted successfully');
    }
}
