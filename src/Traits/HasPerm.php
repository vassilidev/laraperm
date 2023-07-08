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
}
