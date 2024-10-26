<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'error'   => class_basename(AuthorizationException::class),
                    'message' => 'This action us unauthorized',
                ], 403);
            } elseif ($request->is('api/*')) {
                return response()->json([
                    'error'   => $e->getMessage(),
                    'message' => 'Record not found.'
                ], $e->getStatusCode());
            }
        });
    })->create();
