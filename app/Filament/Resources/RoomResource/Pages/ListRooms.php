<?php

namespace App\Filament\Resources\RoomResource\Pages;

use App\Filament\Resources\RoomResource;
use App\Models\Hotel;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRooms extends ListRecords
{
    protected static string $resource = RoomResource::class;

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
            $tabs[$id] = \Filament\Resources\Components\Tab::make()
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
