<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                Select::make('role')
                    ->required()
                    ->options([
                        'mahasiswa' => 'Mahasiswa',
                        'dospem' => 'Dosen Pembimbing',
                        'kaprodi' => 'Kepala Program Studi',
                    ])
                    ->reactive(),

                // * Field untuk mahasiswa
                Forms\Components\TextInput::make('nim')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn(callable $get) => $get('role') === 'mahasiswa'),
                Forms\Components\TextInput::make('batch_year')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn(callable $get) => $get('role') === 'mahasiswa'),

                // * Fields for dospem and kaprodi
                Forms\Components\TextInput::make('nidn')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn(callable $get) => in_array($get('role'), ['dospem', 'kaprodi'])),

                // * Field untuk mahasiswa, kaprodi, dan dospem
                Forms\Components\Select::make('gender')
                    ->required()
                    ->options([
                        'laki-laki' => 'Laki - Laki',
                        'perempuan' => 'Perempuan',
                    ])
                    ->visible(fn(callable $get) => in_array($get('role'), ['mahasiswa', 'dospem', 'kaprodi'])),

                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255)
                    ->visible(fn(callable $get) => in_array($get('role'), ['mahasiswa', 'dospem', 'kaprodi'])),
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'department_name')
                    ->required()
                    ->visible(fn(callable $get) => in_array($get('role'), ['mahasiswa', 'dospem', 'kaprodi'])),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nim')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nidn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.department_name')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
