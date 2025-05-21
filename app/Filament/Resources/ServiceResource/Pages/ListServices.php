<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use App\Models\Hotel;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        $hotels = Hotel::query()->select('id', 'name')
            ->withCount('services')
            ->get();

        if ($hotels->isEmpty())
            return [];

        foreach ($hotels as $hotel) {
            $tabs[$hotel->id] = \Filament\Resources\Components\Tab::make()
                ->label($hotel->name)
                ->badge(fn() => $hotel->services_count)
                ->modifyQueryUsing(fn($query) => $query->where('hotel_id', $hotel->id));
        }

        return $tabs;
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return Hotel::select('id')->first()->id;
    }
}
