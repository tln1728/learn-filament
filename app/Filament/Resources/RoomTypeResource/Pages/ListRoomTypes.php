<?php

namespace App\Filament\Resources\RoomTypeResource\Pages;

use App\Filament\Resources\RoomTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Hotel;
use Filament\Resources\Components\Tab;

class ListRoomTypes extends ListRecords
{
    protected static string $resource = RoomTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $hotels = Hotel::pluck('name', 'id');

        if ($hotels->isEmpty())
            return [];

        // $tabs = [
        //     'Tất cả' => Tab::make(),
        //     Tab::make()->label('Tất cả'),
        // ];

        foreach ($hotels as $id => $name) {
            $tabs[$id] = Tab::make()
                ->label($name)
                ->modifyQueryUsing(fn($query) => $query->where('hotel_id', $id));
        }

        return $tabs;
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return Hotel::select('id')->first()->id;
    }
}
