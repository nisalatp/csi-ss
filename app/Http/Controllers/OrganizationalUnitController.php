<?php

namespace App\Http\Controllers;

use App\Models\OrganizationalUnit;
use App\Models\OUType;
use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganizationalUnitController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = OrganizationalUnit::with(['type', 'parent', 'company']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return Inertia::render('Admin/OU/Index', [
            'organizationalUnits' => $query->latest()->get()->map(function($ou) {
                return [
                    'id' => $ou->getRouteKey(),
                    'name' => $ou->name,
                    'description' => $ou->description,
                    'ou_type_id' => $ou->ou_type_id ? \Vinkla\Hashids\Facades\Hashids::encode($ou->ou_type_id) : null,
                    'type' => $ou->type ? ['id' => $ou->type->getRouteKey(), 'name' => $ou->type->name] : null,
                    'parent_ou_id' => $ou->parent_ou_id ? \Vinkla\Hashids\Facades\Hashids::encode($ou->parent_ou_id) : null,
                    'parent' => $ou->parent ? ['id' => $ou->parent->getRouteKey(), 'name' => $ou->parent->name] : null,
                    'company_id' => $ou->company_id ? \Vinkla\Hashids\Facades\Hashids::encode($ou->company_id) : null,
                    'company' => $ou->company ? ['id' => $ou->company->getRouteKey(), 'name' => $ou->company->name] : null,
                    'color' => $ou->color,
                    'icon' => $ou->icon,
                ];
            }),
            'ouTypes' => OUType::with('parentType')->get()->map(function($type) {
                return [
                    'id' => $type->getRouteKey(),
                    'name' => $type->name,
                    'description' => $type->description,
                    'color' => $type->color,
                    'icon' => $type->icon,
                    'is_root_ou' => $type->is_root_ou,
                    'parent_ou_type_id' => $type->parent_ou_type_id ? \Vinkla\Hashids\Facades\Hashids::encode($type->parent_ou_type_id) : null,
                    'parent_type' => $type->parentType ? ['id' => $type->parentType->getRouteKey(), 'name' => $type->parentType->name] : null,
                ];
            }),
            'companies' => Company::all()->map(function($company) {
                return [
                    'id' => $company->getRouteKey(),
                    'name' => $company->name,
                ];
            }),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        
        // Decode hashed IDs
        if ($request->filled('ou_type_id')) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($request->ou_type_id);
            if (!empty($decoded)) $input['ou_type_id'] = $decoded[0];
        }
        if ($request->filled('parent_ou_id')) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($request->parent_ou_id);
            if (!empty($decoded)) $input['parent_ou_id'] = $decoded[0];
        }
        if ($request->filled('company_id')) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($request->company_id);
            if (!empty($decoded)) $input['company_id'] = $decoded[0];
        }

        $validated = \Illuminate\Support\Facades\Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ou_type_id' => 'required|exists:ou_types,id',
            'parent_ou_id' => 'nullable|exists:organizational_units,id',
            'company_id' => 'required|exists:companies,id',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ])->validate();

        OrganizationalUnit::create($validated);

        return redirect()->back()->with('success', 'Organizational Unit created successfully');
    }

    public function update(Request $request, OrganizationalUnit $organizationalUnit)
    {
        $input = $request->all();
        
        // Decode hashed IDs
        if ($request->filled('ou_type_id')) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($request->ou_type_id);
            if (!empty($decoded)) $input['ou_type_id'] = $decoded[0];
        }
        if ($request->filled('parent_ou_id')) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($request->parent_ou_id);
            if (!empty($decoded)) $input['parent_ou_id'] = $decoded[0];
        }
        if ($request->filled('company_id')) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($request->company_id);
            if (!empty($decoded)) $input['company_id'] = $decoded[0];
        }

        $validated = \Illuminate\Support\Facades\Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ou_type_id' => 'required|exists:ou_types,id',
            'parent_ou_id' => 'nullable|exists:organizational_units,id',
            'company_id' => 'required|exists:companies,id',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ])->validate();

        $organizationalUnit->update($validated);

        return redirect()->back()->with('success', 'Organizational Unit updated successfully');
    }

    public function destroy(OrganizationalUnit $organizationalUnit)
    {
        $organizationalUnit->delete();
        return redirect()->back()->with('success', 'Organizational Unit deleted successfully');
    }

    public function storeType(Request $request)
    {
        $input = $request->all();

        if ($request->filled('parent_ou_type_id')) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($request->parent_ou_type_id);
            if (!empty($decoded)) $input['parent_ou_type_id'] = $decoded[0];
        }

        $validated = \Illuminate\Support\Facades\Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_root_ou' => 'required|boolean',
            'parent_ou_type_id' => 'nullable|exists:ou_types,id',
        ])->validate();

        OUType::create($validated);

        return redirect()->back()->with('success', 'OU Type created successfully');
    }

    public function updateType(Request $request, OUType $ouType)
    {
        $input = $request->all();

        if ($request->filled('parent_ou_type_id')) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($request->parent_ou_type_id);
            if (!empty($decoded)) $input['parent_ou_type_id'] = $decoded[0];
        }

        $validated = \Illuminate\Support\Facades\Validator::make($input, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_root_ou' => 'required|boolean',
            'parent_ou_type_id' => 'nullable|exists:ou_types,id',
        ])->validate();

        $ouType->update($validated);

        return redirect()->back()->with('success', 'OU Type updated successfully');
    }

    public function destroyType(OUType $ouType)
    {
        // Check if types are in use
        if ($ouType->organizationalUnits()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete OU Type because it is in use by organizational units');
        }

        $ouType->delete();
        return redirect()->back()->with('success', 'OU Type deleted successfully');
    }
}
