<?php

namespace App\Console\Commands;

use App\Models\Server;
use App\Models\ServerMetric;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupServerMetrics extends Command
{
    protected $signature = 'metrics:cleanup';
    protected $description = 'Cleanup old server metrics based on retention configuration';

    public function handle()
    {
        $servers = Server::all();
        $this->info('Starting metrics cleanup...');

        foreach ($servers as $server) {
            $totalMetrics = ServerMetric::select('id')->where('server_id', $server->id)->count();
            $this->info("Server: {$server->name}");
            $this->info("Total metrics: " . number_format($totalMetrics));
            $this->info("Retention limit: " . number_format($server->retention));

            if ($totalMetrics > $server->retention) {
                $metricsToDelete = $totalMetrics - $server->retention;
                $this->warn("Need to delete: " . number_format($metricsToDelete) . " records");

                // Delete oldest records that exceed retention limit
                ServerMetric::where('server_id', $server->id)
                    ->orderBy('timestamp', 'asc')
                    ->limit($metricsToDelete)
                    ->delete();

                $this->info("Cleanup completed for {$server->name}");
            } else {
                $this->info("No cleanup needed for {$server->name}");
            }

            $this->newLine();
        }

        $this->info('All servers processed successfully!');
    }
}
