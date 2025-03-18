<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="text-lg font-medium">Basic Info</h3>
            <div class="mt-2 space-y-2">
                <div>
                    <span class="font-medium">Timestamp:</span> {{ $record->timestamp->format('Y-m-d H:i:s') }}
                </div>
                <div>
                    <span class="font-medium">Uptime:</span> {{ $record->uptime }} seconds
                </div>
            </div>
        </div>
        
        <div>
            <h3 class="text-lg font-medium">CPU</h3>
            <div class="mt-2 space-y-2">
                <div>
                    <span class="font-medium">Load:</span> {{ $record->cpu_load }}%
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="text-lg font-medium">Memory</h3>
            <div class="mt-2 space-y-2">
                <div>
                    <span class="font-medium">Total:</span> {{ number_format($record->memory_total / 1024 / 1024, 2) }} MB
                </div>
                <div>
                    <span class="font-medium">Used:</span> {{ number_format($record->memory_used / 1024 / 1024, 2) }} MB
                </div>
                <div>
                    <span class="font-medium">Free:</span> {{ number_format($record->memory_free / 1024 / 1024, 2) }} MB
                </div>
                <div>
                    <span class="font-medium">Active:</span> {{ number_format($record->memory_active / 1024 / 1024, 2) }} MB
                </div>
                <div>
                    <span class="font-medium">Available:</span> {{ number_format($record->memory_available / 1024 / 1024, 2) }} MB
                </div>
                <div>
                    <span class="font-medium">Used Percent:</span> {{ $record->memory_used_percent }}%
                </div>
            </div>
        </div>
        
        <div>
            <h3 class="text-lg font-medium">Swap</h3>
            <div class="mt-2 space-y-2">
                <div>
                    <span class="font-medium">Total:</span> {{ number_format($record->swap_total / 1024 / 1024, 2) }} MB
                </div>
                <div>
                    <span class="font-medium">Used:</span> {{ number_format($record->swap_used / 1024 / 1024, 2) }} MB
                </div>
                <div>
                    <span class="font-medium">Free:</span> {{ number_format($record->swap_free / 1024 / 1024, 2) }} MB
                </div>
                <div>
                    <span class="font-medium">Used Percent:</span> {{ $record->swap_used_percent }}%
                </div>
            </div>
        </div>
    </div>
    
    <div>
        <h3 class="text-lg font-medium">Disk I/O</h3>
        <div class="mt-2 space-y-2">
            <div>
                <span class="font-medium">Read Operations:</span> {{ number_format($record->disk_read_ops) }}
            </div>
            <div>
                <span class="font-medium">Write Operations:</span> {{ number_format($record->disk_write_ops) }}
            </div>
            <div>
                <span class="font-medium">Read Ops/Sec:</span> {{ $record->disk_read_ops_per_sec }}
            </div>
            <div>
                <span class="font-medium">Write Ops/Sec:</span> {{ $record->disk_write_ops_per_sec }}
            </div>
            <div>
                <span class="font-medium">Total Ops/Sec:</span> {{ $record->disk_total_ops_per_sec }}
            </div>
        </div>
    </div>
</div>