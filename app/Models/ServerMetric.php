<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'timestamp',
        'uptime',
        'cpu_load',
        'memory_total',
        'memory_used',
        'memory_free',
        'memory_active',
        'memory_available',
        'memory_used_percent',
        'swap_total',
        'swap_used',
        'swap_free',
        'swap_used_percent',
        'disk_read_ops',
        'disk_write_ops',
        'disk_read_ops_per_sec',
        'disk_write_ops_per_sec',
        'disk_total_ops_per_sec',
        'network_rx_bytes',
        'network_tx_bytes',
        'network_rx_sec',
        'network_tx_sec',
    ];

    protected $casts = [
        'cpu_load' => 'float',
        'memory_used_percent' => 'float',
        'swap_used_percent' => 'float',
        'disk_read_ops_per_sec' => 'float',
        'disk_write_ops_per_sec' => 'float',
        'disk_total_ops_per_sec' => 'float',
    ];

    /**
     * Get the server that owns the metric.
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
