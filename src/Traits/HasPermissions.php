<?php

namespace Vassilidev\Laraperm\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Vassilidev\Laraperm\Models\Permission;
use Vassilidev\Laraperm\Models\Scopes\HideSuperAdminPermissionsScope;

trait HasPermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasDirectPermission(string $permission): bool
    {
        return $this->permissions()
            ->withoutGlobalScope(HideSuperAdminPermissionsScope::class)
            ->where('name', $permission)
            ->exists();
    }

    public function givePermissionTo(...$permissions): self
    {
        $permissions = Permission::query()
            ->withoutGlobalScope(HideSuperAdminPermissionsScope::class)
            ->whereIn('name', $permissions)
            ->get();

        $this->permissions()->syncWithoutDetaching($permissions);

        return $this;
    }

    public function revokePermissionTo(...$permissions): self
    {
        $permissions = Permission::query()
            ->withoutGlobalScope(HideSuperAdminPermissionsScope::class)
            ->whereIn('name', $permissions)
            ->get();

        $this->permissions()->detach($permissions);

        return $this;
    }
}
