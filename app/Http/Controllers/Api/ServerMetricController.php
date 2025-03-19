<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\ServerMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServerMetricController extends Controller
{
    public function store(Request $request)
    {
        // Validate API Key
        $server = Server::where('api_key', $request->header('X-API-Key'))->first();

        if (!$server) {
            return response()->json([
                'message' => 'Invalid API Key'
            ], 401);
        }

        // Validate payload
        $validator = Validator::make($request->all(), [
            'timestamp' => 'required|string',
            'hostname' => 'required|string',
            'uptime' => 'required|numeric',
            'cpu.currentLoad' => 'required|numeric',
            'memory.total' => 'required|numeric',
            'memory.used' => 'required|numeric',
            'memory.free' => 'required|numeric',
            'memory.active' => 'required|numeric',
            'memory.available' => 'required|numeric',
            'memory.usedPercent' => 'required|numeric',
            'swap.total' => 'required|numeric',
            'swap.used' => 'required|numeric',
            'swap.free' => 'required|numeric',
            'swap.usedPercent' => 'required|numeric',
            'diskIO.readOps' => 'required|numeric',
            'diskIO.writeOps' => 'required|numeric',
            'diskIO.readOpsPerSec' => 'required|numeric',
            'diskIO.writeOpsPerSec' => 'required|numeric',
            'diskIO.totalOpsPerSec' => 'required|numeric',
            'network.total.rx_bytes' => 'required|numeric',
            'network.total.tx_bytes' => 'required|numeric',
            'network.total.rx_sec' => 'required|numeric',
            'network.total.tx_sec' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid payload',
                'errors' => $validator->errors()
            ], 422);
        }

        // Store metric
        $metric = new ServerMetric();
        $metric->server_id = $server->id;
        $metric->timestamp = strtotime($request->timestamp);
        $metric->uptime = (int) $request->uptime;
        $metric->cpu_load = $request->input('cpu.currentLoad');
        $metric->memory_total = $request->input('memory.total');
        $metric->memory_used = $request->input('memory.used');
        $metric->memory_free = $request->input('memory.free');
        $metric->memory_active = $request->input('memory.active');
        $metric->memory_available = $request->input('memory.available');
        $metric->memory_used_percent = $request->input('memory.usedPercent');
        $metric->swap_total = $request->input('swap.total');
        $metric->swap_used = $request->input('swap.used');
        $metric->swap_free = $request->input('swap.free');
        $metric->swap_used_percent = $request->input('swap.usedPercent');
        $metric->disk_read_ops = $request->input('diskIO.readOps');
        $metric->disk_write_ops = $request->input('diskIO.writeOps');
        $metric->disk_read_ops_per_sec = $request->input('diskIO.readOpsPerSec');
        $metric->disk_write_ops_per_sec = $request->input('diskIO.writeOpsPerSec');
        $metric->disk_total_ops_per_sec = $request->input('diskIO.totalOpsPerSec');
        $metric->network_rx_bytes = $request->input('network.total.rx_bytes');
        $metric->network_tx_bytes = $request->input('network.total.tx_bytes');
        $metric->network_rx_sec = $request->input('network.total.rx_sec');
        $metric->network_tx_sec = $request->input('network.total.tx_sec');
        $metric->save();

        return response()->json([
            'message' => 'Metric stored successfully'
        ], 201);
    }
}
