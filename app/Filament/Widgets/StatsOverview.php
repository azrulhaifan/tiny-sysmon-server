<?php

namespace App\Filament\Widgets;

use App\Models\Server;
use App\Models\ServerMetric;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Servers', Server::count())
                ->description('Total registered servers')
                ->icon('heroicon-o-server')
                ->color('info'),

            Stat::make('Active Servers', Server::where('is_active', 1)->count())
                ->description('Servers currently being monitored')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Inactive Servers', Server::where('is_active', 0)->count())
                ->description('Servers not being monitored')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make('Total Metrics', function () {
                $count = ServerMetric::count();
                if ($count >= 1000000) {
                    return number_format($count / 1000000, 3) . 'M+';
                }
                return number_format($count);
            })
                ->description('Total metrics collected')
                ->icon('heroicon-o-chart-bar')
                ->color('warning'),
        ];
    }
}
