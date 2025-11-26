<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogHttpResponses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log antes de procesar el request
        Log::info('📥 REQUEST ENTRANTE', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'session_id' => session()->getId(),
            'is_authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Procesar el request
        $response = $next($request);

        // Log después de procesar el request
        $statusCode = $response->getStatusCode();
        $logLevel = $this->getLogLevel($statusCode);

        Log::$logLevel('📤 RESPUESTA HTTP', [
            'status_code' => $statusCode,
            'status_text' => Response::$statusTexts[$statusCode] ?? 'Unknown',
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'session_id' => session()->getId(),
            'is_authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'headers' => $response->headers->all(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Log especial para errores 403
        if ($statusCode === 403) {
            Log::error('🚫 ERROR 403 FORBIDDEN DETECTADO', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email ?? 'N/A',
                'ip' => $request->ip(),
                'session_id' => session()->getId(),
                'session_data' => session()->all(),
                'request_headers' => $request->headers->all(),
                'response_headers' => $response->headers->all(),
                'permissions' => auth()->check() ? auth()->user()->getAllPermissions()->pluck('name') : [],
                'roles' => auth()->check() ? auth()->user()->getRoleNames() : [],
                'storage_permissions' => [
                    'storage_writable' => is_writable(storage_path()),
                    'storage_sessions_writable' => is_writable(storage_path('framework/sessions')),
                    'storage_logs_writable' => is_writable(storage_path('logs')),
                ],
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        // Log especial para redirects
        if ($statusCode >= 300 && $statusCode < 400) {
            Log::info('🔀 REDIRECT DETECTADO', [
                'from' => $request->fullUrl(),
                'to' => $response->headers->get('Location'),
                'status_code' => $statusCode,
                'session_id' => session()->getId(),
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        return $response;
    }

    /**
     * Determina el nivel de log según el código de estado
     */
    private function getLogLevel(int $statusCode): string
    {
        if ($statusCode >= 500) {
            return 'error';
        } elseif ($statusCode >= 400) {
            return 'warning';
        } elseif ($statusCode >= 300) {
            return 'info';
        }
        return 'info';
    }
}
