<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Available user roles
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_STAFF = 'staff';
    const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'role',
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
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    /**
     * Relasi ke transaksi
     */
    public function transaksis()
    {
        return $this->hasMany(\App\Models\Transaksi::class);
    }

    /**
     * Get formatted created date
     */
    public function getFormattedCreatedAtAttribute()
    {
        if ($this->created_at instanceof \Carbon\Carbon) {
            return $this->created_at->format('d/m/Y H:i');
        }
        
        return 'Tidak diketahui';
    }

    /**
     * Get user status badge
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->email_verified_at) {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>';
        }
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Unverified</span>';
    }

    /**
     * Get role badge with color
     */
    public function getRoleBadgeAttribute()
    {
        $badges = [
            'admin' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-crown mr-1"></i>Admin</span>',
            'manager' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800"><i class="fas fa-user-tie mr-1"></i>Manager</span>',
            'staff' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><i class="fas fa-user-cog mr-1"></i>Staff</span>',
            'user' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><i class="fas fa-user mr-1"></i>User</span>',
        ];

        return $badges[$this->role] ?? $badges['user'];
    }

    /**
     * Get role name with proper casing
     */
    public function getRoleNameAttribute()
    {
        return ucfirst($this->role);
    }

    /**
     * Get role color for UI
     */
    public function getRoleColorAttribute()
    {
        $colors = [
            'admin' => 'red',
            'manager' => 'purple', 
            'staff' => 'blue',
            'user' => 'gray',
        ];

        return $colors[$this->role] ?? 'gray';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            return $this->hasRole($roles);
        }

        return in_array($this->role, $roles);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    /**
     * Check if user is manager
     */
    public function isManager()
    {
        return $this->hasRole(self::ROLE_MANAGER);
    }

    /**
     * Check if user is staff
     */
    public function isStaff()
    {
        return $this->hasRole(self::ROLE_STAFF);
    }

    /**
     * Check if user is regular user
     */
    public function isUser()
    {
        return $this->hasRole(self::ROLE_USER);
    }

    /**
     * Check if user has admin or manager role
     */
    public function isAdminOrManager()
    {
        return $this->hasAnyRole([self::ROLE_ADMIN, self::ROLE_MANAGER]);
    }

    /**
     * Check if user has management privileges (admin, manager, staff)
     */
    public function hasManagementAccess()
    {
        return $this->hasAnyRole([self::ROLE_ADMIN, self::ROLE_MANAGER, self::ROLE_STAFF]);
    }

    /**
     * Get all available roles
     */
    public static function getAllRoles()
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_MANAGER => 'Manager', 
            self::ROLE_STAFF => 'Staff',
            self::ROLE_USER => 'User',
        ];
    }

    /**
     * Get role permissions description
     */
    public static function getRolePermissions()
    {
        return [
            self::ROLE_ADMIN => [
                'desc' => 'Full system access',
                'permissions' => [
                    'Manage all users',
                    'Manage all inventory',
                    'Manage all transactions', 
                    'View all reports',
                    'System configuration',
                ]
            ],
            self::ROLE_MANAGER => [
                'desc' => 'Management access',
                'permissions' => [
                    'Manage staff and users',
                    'Manage inventory',
                    'Manage transactions',
                    'View reports',
                ]
            ],
            self::ROLE_STAFF => [
                'desc' => 'Operational access', 
                'permissions' => [
                    'Manage inventory',
                    'Process transactions',
                    'View basic reports',
                ]
            ],
            self::ROLE_USER => [
                'desc' => 'Limited access',
                'permissions' => [
                    'View inventory',
                    'Create transactions',
                    'View own transactions',
                ]
            ],
        ];
    }

    /**
     * Scope: Filter by role
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope: Get admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    /**
     * Scope: Get management users (admin, manager, staff)
     */
    public function scopeManagement($query)
    {
        return $query->whereIn('role', [self::ROLE_ADMIN, self::ROLE_MANAGER, self::ROLE_STAFF]);
    }

    /**
     * Scope: Get regular users only
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('role', self::ROLE_USER);
    }
}