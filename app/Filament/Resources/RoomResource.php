<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;
    protected static ?string $modelLabel = 'Phòng';
    protected static ?string $navigationLabel = 'Quản lý phòng';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('hotel_id')
                    ->label(__('models.room.hotel_id'))
                    ->relationship('hotel', 'name')
                    ->native(false)
                    ->live()
                    ->placeholder('Chọn khách sạn')
                    ->afterStateUpdated(fn($set) => $set('room_type_id', null))
                    ->required(),

                Select::make('room_type_id')
                    ->label(__('models.room.room_type_id'))
                    ->options(function ($get): array {
                        // $hotelId = $get('hotel_id');
                        // if (!$hotelId) return [];
                        return \App\Models\RoomType::where('hotel_id', $get('hotel_id'))
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->disabled(fn($get) => !$get('hotel_id'))
                    ->placeholder('Chọn loại phòng')
                    ->native(false)
                    ->required(),

                TextInput::make('room_number')
                    ->label(__('models.room.room_number'))
                    ->maxLength(255)
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule, $get) => 
                            $rule->where('hotel_id', $get('hotel_id'))
                        // modifyRuleUsing: function (Unique $rule, $get) {
                        //     $hotelId = $get('hotel_id');
                        //     if ($hotelId) return $rule->where('hotel_id', $hotelId);
                        //     return $rule;
                        // }
                    )
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('') // roomtype.name
                //     ->label(__('models.room.room_type_id')),

                TextColumn::make('room_number')
                    ->label(__('models.room.room_number'))
                    ->searchable(),

                // (env('APP_DEBUG')) 
                // ? ToggleColumn::make('is_available')
                //     ->label(__('models.room.is_available'))
                // :
                IconColumn::make('is_available')
                    ->label(__('models.room.is_available'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label(__('models.room.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('models.room.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->searchPlaceholder('Tìm số phòng')
            ->defaultSort('room_number', 'asc')
            ->defaultGroup(
                Group::make('roomtype.name')
                    // ->label(__('models.roomtype.name'))
                    ->titlePrefixedWithLabel(false)
                    ->collapsible()
            )
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }

    // Số lượng phòng trống (của tất cả hotel)
    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::where('is_available', true)->count();
    // }

    // public static function getNavigationBadgeColor(): string|array|null 
    // {
    //     return 'success';
    // }
}
