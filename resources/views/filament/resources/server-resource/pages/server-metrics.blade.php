<x-filament-panels::page>
    <x-filament-panels::form wire:submit="submit">
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button 
                type="submit" 
                wire:loading.attr="disabled"
                color="primary">
                Show Graph
            </x-filament::button>
        </div>
    </x-filament-panels::form>

    @if($isChartVisible)
    <div class="p-6 bg-white rounded-lg shadow mt-6 dark:bg-gray-800">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">CPU Load</h3>
        <div class="h-80" x-data="{
            init() {
                const isDarkMode = document.querySelector('html').classList.contains('dark');
                const textColor = isDarkMode ? '#fff' : '#373d3f';
                const minColor = isDarkMode ? '#10b981' : '#00E396';
                const avgColor = isDarkMode ? '#f59e0b' : '#FEB019';
                const maxColor = isDarkMode ? '#ef4444' : '#FF4560';

                const chart = new ApexCharts(this.$el, {
                    chart: {
                        type: 'line',
                        height: 350,
                        foreColor: textColor,
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                selection: true,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: true,
                                reset: true,
                            },
                        },
                    },
                    colors: ['#008FFB'],
                    series: [{
                        name: 'CPU Load',
                        data: {{ json_encode($chartData['pointCpu']) }}
                    }],
                    xaxis: {
                        categories: {{ json_encode($chartData['dates']) }},
                        labels: {
                            rotate: -45,
                            rotateAlways: true,
                            style: {
                                fontSize: '12px',
                            }
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2,
                    },
                    legend: {
                        position: 'top'
                    },
                    markers: {
                        size: 4,
                        hover: {
                            size: 6
                        }
                    },
                    grid: {
                        borderColor: isDarkMode ? '#404040' : '#e7e7e7',
                        row: {
                            colors: [isDarkMode ? '#333' : '#f3f3f3', 'transparent'],
                            opacity: 0.5
                        },
                    },
                    yaxis: {
                        title: {
                            text: 'CPU Load (%)'
                        },
                        labels: {
                            formatter: function (val) {
                                return val.toFixed(2);
                            }
                        }
                    },
                    annotations: {
                        yaxis: [
                            {
                                y: Math.min(...{{ json_encode($chartData['pointCpu']) }}),
                                borderColor: minColor,
                                label: {
                                    borderColor: minColor,
                                    style: {
                                        color: '#fff',
                                        background: minColor
                                    },
                                    text: `Min: ${Math.min(...{{ json_encode($chartData['pointCpu']) }}).toFixed(2)}%`
                                }
                            },
                            {
                                y: {{ json_encode($chartData['pointCpu']) }}.reduce((a, b) => a + b, 0) / {{ json_encode($chartData['pointCpu']) }}.length,
                                borderColor: avgColor,
                                label: {
                                    borderColor: avgColor,
                                    style: {
                                        color: '#fff',
                                        background: avgColor
                                    },
                                    text: `Avg: ${({{ json_encode($chartData['pointCpu']) }}.reduce((a, b) => a + b, 0) / {{ json_encode($chartData['pointCpu']) }}.length).toFixed(2)}%`
                                }
                            },
                            {
                                y: Math.max(...{{ json_encode($chartData['pointCpu']) }}),
                                borderColor: maxColor,
                                label: {
                                    borderColor: maxColor,
                                    style: {
                                        color: '#fff',
                                        background: maxColor
                                    },
                                    text: `Max: ${Math.max(...{{ json_encode($chartData['pointCpu']) }}).toFixed(2)}%`
                                }
                            }
                        ]
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toFixed(2) + '%';
                            }
                        }
                    },
                    theme: {
                        mode: isDarkMode ? 'dark' : 'light'
                    }
                });
                chart.render();
            }
        }"></div>
    </div>
    @endif
</x-filament-panels::page>