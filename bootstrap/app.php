<?php

use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\AuthRumah;
use App\Http\Middleware\CheckLayananApproval;
use \App\Http\Middleware\CheckDataWarga;
use App\Http\Middleware\CheckDeviceLogin;
use App\Http\Middleware\CheckRWExists;
use App\Http\Middleware\EnsureAjaxRequest;
use App\Http\Middleware\GuestRumah;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\RateLimiter;
use App\Http\Middleware\RedirectIfManagementLoggedIn;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
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

            // Login Security
            'login.limit' => RateLimiter::class,

            // Device & Security
            'check.device' => CheckDeviceLogin::class,
            'prevent.back' => PreventBackHistory::class,
            'check_rw' => CheckRWExists::class,

            // Management

            'redirect.management' => RedirectIfManagementLoggedIn::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,

            // Safety Ajax
            'ajax' => EnsureAjaxRequest::class,
        ]);
    })

    ->withExceptions(function ($exceptions) {

        $exceptions->render(function (UnauthorizedException $e, $request) {

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses.'
                ], 403);
            }

            return redirect()->back()->with(
                'error',
                'Anda tidak memiliki akses ke halaman ini.'
            );
        });
    })

    ->create();
