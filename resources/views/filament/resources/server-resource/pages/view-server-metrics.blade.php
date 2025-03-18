<x-filament-panels::page>
    <x-filament::section>
        <h2 class="text-xl font-bold tracking-tight">
            Metrics for {{ $this->record->name }}
        </h2>
        
        <div class="mt-4">
            {{ $this->table }}
        </div>
    </x-filament::section>
</x-filament-panels::page>
