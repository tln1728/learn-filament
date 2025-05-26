<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomRateResource\Pages;
use App\Filament\Resources\RoomRateResource\RelationManagers;
use App\Models\RoomRate;
use App\Models\RoomType;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group as TableGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class RoomRateResource extends Resource
{
    protected static ?string $model = RoomRate::class;
    protected static ?string $modelLabel = 'Giá phòng';
    protected static ?string $navigationLabel = 'Giá phòng theo ngày';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Select::make('room_type_id')
                        ->label(__('models.roomrate.room_type_id'))
                        ->placeholder('Chọn loại phòng')
                        ->relationship('roomtype', 'name', modifyQueryUsing: function ($query) {
                            $query->with('hotel:id,name');
                        })
                        ->getOptionLabelFromRecordUsing(function ($record) {
                            return $record->hotel->name . ' - ' . $record->name;
                        })
                        ->afterStateUpdated(function ($set, $state) {
                            $roomtype = RoomType::select('base_price')->find($state);
                            $set('room_type_price', $roomtype 
                                ? number_format($roomtype->base_price, 0, '.', '.') 
                                : null
                            );
                        })
                        ->reactive()
                        ->searchable()
                        ->preload()
                        ->required()
                        ->disabledOn('edit')
                        ->columnSpanFull(),
    
                    TextInput::make('room_type_price')
                        ->label(__('models.roomtype.base_price'))
                        ->stripCharacters('.')
                        ->prefix('VND')
                        ->disabled()
                        ->dehydrated(),
    
                    TextInput::make('price')
                        ->label(__('models.roomrate.price'))
                        ->required()
                        ->minValue(0)
                        ->mask(RawJs::make('$money($input)'))
                        ->stripCharacters(',')
                        ->prefix('VND'),

                    DatePicker::make('date')
                        ->label(__('models.roomrate.date'))
                        ->placeholder('dd/mm/yyyy')
                        ->displayFormat('d/m/Y')
                        ->reactive()
                        ->minDate('today')
                        ->required(),

                    DatePicker::make('end_date')
                        ->label('Ngày kết thúc')
                        ->minDate(fn ($get) => Carbon::parse($get('date'))->addDay())
                        ->after('date')
                        ->requiredWith('days_of_week')
                        ->hiddenOn('edit'),
                        
                    Toggle::make('is_promotion')
                        ->label(__('models.roomrate.is_promotion'))
                        ->default(false)
                        ->disabled()
                        ->dehydrated(),

                ])->columns(2),

                Fieldset::make('Lặp lại hàng tuần (Không bắt buộc)')->schema([
                    ToggleButtons::make('days_of_week')
                        ->label('Các thứ trong tuần')
                        ->hiddenLabel()
                        ->options([
                            1 => 'Thứ Hai',
                            2 => 'Thứ Ba',
                            3 => 'Thứ Tư',
                            4 => 'Thứ Năm',
                            5 => 'Thứ Sáu',
                            6 => 'Thứ Bảy',
                            0 => 'Chủ Nhật',
                        ])
                        ->inline()
                        ->multiple()
                        ->columnSpanFull(),
                ])->columnSpan(1),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('roomtype.name')
                    ->label(__('models.roomrate.room_type_id'))
                    ->hidden()
                    ->searchable(),

                TextColumn::make('date')
                    ->label(__('models.roomrate.date'))
                    ->formatStateUsing(function ($state) {
                        $date = Carbon::parse($state)->locale(app()->getLocale())->translatedFormat('l d-m-Y');
                        return ucfirst($date); // Viết hoa chữ cái đầu
                    })
                    ->sortable(),

                TextColumn::make('price')
                    ->label(__('models.roomrate.price'))
                    ->money('VND')
                    ->sortable(),

                IconColumn::make('is_promotion')
                    ->label(__('models.roomrate.is_promotion'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label(__('models.roomrate.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('models.roomrate.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'asc')
            ->defaultGroup(
                TableGroup::make('roomtype.name')
                    ->label(__('models.roomtype.name'))
                    ->collapsible()
            )
            ->defaultPaginationPageOption(50)
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListRoomRates::route('/'),
            'create' => Pages\CreateRoomRate::route('/create'),
            'edit' => Pages\EditRoomRate::route('/{record}/edit'),
        ];
    }
}

// room_type_id
// ->options(function () {
//     return RoomType::with('hotel:id,name')->get()
//         ->groupBy('hotel.name')
//         ->mapWithKeys(function ($roomTypes, $hotelName) {
//             return [
//                 $hotelName => $roomTypes->pluck('name', 'id')->toArray(),
//             ];
//         })
//         ->toArray();
// })