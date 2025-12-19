<?php

namespace App\Services;

use App\Models\{Company, Role};

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

    // protected function createDefaultSettings(Company $company): void
    // {
    //     Setting::firstOrCreate([
    //         'company_id' => $company->id,
    //         'key' => 'timezone',
    //         'value' => 'UTC',
    //     ]);
    // }
}
