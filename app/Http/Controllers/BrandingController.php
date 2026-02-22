<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Branding/Index', [
            'companies' => Company::all()->map(function($company) {
                return [
                    'id' => $company->getRouteKey(),
                    'name' => $company->name,
                    'logo_path' => $company->logo_path,
                    'primary_color' => $company->primary_color,
                    'secondary_color' => $company->secondary_color,
                ];
            }),
        ]);
    }

    public function edit(Company $company)
    {
        return Inertia::render('Admin/Branding/Edit', [
            'company' => [
                'id' => $company->getRouteKey(),
                'name' => $company->name,
                'logo_path' => $company->logo_path,
                'primary_color' => $company->primary_color,
                'secondary_color' => $company->secondary_color,
            ],
        ]);
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'primary_color', 'secondary_color']);

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                Storage::delete($company->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($data);

        return redirect()->back()->with('success', 'Branding updated successfully');
    }
}
