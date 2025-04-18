<?php

use App\Http\Middleware\CheckApiToken;
use App\Http\Middleware\RestrictIpMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\MasterAdminMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\Cors;
use App\Http\Middleware\ForceJsonResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->append(MasterAdminMiddleware::class);
        $middleware->alias(['role' => RoleMiddleware::class, 'cors' => Cors::class, 'forceJsonResponse' => ForceJsonResponse::class, 'check_api_token' => CheckApiToken::class, 'restrict.ip' => RestrictIpMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
