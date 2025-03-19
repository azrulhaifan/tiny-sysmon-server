<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dateTime('last_metrics_dt')->nullable()->after('retention');
            $table->smallInteger('is_active')->default(1)->after('last_metrics_dt');
        });
    }

    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('last_metrics_dt');
            $table->dropColumn('is_active');
        });
    }
};
