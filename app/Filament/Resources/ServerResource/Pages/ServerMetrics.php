<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use App\Models\ServerMetric;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class ServerMetrics extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ServerResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Metrics';
    protected static ?string $title = 'Server Metrics';
    
    protected static string $view = 'filament.resources.server-resource.pages.server-metrics';

    public ?array $data = [];
    public ?array $chartData = [];
    public bool $isChartVisible = false;
    public string $selectedTimeframe = '30s';
    
    public $record;

    public function mount($record): void
    {
        $this->record = $record;
        $this->form->fill([
            'dateStart' => Carbon::now()->subHours(3)->format('Y-m-d H:i'),
            'dateEnd' => Carbon::now()->format('Y-m-d H:i'),
            'timeframe' => '30s', // Add default timeframe here
        ]);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Data')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                DateTimePicker::make('dateStart')
                                    ->label('Date Start')
                                    ->required()
                                    ->maxDate(fn () => $this->data['dateEnd'] ?? now())
                                    ->seconds(false)
                                    ->displayFormat('Y-m-d H:i'),
                                DateTimePicker::make('dateEnd')
                                    ->label('Date End')
                                    ->required()
                                    ->maxDate(now())
                                    ->seconds(false)
                                    ->displayFormat('Y-m-d H:i'),
                                Select::make('timeframe')
                                    ->label('Time Frame')
                                    ->options([
                                        '30s' => '30 Seconds',
                                        '1m' => '1 Minute',
                                        '2m' => '2 Minutes',
                                        '3m' => '3 Minutes',
                                        '5m' => '5 Minutes',
                                        '15m' => '15 Minutes',
                                    ])
                                    ->default('30s')
                                    ->required(),
                            ]),
                    ])
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $this->validate();
        
        $dateStart = Carbon::parse($this->data['dateStart']);
        $dateEnd = Carbon::parse($this->data['dateEnd']);
        
        if ($dateEnd->diffInHours($dateStart) > 24) {
            Notification::make()
                ->warning()
                ->title('Invalid Date Range')
                ->body('Please select a date range of 24 hours or less.')
                ->send();
            return;
        }
        
        $dateStart = strtotime($dateStart);
        $dateEnd = strtotime($dateEnd);
        
        // Get interval in seconds based on timeframe
        $interval = match($this->data['timeframe']) {
            '30s' => 30,
            '1m' => 60,
            '2m' => 120,
            '3m' => 180,
            '5m' => 300,
            '15m' => 900,
            default => 30,
        };

        // Temporarily disable ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        
        $metrics = ServerMetric::select(
                DB::raw("FLOOR(timestamp / {$interval}) * {$interval} as time_interval"),
                DB::raw("FROM_UNIXTIME(FLOOR(timestamp / {$interval}) * {$interval}) as dates"),
                DB::raw('AVG(cpu_load) as pointCpu'),
                DB::raw('AVG(memory_used_percent) as pointMemory'),
                DB::raw('AVG(swap_used_percent) as pointSwap'),
                DB::raw('AVG(disk_read_ops_per_sec) as pointDiskRead'),
                DB::raw('AVG(disk_write_ops_per_sec) as pointDiskWrite')
            )
            ->where('server_id', $this->record)
            ->whereBetween('timestamp', [$dateStart, $dateEnd])
            ->groupBy(DB::raw("FLOOR(timestamp / {$interval}) * {$interval}"))
            ->orderBy('time_interval')
            ->get();
            
        // Reset session SQL mode
        DB::statement("SET SESSION sql_mode=(SELECT CONCAT(@@sql_mode,',ONLY_FULL_GROUP_BY'));");
        
        $this->chartData = [
            'dates' => $metrics->pluck('dates'),
            'pointCpu' => $metrics->pluck('pointCpu'),
            'pointMemory' => $metrics->pluck('pointMemory'),
            'pointSwap' => $metrics->pluck('pointSwap'),
            'pointDiskRead' => $metrics->pluck('pointDiskRead'),
            'pointDiskWrite' => $metrics->pluck('pointDiskWrite'),
        ];
        
        $this->isChartVisible = true;
    }
}