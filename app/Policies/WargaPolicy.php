<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warga;

class WargaPolicy
{
    /**
     * ======================================================
     * VIEW ANY (LIST)
     * ======================================================
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_warga');
    }

    /**
     * ======================================================
     * VIEW DETAIL
     * ======================================================
     */
    public function view(User $user, Warga $warga): bool
    {
        // Super admin bypass
        if ($user->hasRole('super_admin')) return true;

        $wilayah = $user->getWilayahIds();

        // Cek RT dulu
        if ($wilayah['rt_ids']->isNotEmpty()) {
            return $wilayah['rt_ids']->contains($warga->rt_id);
        }

        // Kalau RW
        if ($wilayah['rw_ids']->isNotEmpty()) {
            return $wilayah['rw_ids']->contains($warga->rw_id);
        }

        return false;
    }

    /**
     * ======================================================
     * CREATE
     * ======================================================
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_warga');
    }

    /**
     * ======================================================
     * UPDATE
     * ======================================================
     */
    public function update(User $user, Warga $warga): bool
    {
        if (!$user->hasPermissionTo('edit_warga')) return false;

        return $this->view($user, $warga); // 🔥 reuse logic
    }

    /**
     * ======================================================
     * DELETE
     * ======================================================
     */
    public function delete(User $user, Warga $warga): bool
    {
        if (!$user->hasPermissionTo('delete_warga')) return false;

        return $this->view($user, $warga);
    }
}
