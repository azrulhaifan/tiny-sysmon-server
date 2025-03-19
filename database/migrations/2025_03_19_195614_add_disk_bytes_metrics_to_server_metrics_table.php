<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('server_metrics', function (Blueprint $table) {
            $table->double('disk_read_bytes_per_sec')->after('disk_total_ops_per_sec')->default(0);
            $table->double('disk_write_bytes_per_sec')->after('disk_read_bytes_per_sec')->default(0);
        });
    }

    public function down()
    {
        Schema::table('server_metrics', function (Blueprint $table) {
            $table->dropColumn(['disk_read_bytes_per_sec', 'disk_write_bytes_per_sec']);
        });
    }
};
