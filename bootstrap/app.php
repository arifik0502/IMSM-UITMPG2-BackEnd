<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
        ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);

        // Trust the forwarding headers from tunnels/proxies (ngrok, etc.)
        // so Laravel knows the original request was HTTPS and generates
        // https:// URLs for assets, routes, and forms accordingly.
        $middleware->trustProxies(at: '*');
    })
    
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
