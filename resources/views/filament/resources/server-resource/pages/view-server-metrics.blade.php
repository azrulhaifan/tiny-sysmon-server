<x-filament-panels::page>
    <x-filament::section>
        <h2 class="text-xl font-bold tracking-tight">
            Metrics for {{ $this->record->name }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- CPU Chart -->
            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4 dark:text-gray-200">CPU Load (%)</h3>
                <div id="cpu-chart" style="height: 300px;"></div>
            </div>

            <!-- Memory Chart -->
            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4 dark:text-gray-200">Memory Usage (%)</h3>
                <div id="memory-chart" style="height: 300px;"></div>
            </div>

            <!-- Swap Chart -->
            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4 dark:text-gray-200">Swap Usage (%)</h3>
                <div id="swap-chart" style="height: 300px;"></div>
            </div>

            <!-- Disk IO Chart -->
            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4 dark:text-gray-200">Disk Operations/Sec</h3>
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

            // Detect dark mode
            const isDarkMode = document.documentElement.classList.contains('dark');

            // Set theme-specific colors
            const chartBackground = isDarkMode ? '#1e293b' : '#ffffff';
            const textColor = isDarkMode ? '#94a3b8' : '#334155';
            const gridColor = isDarkMode ? '#334155' : '#e2e8f0';

            // Theme-specific series colors
            const cpuColor = isDarkMode ? '#f87171' : '#FF5733';
            const memoryColor = isDarkMode ? '#60a5fa' : '#33A8FF';
            const swapColor = isDarkMode ? '#4ade80' : '#33FF57';
            const diskColor = isDarkMode ? '#c084fc' : '#A833FF';

            // Annotation colors
            const minColor = isDarkMode ? '#10b981' : '#00E396';
            const avgColor = isDarkMode ? '#f59e0b' : '#FEB019';
            const maxColor = isDarkMode ? '#ef4444' : '#FF4560';

            // Calculate min, avg, max for each dataset
            const calculateStats = (data) => {
                if (!data.length) return {
                    min: 0,
                    avg: 0,
                    max: 0
                };

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
                    },
                    background: chartBackground,
                    foreColor: textColor
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: timestamps,
                    labels: {
                        rotate: -45,
                        rotateAlways: true,
                        formatter: function(value) {
                            // Extract only time part (HH:MM:SS) from the timestamp
                            const date = new Date(value);
                            return date.toTimeString().split(' ')[0];
                        },
                        style: {
                            colors: textColor
                        }
                    },
                    axisBorder: {
                        color: gridColor
                    },
                    axisTicks: {
                        color: gridColor
                    }
                },
                tooltip: {
                    x: {
                        format: 'yyyy-MM-dd HH:mm:ss'
                    },
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const timestamp = timestamps[dataPointIndex];
                        const value = series[seriesIndex][dataPointIndex];
                        const formattedDate = new Date(timestamp).toLocaleString();
                        return `<div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">${formattedDate}</div>
                                <div class="apexcharts-tooltip-series-group" style="order: 1; display: flex;">
                                    <span class="apexcharts-tooltip-marker" style="background-color: ${w.config.colors[0]};"></span>
                                    <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                        <div class="apexcharts-tooltip-y-group">
                                            <span class="apexcharts-tooltip-text-y-label">${w.config.series[seriesIndex].name}: </span>
                                            <span class="apexcharts-tooltip-text-y-value">${value}</span>
                                        </div>
                                    </div>
                                </div>`;
                    },
                    theme: isDarkMode ? 'dark' : 'light'
                },
                annotations: {
                    yaxis: []
                },
                legend: {
                    position: 'top',
                    labels: {
                        colors: textColor
                    }
                },
                grid: {
                    borderColor: gridColor,
                    strokeDashArray: 4
                },
                theme: {
                    mode: isDarkMode ? 'dark' : 'light'
                }
            };

            // CPU Chart
            new ApexCharts(document.querySelector("#cpu-chart"), {
                ...commonOptions,
                series: [{
                    name: 'CPU Load',
                    data: cpuData
                }],
                colors: [cpuColor],
                yaxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: 'Percentage (%)',
                        style: {
                            color: textColor
                        }
                    },
                    labels: {
                        style: {
                            colors: textColor
                        }
                    }
                },
                annotations: {
                    yaxis: [{
                            y: cpuStats.min,
                            borderColor: minColor,
                            label: {
                                borderColor: minColor,
                                style: {
                                    color: '#fff',
                                    background: minColor
                                },
                                text: `Min: ${cpuStats.min}%`
                            }
                        },
                        {
                            y: cpuStats.avg,
                            borderColor: avgColor,
                            label: {
                                borderColor: avgColor,
                                style: {
                                    color: '#fff',
                                    background: avgColor
                                },
                                text: `Avg: ${cpuStats.avg}%`
                            }
                        },
                        {
                            y: cpuStats.max,
                            borderColor: maxColor,
                            label: {
                                borderColor: maxColor,
                                style: {
                                    color: '#fff',
                                    background: maxColor
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
                colors: [memoryColor],
                yaxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: 'Percentage (%)',
                        style: {
                            color: textColor
                        }
                    },
                    labels: {
                        style: {
                            colors: textColor
                        }
                    }
                },
                annotations: {
                    yaxis: [{
                            y: memoryStats.min,
                            borderColor: minColor,
                            label: {
                                borderColor: minColor,
                                style: {
                                    color: '#fff',
                                    background: minColor
                                },
                                text: `Min: ${memoryStats.min}%`
                            }
                        },
                        {
                            y: memoryStats.avg,
                            borderColor: avgColor,
                            label: {
                                borderColor: avgColor,
                                style: {
                                    color: '#fff',
                                    background: avgColor
                                },
                                text: `Avg: ${memoryStats.avg}%`
                            }
                        },
                        {
                            y: memoryStats.max,
                            borderColor: maxColor,
                            label: {
                                borderColor: maxColor,
                                style: {
                                    color: '#fff',
                                    background: maxColor
                                },
                                text: `Max: ${memoryStats.max}%`
                            }
                        }
                    ]
                }
            }).render();

            // Swap Chart (update with similar changes as above)
            new ApexCharts(document.querySelector("#swap-chart"), {
                ...commonOptions,
                series: [{
                    name: 'Swap Usage',
                    data: swapData
                }],
                colors: [swapColor],
                yaxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: 'Percentage (%)',
                        style: {
                            color: textColor
                        }
                    },
                    labels: {
                        style: {
                            colors: textColor
                        }
                    }
                },
                annotations: {
                    yaxis: [{
                            y: swapStats.min,
                            borderColor: minColor,
                            label: {
                                borderColor: minColor,
                                style: {
                                    color: '#fff',
                                    background: minColor
                                },
                                text: `Min: ${swapStats.min}%`
                            }
                        },
                        {
                            y: swapStats.avg,
                            borderColor: avgColor,
                            label: {
                                borderColor: avgColor,
                                style: {
                                    color: '#fff',
                                    background: avgColor
                                },
                                text: `Avg: ${swapStats.avg}%`
                            }
                        },
                        {
                            y: swapStats.max,
                            borderColor: maxColor,
                            label: {
                                borderColor: maxColor,
                                style: {
                                    color: '#fff',
                                    background: maxColor
                                },
                                text: `Max: ${swapStats.max}%`
                            }
                        }
                    ]
                }
            }).render();

            // Disk IO Chart (update with similar changes as above)
            new ApexCharts(document.querySelector("#disk-chart"), {
                ...commonOptions,
                series: [{
                    name: 'Disk Operations/Sec',
                    data: diskIoData
                }],
                colors: [diskColor],
                yaxis: {
                    min: diskIoStats.min - 10,
                    max: diskIoStats.max + 10, // Set max to the maximum value plus 100
                    title: {
                        text: 'Operations/Sec',
                        style: {
                            color: textColor
                        }
                    },
                    labels: {
                        style: {
                            colors: textColor
                        }
                    }
                },
                annotations: {
                    yaxis: [{
                            y: diskIoStats.min,
                            borderColor: minColor,
                            label: {
                                borderColor: minColor,
                                style: {
                                    color: '#fff',
                                    background: minColor
                                },
                                text: `Min: ${diskIoStats.min}`
                            }
                        },
                        {
                            y: diskIoStats.avg,
                            borderColor: avgColor,
                            label: {
                                borderColor: avgColor,
                                style: {
                                    color: '#fff',
                                    background: avgColor
                                },
                                text: `Avg: ${diskIoStats.avg}`
                            }
                        },
                        {
                            y: diskIoStats.max,
                            borderColor: maxColor,
                            label: {
                                borderColor: maxColor,
                                style: {
                                    color: '#fff',
                                    background: maxColor
                                },
                                text: `Max: ${diskIoStats.max}`
                            }
                        }
                    ]
                }
            }).render();

            // Listen for theme changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class' &&
                        mutation.target === document.documentElement) {
                        // Check if the dark mode status has actually changed
                        const currentDarkMode = document.documentElement.classList.contains('dark');
                        if (currentDarkMode !== isDarkMode) {
                            // Disconnect observer before reloading to prevent infinite loop
                            observer.disconnect();
                            window.location.reload();
                        }
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true
            });
        });
    </script>
</x-filament-panels::page>
