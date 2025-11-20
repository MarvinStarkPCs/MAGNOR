<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-lg bg-warning-50 p-4 text-warning-800 dark:bg-warning-900/50 dark:text-warning-300">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium">Gestión de Precios Diarios</h3>
                    <div class="mt-2 text-sm">
                        <p>Actualiza aquí los precios de compra y venta de cada material según el mercado del día. Estos precios se utilizarán automáticamente al registrar nuevas compras.</p>
                    </div>
                </div>
            </div>
        </div>

        <form wire:submit="actualizarPrecios">
            {{ $this->form }}

            <div class="mt-6 flex justify-end gap-3">
                @foreach ($this->getFormActions() as $action)
                    {{ $action }}
                @endforeach
            </div>
        </form>
    </div>
</x-filament-panels::page>
