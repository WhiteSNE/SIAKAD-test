<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Filament\Resources\GuruResource\RelationManagers;
use App\Models\Guru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form->schema([
        Forms\Components\Select::make('user_id')
            ->relationship('user', 'name', fn ($query) => $query->where('role', 'guru'))
            ->required()->searchable()->preload(),
        Forms\Components\TextInput::make('NIP')->label('NIP')->required()->unique(ignoreRecord: true),
        Forms\Components\TextInput::make('NAMA')->label('Nama Lengkap')->required(),
        Forms\Components\FileUpload::make('foto_profil')
        ->image()
        ->disk('public')
        ->visibility('public')
        ->directory('guru-foto'),
    ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
        Tables\Columns\ImageColumn::make('foto_profil')
        ->disk('public')
        ->circular(),
        Tables\Columns\TextColumn::make('NAMA')->label('Nama')->searchable()->sortable(),
        Tables\Columns\TextColumn::make('NIP')->label('NIP')->searchable(),
        Tables\Columns\TextColumn::make('user.email')->label('Email')->searchable(),
    ])
    ->actions([
            // Menambahkan Edit dan Delete pada setiap baris data
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(), // Tambahkan ini
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                // Fitur untuk menghapus banyak data sekaligus
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
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }
}
