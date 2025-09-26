<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Card Proveedores -->
        <div class="p-6 bg-white shadow rounded-2xl hover:shadow-lg transition cursor-pointer">
            <div class="flex flex-col items-center">
                <x-heroicon-o-truck class="w-10 h-10 text-blue-600"/>
                <h3 class="mt-4 text-lg font-semibold">Proveedores</h3>
                <p class="text-sm text-gray-500">Gestiona los proveedores de la empresa</p>
            </div>
        </div>

        <!-- Card Clientes -->
        <div class="p-6 bg-white shadow rounded-2xl hover:shadow-lg transition cursor-pointer">
            <div class="flex flex-col items-center">
                <x-heroicon-o-users class="w-10 h-10 text-green-600"/>
                <h3 class="mt-4 text-lg font-semibold">Clientes</h3>
                <p class="text-sm text-gray-500">Administra tus clientes y contactos</p>
            </div>
        </div>

        <!-- Card Materiales -->
        <div class="p-6 bg-white shadow rounded-2xl hover:shadow-lg transition cursor-pointer">
            <div class="flex flex-col items-center">
                <x-heroicon-o-archive-box class="w-10 h-10 text-purple-600"/>
                <h3 class="mt-4 text-lg font-semibold">Materiales</h3>
                <p class="text-sm text-gray-500">Controla el inventario de materiales</p>
            </div>
        </div>

    </div>
</x-filament::page>
