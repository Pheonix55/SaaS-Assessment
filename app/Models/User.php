<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Billable, HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'role',
        'current_plan',
        'phone',
        'about',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function userMessages($thread_id)
    {
        return SupportMessage::with('supportThread')->where('support_thread_id', $thread_id)->get();
    }

    /**
     * Check if the user has a role in a specific company/team context
     *
     * @param  string|array  $roleNames
     */
    public function hasRoleInCompany($roleNames, ?int $companyId = null, ?string $guardName = null): bool
    {
        $companyId = $companyId ?? $this->company_id;
        $guardName = $guardName ?? 'web';

        // set team context
        app(\Spatie\Permission\PermissionRegistrar::class)
            ->setPermissionsTeamId($companyId);

        $userRoles = $this->roles()->get();

        $roleNames = is_array($roleNames) ? $roleNames : [$roleNames];

        foreach ($roleNames as $roleName) {
            $role = \Spatie\Permission\Models\Role::where('name', $roleName)
                ->where('guard_name', $guardName)
                ->where('company_id', $companyId)
                ->first();

            if ($role && $userRoles->contains('id', $role->id)) {
                return true;
            }
        }

        return false;
    }

    
}
