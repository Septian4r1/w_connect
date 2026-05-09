<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    protected $table = 'menu_permissions';

    protected $fillable = [
        'menu_id',
        'permission_id',
    ];

    /**
     * RELASI KE MENU
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * RELASI KE PERMISSION (Spatie)
     */
    public function permission()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Permission::class);
    }
}
