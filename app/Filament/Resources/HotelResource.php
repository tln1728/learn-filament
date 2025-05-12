<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelResource\Pages;
use App\Filament\Resources\HotelResource\RelationManagers;
use App\Models\Hotel;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;
    protected static ?string $modelLabel = 'Khách sạn';
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make('Thông tin cơ bản')
                        ->schema([
                            TextInput::make('name')
                                ->label(__('models.hotel.name'))
                                ->required()
                                ->maxLength(255),

                            Textarea::make('description')
                                ->label(__('models.hotel.description'))
                                ->columnSpanFull(),
                        ])->columns(1),

                    Section::make('Vị trí khách sạn')
                        ->schema([
                            Group::make([
                                TextInput::make('address')
                                ->label(__('models.hotel.address'))
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(3),

                                TextInput::make('zip_code')
                                    ->label(__('models.hotel.zip_code'))
                                    ->maxLength(255),
                            ])->columns(4)->columnSpanFull(),

                            Select::make('city')
                                ->label(__('models.hotel.city'))
                                ->required()
                                ->options([
                                    'Hà Nội',
                                    'TP Hồ Chí Minh',
                                    'bla bla',
                                ])
                                ->searchable(),

                            Select::make('country')
                                ->label(__('models.hotel.country'))
                                ->required()
                                ->options([
                                    'Hà Nội',
                                    'TP Hồ Chí Minh',
                                    'bla bla',
                                ])
                                ->searchable(),
                        ])->columns(2),
                ])->columnSpan(2),

                Section::make('Thông tin liên hệ')
                    ->schema([
                        TextInput::make('phone')
                            ->label(__('models.hotel.phone'))
                            ->prefixIcon('heroicon-o-phone')
                            ->tel()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label(__('models.hotel.email'))
                            ->prefixIcon('heroicon-o-at-symbol')
                            ->email()
                            ->unique()
                            ->maxLength(255),

                        TextInput::make('website')
                            ->label(__('models.hotel.website'))
                            ->prefixIcon('heroicon-o-link')
                            ->url()
                            ->activeUrl()
                            ->maxLength(255),
                    ])->columnSpan(1)->collapsible(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('models.hotel.name'))
                    ->searchable(),

                TextColumn::make('address')
                    ->label(__('models.hotel.address'))
                    ->searchable(),

                TextColumn::make('city')
                    ->label(__('models.hotel.city'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('country')
                    ->label(__('models.hotel.country'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('zip_code')
                    ->label(__('models.hotel.zip_code'))
                    ->searchable(),

                TextColumn::make('email')
                    ->label(__('models.hotel.email'))
                    ->searchable(),

                TextColumn::make('phone')
                    ->label(__('models.hotel.phone'))
                    ->placeholder('None')
                    ->searchable(),

                TextColumn::make('website')
                    ->label(__('models.hotel.website'))
                    ->url(function (Hotel $record): string {
                        return $record->website ?? '#';
                    })
                    ->openUrlInNewTab()
                    ->placeholder('None')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'view' => Pages\ViewHotel::route('/{record}'),
            'edit' => Pages\EditHotel::route('/{record}/edit'),
        ];
    }
}
