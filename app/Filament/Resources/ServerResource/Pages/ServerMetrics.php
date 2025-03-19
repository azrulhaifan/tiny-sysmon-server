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
    
    // Untuk mendapatkan server_id dari URL
    public $record;

    public function mount($record): void
    {
        $this->record = $record;
        $this->form->fill([
            'dateStart' => Carbon::now()->subDays(7)->format('Y-m-d'),
            'dateEnd' => Carbon::now()->format('Y-m-d'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filter Data')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('dateStart')
                                    ->label('Date Start')
                                    ->required(),
                                DatePicker::make('dateEnd')
                                    ->label('Date End')
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
        
        $dateStart = strtotime(Carbon::parse($this->data['dateStart'])->startOfDay());
        $dateEnd = strtotime(Carbon::parse($this->data['dateEnd'])->endOfDay());
        
        $metrics = ServerMetric::select(
                DB::raw('FROM_UNIXTIME(timestamp) as dates'),
                DB::raw('cpu_load as pointCpu'),
                DB::raw('memory_used_percent as pointMemory'),
                DB::raw('swap_used_percent as pointSwap'),
                DB::raw('disk_read_ops_per_sec as pointDiskRead'),
                DB::raw('disk_write_ops_per_sec as pointDiskWrite')
            )
            ->where('server_id', $this->record)
            ->whereBetween('timestamp', [$dateStart, $dateEnd])
            ->orderBy('dates')
            ->get();
        
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