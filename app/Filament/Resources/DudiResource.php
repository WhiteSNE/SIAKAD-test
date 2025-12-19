<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DudiResource\Pages;
use App\Filament\Resources\DudiResource\RelationManagers;
use App\Models\Dudi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DudiResource extends Resource
{
    protected static ?string $model = Dudi::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form->schema([
        Forms\Components\TextInput::make('nama_perusahaan')->required(),
        Forms\Components\TextInput::make('nama_pic')->label('Nama PIC')->required(),
        Forms\Components\TextInput::make('email_perusahaan')->email(),
        Forms\Components\TextInput::make('no_telepon_perusahaan')->tel(),
        Forms\Components\Textarea::make('alamat')->required()->columnSpanFull(),
        Forms\Components\Textarea::make('deskripsi')->columnSpanFull(),
    ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
        Tables\Columns\TextColumn::make('nama_perusahaan')->searchable()->sortable(),
        Tables\Columns\TextColumn::make('nama_pic')->label('PIC')->searchable(),
        Tables\Columns\TextColumn::make('email_perusahaan')->searchable(),
        Tables\Columns\TextColumn::make('no_telepon_perusahaan')->label('Telepon'),
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
            'index' => Pages\ListDudis::route('/'),
            'create' => Pages\CreateDudi::route('/create'),
            'edit' => Pages\EditDudi::route('/{record}/edit'),
        ];
    }
}
