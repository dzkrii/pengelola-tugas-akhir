<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupervisorResource\Pages;
use App\Filament\Resources\SupervisorResource\RelationManagers;
use App\Models\Supervisor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupervisorResource extends Resource
{
    protected static ?string $model = Supervisor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Manajemen Tugas Akhir';
    protected static ?int $navigationSort = 5;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('title_id')
                    ->label('Judul Tugas Akhir')
                    ->relationship('title', 'title')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Dosen Pembimbing')
                    ->relationship('user', 'name', fn($query) => $query->where('role', 'dospem'))
                    ->required(),
                Forms\Components\Radio::make('role')
                    ->label('Role Pembimbing')
                    ->options([
                        'pembimbing1' => 'Pembimbing 1',
                        'pembimbing2' => 'Pembimbing 2',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title.title')->label('Judul Tugas Akhir'),
                Tables\Columns\TextColumn::make('user.name')->label('Dosen Pembimbing'),
                Tables\Columns\TextColumn::make('role')->label('Role'),
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
            'index' => Pages\ListSupervisors::route('/'),
            'create' => Pages\CreateSupervisor::route('/create'),
            'edit' => Pages\EditSupervisor::route('/{record}/edit'),
        ];
    }
}
