<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class BrandingService
{
    protected ?Company $currentCompany = null;

    public function getCurrentCompany(): ?Company
    {
        if ($this->currentCompany) {
            return $this->currentCompany;
        }

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->current_company_id) {
                $this->currentCompany = $user->currentCompany;
                return $this->currentCompany;
            }
        }

        // Fallback or session-based company loading if needed
        return null;
    }

    public function getBranding(): array
    {
        $company = $this->getCurrentCompany();

        return [
            'name' => $company->name ?? config('app.name', 'Shared Services'),
            'logo' => $company->logo_path ?? '/images/default-logo.png',
            'primary_color' => $company->primary_color ?? '#4f46e5',
            'secondary_color' => $company->secondary_color ?? '#1e293b',
        ];
    }
}
