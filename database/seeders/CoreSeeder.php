<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\OUType;
use App\Models\OrganizationalUnit;

class CoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Default Company
        $company = Company::create([
            'name' => 'CSI Group',
            'slug' => 'csi-group',
            'primary_color' => '#4f46e5',
            'secondary_color' => '#1e293b',
            'domain' => 'csi.com',
        ]);

        // 2. Create OU Types
        $companyType = OUType::create([
            'name' => 'Company',
            'is_root_ou' => true,
        ]);

        $divisionType = OUType::create([
            'name' => 'Division',
            'parent_ou_type_id' => $companyType->id,
        ]);

        $deptType = OUType::create([
            'name' => 'Department',
            'parent_ou_type_id' => $divisionType->id,
        ]);

        // 3. Create Root OU for the company
        $rootOu = OrganizationalUnit::create([
            'name' => 'CSI Group HQ',
            'ou_type_id' => $companyType->id,
            'company_id' => $company->id,
        ]);

        // 4. Create a Shared Service Division
        $ssDivision = OrganizationalUnit::create([
            'name' => 'Shared Services',
            'ou_type_id' => $divisionType->id,
            'parent_ou_id' => $rootOu->id,
            'company_id' => $company->id,
        ]);

        // 5. Create HR Department under SS
        OrganizationalUnit::create([
            'name' => 'Human Resources',
            'ou_type_id' => $deptType->id,
            'parent_ou_id' => $ssDivision->id,
            'company_id' => $company->id,
        ]);
    }
}
