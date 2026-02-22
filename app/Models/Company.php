<?php

namespace App\Models;

use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, Hashidable;

    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'primary_color',
        'secondary_color',
        'domain',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function organizationalUnits()
    {
        return $this->hasMany(OrganizationalUnit::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'current_company_id');
    }
}
