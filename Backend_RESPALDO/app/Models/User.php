<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUuids, Notifiable;

    protected $table = 'core.users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'persona_id',
        'primary_campus_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function primaryCampus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'primary_campus_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'core.user_role', 'user_id', 'role_id')
                    ->withPivot('campus_id');
    }

    public function hasPermission(string $permissionName, ?string $campusId = null): bool
    {
        return $this->roles()
            ->when($campusId, fn($query) => $query->where('core.user_role.campus_id', $campusId))
            ->whereHas('permissions', fn($query) => $query->where('name', $permissionName))
            ->exists();
    }
}