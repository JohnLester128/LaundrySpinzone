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
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin/*')) {
                return route('admin.login');
            }

            return route('customer.login');
        });

        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureAdminRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 419) {
                if ($request->is('admin/*') || $request->is('admin')) {
                    return redirect()->route('admin.login')
                        ->withInput($request->except('password', 'password_confirmation', '_token'))
                        ->with('error', 'Your session expired or your form was idle for too long. Please try logging in again.');
                }

                if ($request->is('customer/*') || $request->is('customer')) {
                    return redirect()->route('customer.login')
                        ->withInput($request->except('password', 'password_confirmation', '_token'))
                        ->with('error', 'Your session expired or your form was idle for too long. Please try logging in again.');
                }

                return redirect()->back()
                    ->withInput($request->except('password', 'password_confirmation', '_token'))
                    ->with('error', 'Your page expired. Please try again.');
            }
        });
    })->create();
