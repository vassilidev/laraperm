<?php

namespace Vassilidev\Laraperm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Vassilidev\Laraperm\Traits\HasPermissions;

class Role extends Model
{
    use HasPermissions;

    protected $fillable = [
        'name',
        'description',
    ];
}
