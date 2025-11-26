<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class CheckPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:permissions {--log : Log results to laravel.log}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica todos los permisos de archivos y carpetas críticos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando permisos de archivos y carpetas...');
        $this->newLine();

        $results = [];
        $hasErrors = false;

        // Directorios críticos
        $criticalDirs = [
            'storage' => storage_path(),
            'storage/app' => storage_path('app'),
            'storage/framework' => storage_path('framework'),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/sessions' => storage_path('framework/sessions'),
            'storage/framework/views' => storage_path('framework/views'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
            'public' => public_path(),
        ];

        foreach ($criticalDirs as $name => $path) {
            $exists = File::exists($path);
            $isWritable = $exists && is_writable($path);
            $isReadable = $exists && is_readable($path);
            $permissions = $exists ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';
            $owner = $exists ? posix_getpwuid(fileowner($path)) : null;

            $status = $exists ? ($isWritable && $isReadable ? '✅' : '❌') : '⚠️';

            if (!$exists || !$isWritable || !$isReadable) {
                $hasErrors = true;
            }

            $result = [
                'path' => $name,
                'full_path' => $path,
                'exists' => $exists,
                'writable' => $isWritable,
                'readable' => $isReadable,
                'permissions' => $permissions,
                'owner' => $owner['name'] ?? 'N/A',
                'status' => $status,
            ];

            $results[] = $result;

            // Mostrar en consola
            $this->line(sprintf(
                '%s %s - Permisos: %s | Owner: %s | W:%s R:%s',
                $status,
                $name,
                $permissions,
                $owner['name'] ?? 'N/A',
                $isWritable ? 'Sí' : 'No',
                $isReadable ? 'Sí' : 'No'
            ));
        }

        $this->newLine();

        // Verificar archivos críticos
        $criticalFiles = [
            '.env' => base_path('.env'),
            'storage/logs/laravel.log' => storage_path('logs/laravel.log'),
        ];

        $this->info('📄 Verificando archivos críticos...');
        $this->newLine();

        foreach ($criticalFiles as $name => $path) {
            $exists = File::exists($path);
            $isWritable = $exists && is_writable($path);
            $isReadable = $exists && is_readable($path);
            $permissions = $exists ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';

            $status = $exists ? ($isWritable && $isReadable ? '✅' : '❌') : '⚠️';

            if (!$exists || !$isWritable || !$isReadable) {
                $hasErrors = true;
            }

            $result = [
                'path' => $name,
                'full_path' => $path,
                'exists' => $exists,
                'writable' => $isWritable,
                'readable' => $isReadable,
                'permissions' => $permissions,
                'status' => $status,
            ];

            $results[] = $result;

            $this->line(sprintf(
                '%s %s - Permisos: %s | W:%s R:%s',
                $status,
                $name,
                $permissions,
                $isWritable ? 'Sí' : 'No',
                $isReadable ? 'Sí' : 'No'
            ));
        }

        $this->newLine();

        // Información del sistema
        $this->info('💻 Información del Sistema:');
        $this->line('PHP User: ' . (function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user()));
        $this->line('PHP Version: ' . PHP_VERSION);
        $this->line('Laravel Version: ' . app()->version());
        $this->line('Environment: ' . app()->environment());

        $this->newLine();

        // Log si se solicita
        if ($this->option('log')) {
            Log::info('🔍 VERIFICACIÓN DE PERMISOS COMPLETA', [
                'results' => $results,
                'has_errors' => $hasErrors,
                'php_user' => function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user(),
                'timestamp' => now()->toDateTimeString(),
            ]);
            $this->info('✅ Resultados guardados en storage/logs/laravel.log');
        }

        // Resumen
        if ($hasErrors) {
            $this->error('❌ Se encontraron problemas de permisos. Revisa los elementos marcados con ❌ o ⚠️');
            $this->newLine();
            $this->warn('💡 Solución sugerida:');
            $this->line('chmod -R 775 storage bootstrap/cache');
            $this->line('chown -R www-data:www-data storage bootstrap/cache');
            return Command::FAILURE;
        } else {
            $this->success('✅ Todos los permisos están correctos');
            return Command::SUCCESS;
        }
    }
}
