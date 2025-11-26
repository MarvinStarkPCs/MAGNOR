<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Session\Events\SessionStarted;

class LoginLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Log cuando se intenta login
        Event::listen(Attempting::class, function ($event) {
            Log::info('🔵 INTENTO DE LOGIN', [
                'guard' => $event->guard,
                'credentials_email' => $event->credentials['email'] ?? 'N/A',
                'remember' => $event->remember,
                'timestamp' => now()->toDateTimeString(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        // Log cuando login es exitoso
        Event::listen(Login::class, function ($event) {
            Log::info('✅ LOGIN EXITOSO', [
                'user_id' => $event->user->id,
                'user_email' => $event->user->email,
                'guard' => $event->guard,
                'timestamp' => now()->toDateTimeString(),
                'ip' => request()->ip(),
                'session_id' => session()->getId(),
            ]);
        });

        // Log cuando el usuario está autenticado
        Event::listen(Authenticated::class, function ($event) {
            Log::info('🟢 USUARIO AUTENTICADO', [
                'user_id' => $event->user->id,
                'user_email' => $event->user->email,
                'guard' => $event->guard,
                'timestamp' => now()->toDateTimeString(),
            ]);
        });

        // Log cuando login falla
        Event::listen(Failed::class, function ($event) {
            Log::warning('❌ LOGIN FALLIDO', [
                'guard' => $event->guard,
                'credentials_email' => $event->credentials['email'] ?? 'N/A',
                'timestamp' => now()->toDateTimeString(),
                'ip' => request()->ip(),
            ]);
        });

        // Log cuando hay lockout (muchos intentos fallidos)
        Event::listen(Lockout::class, function ($event) {
            Log::error('🔒 CUENTA BLOQUEADA', [
                'request' => $event->request->all(),
                'timestamp' => now()->toDateTimeString(),
                'ip' => request()->ip(),
            ]);
        });

        // Log cuando se hace logout
        Event::listen(Logout::class, function ($event) {
            Log::info('🔴 LOGOUT', [
                'user_id' => $event->user->id ?? 'N/A',
                'user_email' => $event->user->email ?? 'N/A',
                'guard' => $event->guard,
                'timestamp' => now()->toDateTimeString(),
            ]);
        });

        // Log cuando se inicia una sesión
        Event::listen(SessionStarted::class, function ($event) {
            Log::info('🆕 SESIÓN INICIADA', [
                'session_id' => session()->getId(),
                'timestamp' => now()->toDateTimeString(),
                'ip' => request()->ip(),
            ]);
        });

        // Log adicional en cada request autenticado
        app('router')->matched(function () {
            if (auth()->check()) {
                Log::info('👤 REQUEST AUTENTICADO', [
                    'user_id' => auth()->id(),
                    'user_email' => auth()->user()->email,
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'session_id' => session()->getId(),
                    'timestamp' => now()->toDateTimeString(),
                ]);
            } else {
                Log::info('👻 REQUEST NO AUTENTICADO', [
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'session_id' => session()->getId(),
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }
        });
    }
}
