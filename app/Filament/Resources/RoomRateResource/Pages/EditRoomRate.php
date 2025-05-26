<?php

namespace App\Filament\Resources\RoomRateResource\Pages;

use App\Filament\Resources\RoomRateResource;
use App\Models\RoomType;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoomRate extends EditRecord
{
    protected static string $resource = RoomRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $roomtype = RoomType::select('base_price')->find($data['room_type_id']);

        $data['room_type_price'] = number_format($roomtype->base_price, 0, '.', '.');

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['is_promotion'] = $data['price'] < $data['room_type_price'];
        return $data;
    }
}
