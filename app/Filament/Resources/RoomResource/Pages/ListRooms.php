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
        // Lấy danh sách hotel với rooms_count (chỉ tính is_available = true)
        $hotels = Hotel::query()->select('id', 'name')
            ->withCount(['rooms' => fn($query) => $query->where('is_available', true)])
            ->get();

        if ($hotels->isEmpty())
            return [];

        foreach ($hotels as $hotel) {
            $tabs[$hotel->id] = \Filament\Resources\Components\Tab::make()
                ->label($hotel->name)
                ->badge(fn() => $hotel->rooms_count)
                ->badgeColor('success')
                ->modifyQueryUsing(fn($query) => $query->where('hotel_id', $hotel->id));
        }

        return $tabs;
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return Hotel::select('id')->first()->id;
    }
}
