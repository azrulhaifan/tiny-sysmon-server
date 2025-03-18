<x-filament-panels::page>
    <x-filament::section>
        <h2 class="text-xl font-bold tracking-tight">
            Metrics for {{ $this->record->name }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- CPU Chart -->
            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4">CPU Load (%)</h3>
                <div id="cpu-chart" style="height: 300px;"></div>
            </div>

            <!-- Memory Chart -->
            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4">Memory Usage (%)</h3>
                <div id="memory-chart" style="height: 300px;"></div>
            </div>

            <!-- Swap Chart -->
            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4">Swap Usage (%)</h3>
                <div id="swap-chart" style="height: 300px;"></div>
            </div>

            <!-- Disk IO Chart -->
            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4">Disk Operations/Sec</h3>
                <div id="disk-chart" style="height: 300px;"></div>
            </div>
        </div>
    </x-filament::section>

    <!-- Include ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timestamps = @json($this->timestamps);
            const cpuData = @json($this->cpuData);
            const memoryData = @json($this->memoryData);
            const swapData = @json($this->swapData);
            const diskIoData = @json($this->diskIoData);

            // Calculate min, avg, max for each dataset
            const calculateStats = (data) => {
                if (!data.length) return { min: 0, avg: 0, max: 0 };
                
                const min = Math.min(...data);
                const max = Math.max(...data);
                const sum = data.reduce((a, b) => a + b, 0);
                const avg = sum / data.length;
                
                return {
                    min: parseFloat(min.toFixed(2)),
                    avg: parseFloat(avg.toFixed(2)),
                    max: parseFloat(max.toFixed(2))
                };
            };

            const cpuStats = calculateStats(cpuData);
            const memoryStats = calculateStats(memoryData);
            const swapStats = calculateStats(swapData);
            const diskIoStats = calculateStats(diskIoData);

            // Common chart options
            const commonOptions = {
                chart: {
                    type: 'line',
                    height: 300,
                    zoom: {
                        enabled: true
                    },
                    toolbar: {
                        show: true
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: timestamps,
                    labels: {
                        rotate: -45,
                        rotateAlways: true
                    }
                },
                tooltip: {
                    x: {
                        format: 'yyyy-MM-dd HH:mm:ss'
                    }
                },
                annotations: {
                    yaxis: []
                },
                legend: {
                    position: 'top'
                }
            };

            // CPU Chart
            new ApexCharts(document.querySelector("#cpu-chart"), {
                ...commonOptions,
                series: [{
                    name: 'CPU Load',
                    data: cpuData
                }],
                colors: ['#FF5733'],
                yaxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: 'Percentage (%)'
                    }
                },
                annotations: {
                    yaxis: [
                        {
                            y: cpuStats.min,
                            borderColor: '#00E396',
                            label: {
                                borderColor: '#00E396',
                                style: {
                                    color: '#fff',
                                    background: '#00E396'
                                },
                                text: `Min: ${cpuStats.min}%`
                            }
                        },
                        {
                            y: cpuStats.avg,
                            borderColor: '#FEB019',
                            label: {
                                borderColor: '#FEB019',
                                style: {
                                    color: '#fff',
                                    background: '#FEB019'
                                },
                                text: `Avg: ${cpuStats.avg}%`
                            }
                        },
                        {
                            y: cpuStats.max,
                            borderColor: '#FF4560',
                            label: {
                                borderColor: '#FF4560',
                                style: {
                                    color: '#fff',
                                    background: '#FF4560'
                                },
                                text: `Max: ${cpuStats.max}%`
                            }
                        }
                    ]
                }
            }).render();

            // Memory Chart
            new ApexCharts(document.querySelector("#memory-chart"), {
                ...commonOptions,
                series: [{
                    name: 'Memory Usage',
                    data: memoryData
                }],
                colors: ['#33A8FF'],
                yaxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: 'Percentage (%)'
                    }
                },
                annotations: {
                    yaxis: [
                        {
                            y: memoryStats.min,
                            borderColor: '#00E396',
                            label: {
                                borderColor: '#00E396',
                                style: {
                                    color: '#fff',
                                    background: '#00E396'
                                },
                                text: `Min: ${memoryStats.min}%`
                            }
                        },
                        {
                            y: memoryStats.avg,
                            borderColor: '#FEB019',
                            label: {
                                borderColor: '#FEB019',
                                style: {
                                    color: '#fff',
                                    background: '#FEB019'
                                },
                                text: `Avg: ${memoryStats.avg}%`
                            }
                        },
                        {
                            y: memoryStats.max,
                            borderColor: '#FF4560',
                            label: {
                                borderColor: '#FF4560',
                                style: {
                                    color: '#fff',
                                    background: '#FF4560'
                                },
                                text: `Max: ${memoryStats.max}%`
                            }
                        }
                    ]
                }
            }).render();

            // Swap Chart
            new ApexCharts(document.querySelector("#swap-chart"), {
                ...commonOptions,
                series: [{
                    name: 'Swap Usage',
                    data: swapData
                }],
                colors: ['#33FF57'],
                yaxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: 'Percentage (%)'
                    }
                },
                annotations: {
                    yaxis: [
                        {
                            y: swapStats.min,
                            borderColor: '#00E396',
                            label: {
                                borderColor: '#00E396',
                                style: {
                                    color: '#fff',
                                    background: '#00E396'
                                },
                                text: `Min: ${swapStats.min}%`
                            }
                        },
                        {
                            y: swapStats.avg,
                            borderColor: '#FEB019',
                            label: {
                                borderColor: '#FEB019',
                                style: {
                                    color: '#fff',
                                    background: '#FEB019'
                                },
                                text: `Avg: ${swapStats.avg}%`
                            }
                        },
                        {
                            y: swapStats.max,
                            borderColor: '#FF4560',
                            label: {
                                borderColor: '#FF4560',
                                style: {
                                    color: '#fff',
                                    background: '#FF4560'
                                },
                                text: `Max: ${swapStats.max}%`
                            }
                        }
                    ]
                }
            }).render();

            // Disk IO Chart
            new ApexCharts(document.querySelector("#disk-chart"), {
                ...commonOptions,
                series: [{
                    name: 'Disk Operations/Sec',
                    data: diskIoData
                }],
                colors: ['#A833FF'],
                yaxis: {
                    title: {
                        text: 'Operations/Sec'
                    }
                },
                annotations: {
                    yaxis: [
                        {
                            y: diskIoStats.min,
                            borderColor: '#00E396',
                            label: {
                                borderColor: '#00E396',
                                style: {
                                    color: '#fff',
                                    background: '#00E396'
                                },
                                text: `Min: ${diskIoStats.min}`
                            }
                        },
                        {
                            y: diskIoStats.avg,
                            borderColor: '#FEB019',
                            label: {
                                borderColor: '#FEB019',
                                style: {
                                    color: '#fff',
                                    background: '#FEB019'
                                },
                                text: `Avg: ${diskIoStats.avg}`
                            }
                        },
                        {
                            y: diskIoStats.max,
                            borderColor: '#FF4560',
                            label: {
                                borderColor: '#FF4560',
                                style: {
                                    color: '#fff',
                                    background: '#FF4560'
                                },
                                text: `Max: ${diskIoStats.max}`
                            }
                        }
                    ]
                }
            }).render();
        });
    </script>
</x-filament-panels::page>