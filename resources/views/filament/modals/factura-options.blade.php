<div class="p-4">
    <div class="grid grid-cols-2 gap-4">
        <!-- Imprimir Button -->
        <a href="{{ route('venta.factura.print', $venta->id) }}"
           target="_blank"
           class="flex flex-col items-center justify-center p-6 bg-success-50 dark:bg-success-900/20 rounded-lg hover:bg-success-100 dark:hover:bg-success-900/30 transition-colors border-2 border-success-500">
            <svg class="w-16 h-16 text-success-600 dark:text-success-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <span class="text-lg font-semibold text-success-700 dark:text-success-300">Imprimir</span>
            <span class="text-sm text-success-600 dark:text-success-400 mt-1">Abrir para imprimir</span>
        </a>

        <!-- Descargar PDF Button -->
        <a href="{{ route('venta.factura.download', $venta->id) }}"
           class="flex flex-col items-center justify-center p-6 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors border-2 border-primary-500">
            <svg class="w-16 h-16 text-primary-600 dark:text-primary-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="text-lg font-semibold text-primary-700 dark:text-primary-300">Descargar PDF</span>
            <span class="text-sm text-primary-600 dark:text-primary-400 mt-1">Guardar en el equipo</span>
        </a>
    </div>
</div>
