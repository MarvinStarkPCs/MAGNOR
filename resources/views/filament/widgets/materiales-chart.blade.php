<x-filament-widgets::widget>
    <x-filament::section>
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Stock de Materiales</h2>
            <div class="w-full flex justify-center" style="height: 400px;">
                <canvas id="materialesChart"></canvas>
            </div>
        </div>
    </x-filament::section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('materialesChart');

            if (ctx) {
                const data = @json($this->getData());

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: [
                                '#146e39',
                                '#276691',
                                '#f78921',
                                '#cc2128',
                                '#52c41a',
                                '#13c2c2'
                            ],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    font: {
                                        size: 13,
                                        weight: '500'
                                    },
                                    usePointStyle: true,
                                    padding: 15,
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
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return `${label}: ${value} kg (${percentage}%)`;
                                    }
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
