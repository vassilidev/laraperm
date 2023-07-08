<?php

namespace Vassilidev\Laraperm\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Vassilidev\Laraperm\Models\Scopes\HideSuperAdminPermissionsScope;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public static function booted(): void
    {
        self::addGlobalScope(new HideSuperAdminPermissionsScope);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function scopeSuperAdmin(Builder $query): Builder|Model
    {
        return $query
            ->withoutGlobalScopes()
            ->firstWhere('name', config('laraperm.permissions.super-admin'));
    }
}
