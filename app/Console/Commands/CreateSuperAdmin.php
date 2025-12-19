<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\{Company, User};

class CreateSuperAdmin extends Command
{
    protected $signature = 'make:superadmin 
                            {name : Name of the super admin} 
                            {email : Email of the super admin} 
                            {password : Password for the super admin} 
                            {company_name? : Optional company name}';

    protected $description = 'Create a super admin user with role SUPER_ADMIN';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $companyName = $this->argument('company_name') ?? $name.' Company';
        // only one super admin allowed to create via command
        if (User::where('role', 'SUPER_ADMIN')->exists()) {
            return 1;
        }
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");

            return 1;
        }

        $company = Company::create([
            'name' => $companyName,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'company_id' => $company->id,
            'role' => 'SUPER_ADMIN',
        ]);

        $this->info("Super admin '{$name}' created successfully!");
        $this->info("Company '{$companyName}' created with ID: {$company->id}");
        $this->info("Email: {$email} | Password: {$password}");

        return 0;
    }
}
