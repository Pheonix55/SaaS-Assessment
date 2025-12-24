<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{DB, Hash};
use App\Enums\CompanyStatus;
use App\Models\{Company, User};
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class CreateSuperAdmin extends Command
{
    protected $signature = 'make:superadmin
                            {name : Name of the super admin}
                            {email : Email of the super admin}
                            {password : Password for the super admin}
                            {company_name? : Optional company name}';

    protected $description = 'Create a SUPER_ADMIN user with company and role';

    public function handle(): int
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $companyName = $this->argument('company_name') ?? "{$name} Company";

        if (User::where('role', 'SUPER_ADMIN')->exists()) {
            $this->error('SUPER_ADMIN already exists.');

            return Command::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists.");

            return Command::FAILURE;
        }

        DB::transaction(function () use ($name, $email, $password, $companyName) {

            // Create Company
            $company = Company::create([
                'name' => $companyName,
                'email' => $email,
                'status' => CompanyStatus::ACTIVE,
                'password' => Hash::make($password),
            ]);

            // Set team context for Spatie
            app(PermissionRegistrar::class)->setPermissionsTeamId($company->id);

            // Create SUPER_ADMIN role if missing
            $role = Role::firstOrCreate([
                'name' => 'SUPER_ADMIN',
                'guard_name' => 'web',
                'company_id' => $company->id,
            ]);

            // Create User
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'company_id' => $company->id,
                'role' => 'SUPER_ADMIN',
            ]);

            // Assign role
            $user->assignRole($role);

            $this->info("SUPER_ADMIN '{$name}' created successfully.");
            $this->info("Company '{$companyName}' created (ID: {$company->id}).");
            $this->info("Login Email: {$email}");
        });

        return Command::SUCCESS;
    }
}
