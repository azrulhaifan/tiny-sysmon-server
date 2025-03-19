<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServerResource\Pages;
use App\Filament\Resources\ServerResource\RelationManagers;
use App\Models\Server;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('api_key')
                    ->default(fn() => (string) Str::uuid())
                    ->disabled()
                    ->dehydrated()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('retention')
                    ->label('Data Retention (record)')
                    ->numeric()
                    ->default(100000)
                    ->required()
                    ->minValue(1440)
                    ->maxValue(100000)
                    ->helperText(new HtmlString('<div class="space-y-2">
                        <p>Number of records to keep in the metrics data.</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Default: 80,000 records</li>
                            <li>Min: 1440 record</li>
                            <li>Max: 100,000 records</li>
                            <li>Each record represents one metric entry</li>
                            <li>Older records will be automatically deleted</li>
                            <li>Example : 15s metric interval will reach 40.320 record in 7 days (168 hours)</li>
                            <li>or: 30s metric interval will reach 86.400 record in 30 days</li>
                        </ul>
                    </div>')),
                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->required()
                    ->helperText("If inactive, the server will not be monitored and not received any metrics."),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('api_key')
                    ->searchable()
                    ->icon('heroicon-m-document-duplicate')
                    ->iconPosition('after')
                    ->copyable(fn($record) => $record->api_key)
                    ->formatStateUsing(fn($state) => str_repeat('•', 8))
                    // ->tooltip(fn ($record) => $record->api_key)
                    ->label('API Key'),
                Tables\Columns\TextColumn::make('retention')
                    ->label('Retention (rec)')
                    ->numeric(
                        thousandsSeparator: ',',
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_metrics_dt')
                    ->label('Last Metrics')
                    ->dateTime('Y-m-d H:i:s')
                    ->placeholder('-')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('metrics')
                    ->label('View Metrics')
                    ->icon('heroicon-o-chart-bar')
                    ->color('success')
                    ->url(fn(Server $record): string => static::getUrl('metrics', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServers::route('/'),
            'create' => Pages\CreateServer::route('/create'),
            'edit' => Pages\EditServer::route('/{record}/edit'),
            // 'metrics' => Pages\ViewServerMetrics::route('/{record}/metrics'),
            'metrics' => Pages\ServerMetrics::route('/{record}/metrics'),
        ];
    }
}
