<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomTypeResource\Pages;
use App\Filament\Resources\RoomTypeResource\RelationManagers;
use App\Filament\Resources\RoomTypeResource\RelationManagers\RoomsRelationManager;
use App\Models\Hotel;
use App\Models\RoomType;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class RoomTypeResource extends Resource
{
    protected static ?string $model = RoomType::class;
    protected static ?string $modelLabel = 'Loại phòng';
    protected static ?string $navigationLabel = 'Quản lý loại phòng';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('hotel_id')
                    ->label(__('models.roomtype.hotel_id'))
                    ->relationship('hotel', 'name')
                    ->native(false)
                    ->required(),

                TextInput::make('name')
                    ->label(__('models.roomtype.name'))
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label(__('models.roomtype.description'))
                    ->columnSpanFull(),

                TextInput::make('max_occupancy')
                    ->label(__('models.roomtype.max_occupancy'))
                    ->required()
                    ->integer()
                    ->default(1)
                    ->minValue(1)
                    ->maxValue(10),

                TextInput::make('base_price')
                    ->label(__('models.roomtype.base_price'))
                    ->required()
                    ->numeric()
                    ->default(100000)
                    ->step(50000)
                    ->minValue(100000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('hotel.name')
                //     ->label(__('models.roomtype.hotel_id')),

                TextColumn::make('name')
                    ->label(__('models.roomtype.name'))
                    ->searchable(),

                TextColumn::make('max_occupancy')
                    ->label(__('models.roomtype.max_occupancy'))
                    ->icon('heroicon-m-users')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('base_price')
                    ->label(__('models.roomtype.base_price'))
                    ->numeric()
                    ->money('VND')
                    ->sortable(),

                TextColumn::make('rooms_count')
                    ->counts('rooms')
                    ->label(__('models.roomtype.rooms_count'))
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('models.roomtype.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('models.roomtype.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('generateWeeklyRates')
                    ->label('Tạo giá hàng tuần')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Ngày bắt đầu')
                            ->required()
                            ->default(Carbon::today()),
                        Forms\Components\TextInput::make('duration')
                            ->label('Số ngày')
                            ->required()
                            ->numeric()
                            ->default(28)
                            ->minValue(1),
                        Forms\Components\TextInput::make('weekday_price')
                            ->label('Giá ngày thường')
                            ->required()
                            ->numeric()
                            ->prefix('VNĐ')
                            ->minValue(0),
                        Forms\Components\TextInput::make('weekend_price')
                            ->label('Giá cuối tuần')
                            ->required()
                            ->numeric()
                            ->prefix('VNĐ')
                            ->minValue(0),
                        Forms\Components\Toggle::make('is_promotion')
                            ->label('Áp dụng khuyến mãi cho cuối tuần')
                            ->default(false),
                    ])
                    ->action(function ($record, array $data) {
                        $startDate = Carbon::parse($data['start_date']);
                        $duration = (int) $data['duration'];
                        $weekdayPrice = (float) $data['weekday_price'];
                        $weekendPrice = (float) $data['weekend_price'];
                        $isPromotion = $data['is_promotion'];

                        for ($i = 0; $i < $duration; $i++) {
                            $currentDate = $startDate->copy()->addDays($i);
                            $isWeekend = $currentDate->isSaturday() || $currentDate->isSunday();
                            $price = $isWeekend ? $weekendPrice : $weekdayPrice;

                            $record->roomRates()->updateOrCreate(
                                ['date' => $currentDate],
                                [
                                    'price' => $price,
                                    'is_promotion' => $isWeekend ? $isPromotion : false,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]
                            );
                        }
                    })
                    ->color('success'),
                    // ->iconButton(),
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
            RoomsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoomTypes::route('/'),
            'create' => Pages\CreateRoomType::route('/create'),
            'edit' => Pages\EditRoomType::route('/{record}/edit'),
        ];
    }
}
