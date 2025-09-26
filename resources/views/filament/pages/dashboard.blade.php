<x-filament::page>

    <!-- Indicadores -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white shadow rounded-2xl p-6 text-center">
            <h3 class="text-gray-500">Proveedores Hoy</h3>
            <p class="text-3xl font-bold text-blue-600">12</p>
        </div>
        <div class="bg-white shadow rounded-2xl p-6 text-center">
            <h3 class="text-gray-500">Clientes Hoy</h3>
            <p class="text-3xl font-bold text-green-600">30</p>
        </div>
        <div class="bg-white shadow rounded-2xl p-6 text-center">
            <h3 class="text-gray-500">Materiales Recibidos</h3>
            <p class="text-3xl font-bold text-purple-600">50</p>
        </div>
    </div>

    <!-- Gráficas comparativas diarias -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Gráfica de barras diaria -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">Proveedores vs Clientes - Últimos 7 días</h3>
            <div id="barChart"></div>
        </div>

        <!-- Gráfica de líneas diaria -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">Materiales Recibidos - Últimos 7 días</h3>
            <div id="lineChart"></div>
        </div>
    </div>

    <!-- Scripts ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Gráfica de barras diaria
        var barOptions = {
            chart: { type: 'bar', height: 300 },
            series: [
                { name: 'Proveedores', data: [5, 8, 6, 7, 4, 10, 12] },
                { name: 'Clientes', data: [12, 15, 10, 18, 20, 25, 30] }
            ],
            xaxis: { categories: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] },
            colors: ['#3B82F6', '#10B981']
        };
        new ApexCharts(document.querySelector("#barChart"), barOptions).render();

        // Gráfica de líneas diaria
        var lineOptions = {
            chart: { type: 'line', height: 300 },
            series: [{ name: 'Materiales', data: [10, 15, 20, 12, 18, 22, 50] }],
            xaxis: { categories: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] },
            stroke: { curve: 'smooth' },
            colors: ['#8B5CF6']
        };
        new ApexCharts(document.querySelector("#lineChart"), lineOptions).render();
    </script>
</x-filament::page>
