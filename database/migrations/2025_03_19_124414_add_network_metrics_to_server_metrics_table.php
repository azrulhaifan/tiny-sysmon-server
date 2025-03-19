<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('server_metrics', function (Blueprint $table) {
            $table->bigInteger('network_rx_bytes')->after('disk_total_ops_per_sec');
            $table->bigInteger('network_tx_bytes')->after('network_rx_bytes');
            $table->float('network_rx_sec')->after('network_tx_bytes');
            $table->float('network_tx_sec')->after('network_rx_sec');
        });
    }

    public function down()
    {
        Schema::table('server_metrics', function (Blueprint $table) {
            $table->dropColumn([
                'network_rx_bytes',
                'network_tx_bytes',
                'network_rx_sec',
                'network_tx_sec'
            ]);
        });
    }
};