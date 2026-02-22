<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Hashidable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ms_oid',
        'name',
        'email',
        'password',
        'employee_id',
        'job_title',
        'manager_user_id',
        'current_company_id',
        'is_admin',
        'is_evaluator',
        'profile_photo_path',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_evaluator' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function currentCompany()
    {
        return $this->belongsTo(Company::class, 'current_company_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_user_id');
    }

    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_user_id');
    }

    public function memberships()
    {
        return $this->hasMany(OUMembership::class);
    }

    public function organizationalUnits()
    {
        return $this->belongsToMany(OrganizationalUnit::class, 'ou_memberships', 'user_id', 'ou_id')
                    ->withPivot(['role', 'is_primary'])
                    ->withTimestamps();
    }
}
