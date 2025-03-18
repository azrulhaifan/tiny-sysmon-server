<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('server_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->cascadeOnDelete();
            $table->timestamp('timestamp');
            $table->bigInteger('uptime');

            // CPU metrics
            $table->float('cpu_load', 8, 2);

            // Memory metrics
            $table->bigInteger('memory_total');
            $table->bigInteger('memory_used');
            $table->bigInteger('memory_free');
            $table->bigInteger('memory_active');
            $table->bigInteger('memory_available');
            $table->float('memory_used_percent', 8, 2);

            // Swap metrics
            $table->bigInteger('swap_total');
            $table->bigInteger('swap_used');
            $table->bigInteger('swap_free');
            $table->float('swap_used_percent', 8, 2);

            // Disk IO metrics
            $table->bigInteger('disk_read_ops');
            $table->bigInteger('disk_write_ops');
            $table->float('disk_read_ops_per_sec', 8, 2);
            $table->float('disk_write_ops_per_sec', 8, 2);
            $table->float('disk_total_ops_per_sec', 8, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_metrics');
    }
};
