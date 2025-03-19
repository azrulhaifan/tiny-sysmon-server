<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('metrics:cleanup')->hourly()
    ->name('Cleanup old server metrics')
    ->onSuccess(function () {
        info('Metrics cleanup completed successfully');
    })
    ->onFailure(function () {
        info('Metrics cleanup failed');
    });
