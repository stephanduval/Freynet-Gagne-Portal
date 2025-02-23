<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password', 'remember_token',
    ]; // ✅ Hides unnecessary fields


    protected $with = []; // ✅ Prevents auto-loading relationships

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_reset_required' => 'boolean',
    ];

    /**
     * Define a many-to-many relationship with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,    // The related model
            'user_roles',   // The pivot table name
            'user_id',      // The foreign key on the pivot table for the User model
            'role_id'       // The foreign key on the pivot table for the Role model
        );
    }

    /**
     * Define a many-to-many relationship with Company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(
            Company::class, // The related model
            'user_company', // The pivot table name
            'user_id',      // The foreign key on the pivot table for the User model
            'company_id'    // The foreign key on the pivot table for the Company model
        );
    }

    /**
     * The subjects that belong to the user.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_user')
                    ->withTimestamps();
    }

    /**
     * Get all permissions for the user through their roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionsAttribute()
{
    if (!$this->relationLoaded('roles')) {
        $this->load('roles.permissions');
    }

    $permissions = $this->roles->flatMap(function ($role) {
        return $role->permissions->map(function ($permission) {
            return [
                'id' => $permission->id ?? null,
                'action' => $permission->action ?? 'read',
                'subject' => $permission->subject ?? 'all',
            ];
        });
    })->unique('id')->values();

    \Log::info("User [{$this->id}] permissions:", $permissions->toArray());

    return $permissions;
}


    

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Detach all related companies and roles
            $user->companies()->detach();
            $user->roles()->detach();
        });
    }
}
