<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Vassilidev\Laraperm\Models\Permission;
use Vassilidev\Laraperm\Models\Role;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permissions', static function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('roles', static function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_user', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Permission::class, 'permission_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('role_user', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Role::class, 'role_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('permission_role', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Permission::class, 'permission_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Role::class, 'role_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });

        Permission::create([
            'name' => config('laraperm.permissions.super-admin'),
            'description' => 'If you have this you know :D',
        ]);
    }

    public function down(): void
    {
        Schema::drop('permission_role');
        Schema::drop('role_user');
        Schema::drop('permission_user');
        Schema::drop('permissions');
        Schema::drop('roles');
    }
};
