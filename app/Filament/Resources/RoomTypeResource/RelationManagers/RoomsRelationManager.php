<?php

namespace App\Filament\Resources\RoomTypeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;

class RoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';
    protected static ?string $modelLabel = 'phòng';
    protected static ?string $title = 'Phòng cùng loại';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('hotel_name')
                    ->label(__('models.room.hotel_id'))
                    ->formatStateUsing(fn() => $this->getOwnerRecord()->loadMissing('hotel:id,name')->hotel->name)
                    ->disabled(),

                TextInput::make('room_type_id')
                    ->label(__('models.room.room_type_id'))
                    ->formatStateUsing(fn() => $this->getOwnerRecord()->name)
                    ->disabled(),

                Hidden::make('hotel_id')->default($this->getOwnerRecord()->hotel_id),
                
                TextInput::make('room_number')
                    ->label(__('models.room.room_number'))
                    ->maxLength(255)
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule) => $rule->where('hotel_id', $this->getOwnerRecord()->hotel_id)
                        // or modifyRuleUsing: fn (Unique $rule) => $rule->where('room_type_id', $this->getOwnerRecord()->id)
                    )
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('room_number')
            ->columns([
                TextColumn::make('room_number')
                    ->label(__('models.room.room_number')),

                IconColumn::make('is_available')
                    ->label(__('models.room.is_available'))
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
                    ->iconButton(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tạo phòng')
                    ->icon('heroicon-o-plus-circle')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
