<?php

namespace App\Models;

use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationalUnit extends Model
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'name',
        'description',
        'ou_type_id',
        'parent_ou_id',
        'company_id',
        'icon',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function type()
    {
        return $this->belongsTo(OUType::class, 'ou_type_id');
    }

    public function parent()
    {
        return $this->belongsTo(OrganizationalUnit::class, 'parent_ou_id');
    }

    public function children()
    {
        return $this->hasMany(OrganizationalUnit::class, 'parent_ou_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function memberships()
    {
        return $this->hasMany(OUMembership::class, 'ou_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'ou_memberships', 'ou_id', 'user_id')
                    ->withPivot(['role', 'is_primary'])
                    ->withTimestamps();
    }
}
