<?php

namespace App\Models;

use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OUType extends Model
{
    use HasFactory, Hashidable;

    protected $table = 'ou_types';

    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'is_root_ou',
        'parent_ou_type_id',
    ];

    protected $casts = [
        'is_root_ou' => 'boolean',
    ];

    public function organizationalUnits()
    {
        return $this->hasMany(OrganizationalUnit::class, 'ou_type_id');
    }

    public function parentType()
    {
        return $this->belongsTo(OUType::class, 'parent_ou_type_id');
    }

    public function childTypes()
    {
        return $this->hasMany(OUType::class, 'parent_ou_type_id');
    }
}
