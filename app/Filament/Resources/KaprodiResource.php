<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaprodiResource\Pages;
use App\Filament\Resources\KaprodiResource\RelationManagers;
use App\Models\Kaprodi;
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

class KaprodiResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Kaprodi'; // Label menu
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap'; // Ikon menu
    protected static ?string $navigationGroup = 'Manajemen Pengguna'; // Grup navigasi
    protected static ?int $navigationSort = 1; // Urutan menu

    public static function query(Builder $query): Builder
    {
        // Filter hanya data dengan role kaprodi
        return $query->where('role', 'mahasiswa');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nidn')
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
                Forms\Components\Select::make('gender')
                    ->required()
                    ->options([
                        'laki-laki' => 'Laki - Laki',
                        'perempuan' => 'Perempuan',
                    ]),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'department_name')
                    ->required(),
                Select::make('role')
                    ->required()
                    ->options([
                        'mahasiswa' => 'Mahasiswa',
                        'dospem' => 'Dosen Pembimbing',
                        'kaprodi' => 'Kepala Program Studi',
                    ])
                    ->default('kaprodi')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('role', 'kaprodi'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
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
            'index' => Pages\ListKaprodis::route('/'),
            'create' => Pages\CreateKaprodi::route('/create'),
            'edit' => Pages\EditKaprodi::route('/{record}/edit'),
        ];
    }
}
