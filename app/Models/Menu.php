<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    // =========================
    // TABLE
    // =========================
    protected $table = 'menus';
    protected $appends = ['level'];

    // =========================
    // MASS ASSIGNMENT
    // =========================
    protected $fillable = [
        'name',
        'route',
        'icon',
        'parent_id',
        'order',
        'is_active',
    ];

    // =========================
    // CASTING
    // =========================
    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
        'parent_id' => 'integer',
    ];

    // =========================
    // RELATIONSHIP
    // =========================

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order');
    }

    /**
     * 🔥 recursive + permission (FIX N+1 & akses)
     */
    public function childrenRecursive()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('order')
            ->with(['childrenRecursive', 'permissions']);
    }

    /**
     * 🔥 pivot menu ↔ permission
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'menu_permissions',
            'menu_id',
            'permission_id'
        )->withTimestamps(); // 🔥 optional tapi bagus
    }

    /**
     * optional (kalau mau akses pivot langsung)
     */
    public function menuPermissions()
    {
        return $this->hasMany(MenuPermission::class);
    }

    // =========================
    // SCOPES
    // =========================

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParent(Builder $query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered(Builder $query)
    {
        return $query->orderBy('order');
    }

    // =========================
    // CORE MENU TREE (CACHE PER USER)
    // =========================

    public static function getMenuTree()
    {
        $user = Auth::user();
        $key = 'menus_user_' . ($user?->id ?? 'guest');

        return Cache::remember($key, now()->addHour(), function () {

            return self::with(['permissions', 'childrenActive'])
                ->active()
                ->parent()
                ->ordered()
                ->get()
                ->filter(fn($menu) => $menu->canAccess())
                ->values();
        });
    }

    // =========================
    // ACCESS CONTROL
    // =========================

    public function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // super admin bypass
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return true;
        }

        if (!$this->relationLoaded('permissions')) {
            $this->load('permissions');
        }

        if ($this->permissions->isEmpty()) {
            return true;
        }

        return $this->permissions->contains(function ($permission) use ($user) {
            return method_exists($user, 'can') && $user->can($permission->name);
        });
    }

    // =========================
    // HELPERS
    // =========================

    /**
     * apakah punya child
     */
    public function hasChildren(): bool
    {
        return $this->relationLoaded('childrenRecursive')
            ? $this->childrenRecursive->isNotEmpty()
            : $this->children()->exists();
    }

    /**
     * URL dari route
     */
    public function getUrlAttribute(): string
    {
        try {
            return $this->route ? route($this->route) : 'javascript:;';
        } catch (\Throwable $e) {
            return 'javascript:;';
        }
    }

    /**
     * menu aktif
     */
    public function isActive(): bool
    {
        return $this->route && request()->routeIs($this->route . '*');
    }

    /**
     * cek child aktif
     */
    public function hasActiveChild(): bool
    {
        if (!$this->relationLoaded('childrenRecursive')) {
            return false;
        }

        return $this->childrenRecursive
            ->filter(fn($child) => $child->canAccess())
            ->contains(fn($child) => $child->isActive());
    }

    /**
     * fallback icon
     */
    public function getIconAttribute(?string $value): string
    {
        return $value ?: 'bi bi-circle';
    }

    /**
     * 🔥 helper untuk blade (biar bersih)
     */
    public function getParentNameAttribute(): string
    {
        return $this->parent->name ?? 'Main Menu';
    }

    /**
     * 🔥 label status
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    public function childrenActive()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->with(['childrenActive', 'permissions']);
    }

    public function getLevelAttribute()
    {
        $level = 0;
        $parent = $this->parent;

        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }

        return $level;
    }

    public static function clearMenuCache()
    {
        Cache::flush();
    }
}
