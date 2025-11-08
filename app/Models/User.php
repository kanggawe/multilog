<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'created_by',
        'phone',
        'company',
        'address',
        'bio',
        'language',
        'timezone',
        'date_format',
        'theme',
        'email_notifications',
        'push_notifications',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the user who created this user.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get users created by this user.
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if user has a minimum role level.
     */
    public function hasMinLevel(int $level): bool
    {
        return $this->role && $this->role->level >= $level;
    }

    /**
     * Check if user is admin (Level 9 or higher).
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->level >= 9;
    }

    /**
     * Check if user is manager (Level 7 or higher).
     */
    public function isManager(): bool
    {
        return $this->role && $this->role->level >= 7 && $this->role->level < 9;
    }

    /**
     * Check if user is regular user (Level 1-6).
     */
    public function isUser(): bool
    {
        return $this->role && $this->role->level < 7;
    }

    /**
     * Check if user is super admin (Level 10).
     */
    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->level >= 10;
    }

    /**
     * Check if current user can manage target user.
     * - Level 9-10 can manage any user with lower level
     * - Level 8 can only manage users they created with lower level
     * - Other levels can manage users with lower level
     */
    public function canManage(User $targetUser): bool
    {
        if (!$this->role || !$targetUser->role) {
            return false;
        }

        // Cannot manage user with same or higher level
        if ($this->role->level <= $targetUser->role->level) {
            return false;
        }

        // Level 9 and 10 can manage any user with lower level
        if ($this->role->level >= 9) {
            return true;
        }

        // Level 8 can only manage users they created
        if ($this->role->level == 8) {
            return $targetUser->created_by === $this->id;
        }

        // Other levels can manage users with lower level
        return true;
    }

    /**
     * Check if current user can assign a specific role.
     * A user can only assign roles with level lower than their own.
     */
    public function canAssignRole(Role $role): bool
    {
        if (!$this->role) {
            return false;
        }

        // User can only assign roles with strictly lower level
        return $this->role->level > $role->level;
    }

    /**
     * Get roles that current user can assign to others.
     */
    public function getAssignableRoles()
    {
        if (!$this->role) {
            return collect([]);
        }

        // Return roles with level lower than current user
        return Role::where('level', '<', $this->role->level)
            ->orderBy('level', 'asc')
            ->get();
    }
}
