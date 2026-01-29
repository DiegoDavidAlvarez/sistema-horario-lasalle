<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Capturar la excepción de Spatie Permission
        // Capturar excepciones de autorización (403)
        $exceptions->render(function (\Throwable $e, $request) {
            if ($e instanceof \Spatie\Permission\Exceptions\UnauthorizedException || 
                $e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
                
                return redirect()->route('dashboard')->with('error', 'Acceso denegado: No tienes permisos para acceder a esta sección.');
            }
        });
    })->create();
