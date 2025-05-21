<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = 'Tài khoản';
    protected static ?string $navigationLabel = 'Quản lý tài khoản';
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    TextInput::make('name')
                        ->label(__('models.user.name'))
                        ->required()
                        ->autofocus(),

                    TextInput::make('email')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->regex('/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'),

                    TextInput::make('password')
                        ->label(__('models.user.password'))
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
                ])->columnSpan(2),

                Section::make(null)
                    ->schema([
                        FileUpload::make('avatar')
                            ->label(__('models.user.avatar'))
                            ->directory('avatar')
                            ->image(),

                        DatePicker::make('birth')
                            ->label(__('models.user.birth'))
                            ->nullable(),
    
                        TextInput::make('phone')
                            ->label(__('models.user.phone'))
                            ->tel()
                            ->nullable(),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),

                TextColumn::make('name')
                    ->label(__('models.user.name')),

                ImageColumn::make('avatar')
                    ->placeholder('None')
                    ->circular()
                    ->label(__('models.user.avatar')),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label(__('models.user.phone'))
                    ->placeholder('Chưa cập nhật SĐT'),

                TextColumn::make('created_at')
                    ->label(__('models.user.created_at'))
                    ->date()
                    // ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y'))
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label(__('models.user.updated_at'))
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
            ->defaultSort('id', 'desc')
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