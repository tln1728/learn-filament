<?php

namespace App\Filament\Resources\RoomRateResource\Pages;

use App\Filament\Resources\RoomRateResource;
use App\Models\RoomRate;
use App\Models\RoomType;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CreateRoomRate extends CreateRecord
{
    protected static string $resource = RoomRateResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $roomType    = RoomType::find($data['room_type_id']);
        $price       = $data['price'];
        $isPromotion = $price < $data['room_type_price'];
        $startDate   = Carbon::parse($data['date']);
        $endDate     = $data['end_date'] ? Carbon::parse($data['end_date']) : $startDate;
        $daysOfWeek  = $data['days_of_week'] ?? []; // Mảng các ngày trong tuần (0-6)

        // [
        //     '0' => 'Sunday',
        //     '1' => 'Monday',
        //     '2' => 'Tuesday',
        //     '3' => 'Wednesday',
        //     '4' => 'Thursday',
        //     '5' => 'Friday',
        //     '6' => 'Saturday',
        // ];

        // Biến lưu bản ghi đầu tiên để trả về cho Filament
        $firstCreatedRecord = null;

        // Lặp qua từng ngày từ startDate đến endDate
        $currentDate = $startDate->copy();
        // while startDate < endDate 
        while ($currentDate->lte($endDate)) {
            $currentDayIndex = (string) $currentDate->dayOfWeek; // 0 (Chủ Nhật) -> 6 (Thứ Bảy)

            // Nếu không chọn days_of_week hoặc ngày hiện tại nằm trong days_of_week
            if (empty($daysOfWeek) || in_array($currentDayIndex, $daysOfWeek)) {
                $existingRate = RoomRate::query()
                    ->where('room_type_id', $roomType->id)
                    ->where('date', $currentDate->toDateString())
                    ->first();

                $record = RoomRate::updateOrCreate(
                    [
                        'room_type_id' => $roomType->id,
                        'date'         => $currentDate->toDateString(),
                    ],
                    [
                        'price'        => $price,
                        'is_promotion' => $isPromotion,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]
                );

                // Lưu bản ghi đầu tiên
                if (!$firstCreatedRecord) $firstCreatedRecord = $record;

                // Thông báo nếu bản ghi đã tồn tại và được cập nhật
                // if ($existingRate) {
                //     Notification::make()
                //         ->title('Cập nhật giá thành công')
                //         ->body('Giá cho ngày ' . $currentDate->format('d/m/Y') . ' đã được cập nhật.')
                //         ->success()
                //         ->send();
                // } else {
                //     Notification::make()
                //         ->title('Tạo giá thành công')
                //         ->body('Giá cho ngày ' . $currentDate->format('d/m/Y') . ' đã được tạo.')
                //         ->success()
                //         ->send();
                // }
            }

            $currentDate->addDay();
        }

        // Nếu không có bản ghi nào được tạo (?), tạo một bản ghi mặc định
        if (!$firstCreatedRecord) {
            dd('huh');
            $firstCreatedRecord = $roomType->roomRates()->create([
                'date'         => $startDate->toDateString(),
                'price'        => $price,
                'is_promotion' => $isPromotion,
            ]);

            Notification::make()
                ->title('Tạo giá thành công')
                ->body('Giá cho ngày ' . $startDate->format('d/m/Y') . ' đã được tạo.')
                ->success()
                ->send();
        }

        return $firstCreatedRecord;
    }
}
