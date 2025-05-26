<?php

namespace App\Filament\Resources\RoomRateResource\Pages;

use App\Filament\Resources\RoomRateResource;
use App\Models\Hotel;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoomRates extends ListRecords
{
    protected static string $resource = RoomRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        // Lấy danh sách hotel có room_rates thông qua room_types
        $hotels = Hotel::select('hotels.id', 'hotels.name')
            ->join('room_types', 'hotels.id', '=', 'room_types.hotel_id')
            ->join('room_rates', 'room_types.id', '=', 'room_rates.room_type_id')
            ->distinct()
            ->get();

        if ($hotels->isEmpty())
            return [];

        foreach ($hotels as $hotel) {
            $tabs[$hotel->id] = \Filament\Resources\Components\Tab::make()
                ->label($hotel->name)
                ->modifyQueryUsing(function ($query) use ($hotel) {
                    $query->whereHas('roomtype', function ($q) use ($hotel) {
                        $q->where('hotel_id', $hotel->id);
                    });
                });
        }

        return $tabs;
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return Hotel::select('id')->first()->id;
    }
}
