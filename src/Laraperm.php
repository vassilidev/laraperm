<?php

namespace Vassilidev\Laraperm;

use App\Models\User;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Vassilidev\Laraperm\Traits\HasPerm;

class Laraperm
{
    /**
     * @param Authorizable $authorizable
     * @param string $ability
     * @return bool|null
     */
    public function authorize(Authorizable $authorizable, string $ability): ?bool
    {
        if (!is_a($authorizable, User::class)) {
            return null;
        }

        if (!in_array(HasPerm::class, class_uses_recursive($authorizable), true)) {
            return null;
        }

        /** @var User $authorizable */
        if ($authorizable->isSuperAdmin()) {
            return true;
        }

        return $authorizable->hasDirectPermission($ability) || $authorizable->hasPermissionViaRole($ability);
    }
}
