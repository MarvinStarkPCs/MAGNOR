<x-filament-widgets::widget>
    <x-filament::section>
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Ventas vs Compras</h2>
            <div class="w-full" style="height: 400px;">
                <canvas id="ventasComprasChart"></canvas>
            </div>
        </div>
    </x-filament::section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('ventasComprasChart');

            if (ctx) {
                const data = @json($this->getData());

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'Ventas',
                                data: data.ventas,
                                borderColor: '#146e39',
                                backgroundColor: 'rgba(20, 110, 57, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Compras',
                                data: data.compras,
                                borderColor: '#276691',
                                backgroundColor: 'rgba(39, 102, 145, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-filament-widgets::widget>
