<?php

namespace Vassilidev\Laraperm\Traits;

use Illuminate\Support\Collection;

/**
 * @property Collection $permissions
 * @property Collection $roles
 */
trait HasPerm
{
    use HasPermissions;
    use HasRoles;

    public function isSuperAdmin(): bool
    {
        return $this->hasDirectPermission(config('laraperm.permissions.super-admin'));
    }

    public function declareAsSuperAdmin(): self
    {
        $this->givePermissionTo(config('laraperm.permissions.super-admin'));

        return $this;
    }

    public function revokeSuperAdmin(): self
    {
        $this->revokePermissionTo(config('laraperm.permissions.super-admin'));

        return $this;
    }
}
