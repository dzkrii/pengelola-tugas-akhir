<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;

class MahasiswaResource extends UserResource
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?string $model = User::class;

    public static function getNavigationLabel(): string
    {
        return 'Mahasiswa';
    }

    public static function getNavigationSort(): ?int
    {
        return 1; // Urutan dalam navigasi
    }

    public static function query(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('role', 'mahasiswa'); // Filter hanya untuk mahasiswa
    }
}
