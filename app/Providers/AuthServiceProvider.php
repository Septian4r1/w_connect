<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * ======================================================
     * POLICY MAPPING
     * ======================================================
     */
    protected $policies = [
        \App\Models\Warga::class => \App\Policies\WargaPolicy::class,
        // Tambahkan model lain jika perlu
    ];

    /**
     * ======================================================
     * BOOT
     * ======================================================
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // ✅ Super admin sudah otomatis punya semua permission di DB,
        // jadi tidak perlu Gate::before lagi.
    }
}
