<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $modelLabel = 'Dịch vụ';
    protected static ?string $navigationLabel = 'Quản lý dịch vụ';
    protected static ?string $navigationIcon = 'heroicon-o-bell';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Select::make('hotel_id')
                        ->label(__('models.service.hotel_id'))
                        ->relationship('hotel', 'name')
                        ->native(false)
                        ->placeholder('Chọn khách sạn')
                        ->required(),

                    TextInput::make('name')
                        ->label(__('models.service.name'))
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn (Unique $rule, $get) => 
                                $rule->where('hotel_id', $get('hotel_id')))
                        ->maxLength(25)
                        ->required(),

                    TextInput::make('price')
                        ->label(__('models.service.price'))
                        ->prefix('VND')
                        ->numeric()
                        ->minValue(0)
                        ->mask(RawJs::make('$money($input)'))
                        ->stripCharacters(',')
                        ->required(),
                ])->columnSpan(2),

                Textarea::make('description')
                    ->label(__('models.service.description'))
                    ->maxLength(255)
                    ->columnSpan(2)
                    ->rows(9),

                FileUpload::make('image')
                    ->label(__('models.service.image'))
                    ->image()
                    ->maxSize('4048')
                    ->directory('service_img')
                    ->columnSpan(2),
            ])->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('models.service.image'))
                    ->size(100),

                TextColumn::make('name')
                    ->label(__('models.service.name'))
                    ->searchable(),

                TextColumn::make('price')
                    ->label(__('models.service.price'))
                    ->money('VND')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('models.service.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('models.service.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
