<?php

use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\AuthRumah;
use App\Http\Middleware\CheckLayananApproval;
use \App\Http\Middleware\CheckDataWarga;
use App\Http\Middleware\GuestRumah;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {

        // Global middleware
        $middleware->append(TrustProxies::class);

        // Alias middleware
        $middleware->alias([
            'auth.rumah' => AuthRumah::class,
            'guest.rumah' => GuestRumah::class,
            'check.approval' => CheckLayananApproval::class,
            'check.data' => CheckDataWarga::class,
        ]);

    })

    ->withExceptions(function ($exceptions) {
        //
    })

    ->create();
