<?php

namespace App\Services;

use App\Models\Company;
use Spatie\Permission\Models\Role;

class CompanyBootstrapService
{
    public function bootstrap(Company $company): void
    {
        $this->createDefaultRoles($company);
        // $this->createDefaultSettings($company);
    }

    protected function createDefaultRoles(Company $company): void
    {
        $roles = ['admin', 'support', 'user'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'company_id' => $company->id,
                'guard_name' => 'web',
            ]);
        }
    }

    
}
