<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'support', 'user'];

        Company::all()->each(function ($company) use ($roles) {
            foreach ($roles as $role) {
                Role::firstOrCreate([
                    'name' => $role,
                    'company_id' => $company->id,
                ]);
            }
        });
    }
}
