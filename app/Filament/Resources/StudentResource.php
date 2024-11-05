<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Buat user dengan role 'mahasiswa' untuk login mahasiswa
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'mahasiswa',
        ]);

        // Set user_id di tabel students dengan ID dari user yang baru dibuat
        $data['user_id'] = $user->id;

        // Hapus email dan password dari $data untuk mencegah penyimpanan di tabel students
        unset($data['email'], $data['password']);

        return $data;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nim')
                    ->required()
                    ->maxLength(255)
                    ->unique('students', 'nim'),
                Forms\Components\Select::make('gender')
                    ->options([
                        'Laki - Laki' => 'Laki - Laki',
                        'Perempuan' => 'Perempuan',
                    ]),
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'department_name')
                    ->required(),
                Forms\Components\TextInput::make('batch_year')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),

                // Fields for the User model
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique('users', 'email'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nim')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch_year')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
