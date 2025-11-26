<x-filament-panels::page>
    <div class="space-y-6">

        <!-- Indicadores en Grid de 4 columnas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Proveedores -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Proveedores</p>
                        <p class="text-3xl font-bold mt-2" style="color: #146e39">12</p>
                        <p class="text-xs text-gray-500 mt-1">↑ 8%</p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: rgba(20, 110, 57, 0.1)">
                        <svg class="w-6 h-6" style="color: #146e39" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Clientes -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Clientes</p>
                        <p class="text-3xl font-bold mt-2" style="color: #276691">30</p>
                        <p class="text-xs text-gray-500 mt-1">↑ 12%</p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: rgba(39, 102, 145, 0.1)">
                        <svg class="w-6 h-6" style="color: #276691" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Materiales -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Materiales</p>
                        <p class="text-3xl font-bold mt-2" style="color: #f78921">50</p>
                        <p class="text-xs text-gray-500 mt-1">Tipos</p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: rgba(247, 137, 33, 0.1)">
                        <svg class="w-6 h-6" style="color: #f78921" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Ventas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ventas</p>
                        <p class="text-3xl font-bold mt-2" style="color: #cc2128">$2.5M</p>
                        <p class="text-xs text-gray-500 mt-1">↑ 15%</p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: rgba(204, 33, 40, 0.1)">
                        <svg class="w-6 h-6" style="color: #cc2128" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid Principal de Gráficos - 2 columnas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Gráfico 1: Ventas vs Compras -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ventas vs Compras</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Últimos 6 meses</p>
                </div>
                <div class="p-6">
                    <div class="w-full" style="height: 320px;">
                        <canvas id="ventasComprasChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico 2: Compras por Material -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Compras por Material</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kilogramos este mes</p>
                </div>
                <div class="p-6">
                    <div class="w-full" style="height: 320px;">
                        <canvas id="materialesBarChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico 3: Distribución de Stock -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribución de Stock</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Inventario actual</p>
                </div>
                <div class="p-6">
                    <div class="w-full flex justify-center" style="height: 320px;">
                        <canvas id="stockDoughnutChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico 4: Tendencia Semanal -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tendencia Semanal</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Actividad diaria</p>
                </div>
                <div class="p-6">
                    <div class="w-full" style="height: 320px;">
                        <canvas id="tendenciaChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const colors = {
                green: '#146e39',
                blue: '#276691',
                orange: '#f78921',
                red: '#cc2128',
                lime: '#52c41a',
                teal: '#13c2c2'
            };

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            font: { size: 12, family: 'Inter, sans-serif' },
                            usePointStyle: true,
                            padding: 12
                        }
                    }
                }
            };

            // 1. Ventas vs Compras
            const ventasComprasCtx = document.getElementById('ventasComprasChart');
            if (ventasComprasCtx) {
                new Chart(ventasComprasCtx, {
                    type: 'line',
                    data: {
                        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                        datasets: [
                            {
                                label: 'Ventas',
                                data: [1500000, 2200000, 1800000, 2800000, 3200000, 2500000],
                                borderColor: colors.green,
                                backgroundColor: 'rgba(20, 110, 57, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: colors.green,
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            },
                            {
                                label: 'Compras',
                                data: [1200000, 1800000, 1500000, 2100000, 2500000, 2000000],
                                borderColor: colors.blue,
                                backgroundColor: 'rgba(39, 102, 145, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: colors.blue,
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        plugins: {
                            ...commonOptions.plugins,
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 10,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': $' + context.parsed.y.toLocaleString('es-CO');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + (value / 1000000).toFixed(1) + 'M';
                                    },
                                    font: { size: 11 }
                                },
                                grid: { color: 'rgba(0, 0, 0, 0.05)' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 } }
                            }
                        }
                    }
                });
            }

            // 2. Barras - Materiales
            const materialesBarCtx = document.getElementById('materialesBarChart');
            if (materialesBarCtx) {
                new Chart(materialesBarCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Cobre', 'Aluminio', 'Acero', 'Bronce', 'Hierro'],
                        datasets: [{
                            label: 'Kilogramos',
                            data: [1200, 1900, 800, 650, 1500],
                            backgroundColor: [colors.green, colors.blue, colors.orange, colors.red, colors.lime],
                            borderWidth: 0,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        ...commonOptions,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 10,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y.toLocaleString() + ' kg';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString() + ' kg';
                                    },
                                    font: { size: 11 }
                                },
                                grid: { color: 'rgba(0, 0, 0, 0.05)' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 } }
                            }
                        }
                    }
                });
            }

            // 3. Dona - Stock
            const stockDoughnutCtx = document.getElementById('stockDoughnutChart');
            if (stockDoughnutCtx) {
                new Chart(stockDoughnutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Cobre', 'Aluminio', 'Acero', 'Bronce', 'Hierro', 'Otros'],
                        datasets: [{
                            data: [850, 1200, 600, 450, 900, 300],
                            backgroundColor: [colors.green, colors.blue, colors.orange, colors.red, colors.lime, colors.teal],
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    font: { size: 11, family: 'Inter, sans-serif' },
                                    usePointStyle: true,
                                    padding: 10,
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.labels.map((label, i) => {
                                                const value = data.datasets[0].data[i];
                                                return {
                                                    text: `${label}: ${value} kg`,
                                                    fillStyle: data.datasets[0].backgroundColor[i],
                                                    hidden: false,
                                                    index: i
                                                };
                                            });
                                        }
                                        return [];
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 10,
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        return `${context.label}: ${context.parsed} kg (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 4. Tendencia Semanal
            const tendenciaCtx = document.getElementById('tendenciaChart');
            if (tendenciaCtx) {
                new Chart(tendenciaCtx, {
                    type: 'line',
                    data: {
                        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                        datasets: [
                            {
                                label: 'Proveedores',
                                data: [5, 8, 6, 7, 4, 10, 12],
                                borderColor: colors.green,
                                backgroundColor: 'rgba(20, 110, 57, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Clientes',
                                data: [12, 15, 10, 18, 20, 25, 30],
                                borderColor: colors.blue,
                                backgroundColor: 'rgba(39, 102, 145, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Materiales',
                                data: [10, 15, 20, 12, 18, 22, 50],
                                borderColor: colors.orange,
                                backgroundColor: 'rgba(247, 137, 33, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        plugins: {
                            ...commonOptions.plugins,
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 10
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { font: { size: 11 } },
                                grid: { color: 'rgba(0, 0, 0, 0.05)' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 } }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-filament-panels::page>
