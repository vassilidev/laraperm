<?php

namespace Vassilidev\Laraperm\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Vassilidev\Laraperm\Models\Permission;
use Vassilidev\Laraperm\Models\Role;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasPermissionViaRole($ability): bool
    {
        return Permission::query()
            ->where('name', $ability)
            ->whereHas('roles', function ($q) {
                return $q
                    ->whereIn('roles.id', $this->roles()->pluck('roles.id'));
            })
            ->exists();
    }
}
