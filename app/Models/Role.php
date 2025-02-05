<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * The permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
{
    return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')                
    ->withTimestamps();
}

    /**
     * The users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}
