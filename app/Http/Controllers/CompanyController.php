<?php

namespace App\Http\Controllers;

use App\Constants\RoleType;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyResourceCollection;
use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    private CompanyRepositoryInterface $companyRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository, UserRepositoryInterface  $userRepository)
    {
        $this->companyRepository = $companyRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Company::class);
        $companies = $this->companyRepository->getPaginated();
        return $this->respondWithResourceCollection(new CompanyResourceCollection($companies), 'All Companies fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCompanyRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $this->authorize('create', Company::class);

        $user = $this->userRepository->findById($request->user_id);
        if ($user && $user->company()->exists()){
            $this->respondError('A company has already been created for the supplied user');
        }
        if ($user && ($user->role  != RoleType::COMPANY)){
            $this->respondError('Only users with role of company can have a company');
        }
        $company = $this->companyRepository->create($request->validated());
        return $this->respondWithResource(new CompanyResource($company), 'Company created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Company $company): JsonResponse
    {
        $this->authorize('view', $company);
       return $this->respondWithResource(new CompanyResource($company), 'Company fetched successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCompanyRequest $request
     * @param Company $company
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        $this->authorize('update', $company);
        $this->companyRepository->update( $company->id, $request->validated());
        return $this->respondWithResource(new CompanyResource($company->refresh()), 'Company updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Company $company): JsonResponse
    {
        $this->authorize('delete', $company);
        $company->delete();
        return $this->respondSuccess([], 'Company deleted successfully');
    }
}
