<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Constants\RoleType;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRoleName(int $role)
    {
        switch ($role) {
            case RoleType::SUPER_ADMIN:
                return 'super admin';
                break;
            case RoleType::ADMIN:
                return 'admin';
                break;
            case RoleType::COMPANY:
                return 'company';
                break;
            default:
                return 'employee';
                break;
        }
    }

    public function isSuperAdmin()
    {
        return $this->role == RoleType::SUPER_ADMIN;
    }
    public function isAdmin()
    {
        return $this->role == RoleType::ADMIN;
    }
    public function isCompany()
    {
        return $this->role == RoleType::COMPANY;

    }
    public function isEmployee()
    {
        return $this->role == RoleType::EMPLOYEE;
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
