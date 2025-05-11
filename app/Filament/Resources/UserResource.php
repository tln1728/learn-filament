<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Tên người dùng')
                    ->required()
                    ->autofocus(),

                TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->regex('/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'),

                // TextInput::make('birth')
                //     ->label('Ngày sinh')
                //     ->type('date')
                //     ->nullable(),

                TextInput::make('phone')
                    ->tel()
                    ->regex('/^0[0-9]{9,10}$/')
                    ->label('Số điện thoại')
                    ->nullable(),

                TextInput::make('password')
                    ->label('Mật khẩu')
                    ->password()
                    ->revealable()
                    ->required()
                    ->visibleOn('create'),

                TextInput::make('password_confirmation')
                    ->label('Xác nhận mật khẩu')
                    ->password()
                    ->same('password')
                    ->required()
                    ->visibleOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),

                TextColumn::make('name')
                    ->label('Tên'),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('phone')
                    ->placeholder('Chưa cập nhật SĐT'),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->date()
                    // ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y'))
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật gần nhất')
                    ->dateTooltip()
                    ->formatStateUsing(fn ($state) =>
                        $state->diffForHumans()
                    )
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

            ])
            ->searchPlaceholder('Tìm kiếm ...')
            // ->searchDebounce('1000ms')
            ->searchOnBlur()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}