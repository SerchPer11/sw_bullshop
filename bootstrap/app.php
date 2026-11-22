<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Sentry\Laravel\Integration;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            
        ]);

        $middleware->trustProxies(at: '*');

        $middleware->preventRequestForgery(except: [
            'stripe/webhook',
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        Integration::handles($exceptions);
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
        $exceptions->renderable(function (TooManyRequestsHttpException $e, Request $request) {
            if ($request->expectsJson() || ! $request->acceptsHtml()) {
                return null;
            }

            return Inertia::render('Error', [
                'status' => 429,
            ])->toResponse($request)->setStatusCode(429);
        });
        //capturar excepcion 403
        $exceptions->renderable(function (UnauthorizedException $e, Request $request){
            if ($request->expectsJson() || ! $request->acceptsHtml()) {
                return null;
            }

            return Inertia::render('Error', [
                'status' => 403,
            ])->toResponse($request)->setStatusCode(403);
        });

        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson() || ! $request->acceptsHtml()) {
                return null;
            }

            return Inertia::render('Error', [
                'status' => 404,
            ])->toResponse($request)->setStatusCode(404);
        });
    })->create();
