<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use App\Models\Server;
use App\Models\ServerMetric;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class ViewServerMetrics extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = ServerResource::class;

    protected static string $view = 'filament.resources.server-resource.pages.view-server-metrics';

    public ?Server $record = null;

    public function mount(Server $record): void
    {
        $this->record = $record;
    }

    public function getTableQuery(): Builder
    {
        return ServerMetric::query()->where('server_id', $this->record->id)->latest('timestamp');
    }

    public function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('timestamp')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('uptime')
                ->label('Uptime (seconds)')
                ->numeric(),
            Tables\Columns\TextColumn::make('cpu_load')
                ->label('CPU Load (%)')
                ->numeric(2),
            Tables\Columns\TextColumn::make('memory_used_percent')
                ->label('Memory Usage (%)')
                ->numeric(2),
            Tables\Columns\TextColumn::make('swap_used_percent')
                ->label('Swap Usage (%)')
                ->numeric(2),
            Tables\Columns\TextColumn::make('disk_total_ops_per_sec')
                ->label('Disk Ops/Sec')
                ->numeric(2),
        ];
    }

    public function getTableFilters(): array
    {
        return [
            Tables\Filters\Filter::make('created_at')
                ->form([
                    DatePicker::make('from'),
                    DatePicker::make('until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['from'],
                            fn(Builder $query, $date): Builder => $query->whereDate('timestamp', '>=', $date),
                        )
                        ->when(
                            $data['until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('timestamp', '<=', $date),
                        );
                }),
        ];
    }

    public function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->modalContent(fn(ServerMetric $record) => view('filament.resources.server-resource.pages.server-metric-details', ['record' => $record])),
        ];
    }

    public function getTableBulkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make(),
        ];
    }
}
