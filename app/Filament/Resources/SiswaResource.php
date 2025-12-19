<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Psy\Util\Str;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Siswa';

    public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\Section::make('Biodata')->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name', fn ($query) => $query->where('role', 'siswa'))
                ->required()->searchable()->preload(),
            Forms\Components\TextInput::make('nisn')->label('NISN')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('nama_lengkap')->required(),
            Forms\Components\Select::make('jenis_kelamin')->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])->required(),
            Forms\Components\FileUpload::make('foto_profil')
            ->image()
            ->disk('public')
            ->visibility('public')
            ->directory('siswa-foto'),
        ])->columns(2),
        Forms\Components\Section::make('Akademik & Penempatan')->schema([
            Forms\Components\Select::make('jurusan_id')->relationship('jurusan', 'nama_jurusan')->required(),
            Forms\Components\Select::make('kelas_id')->relationship('kelas', 'nama_kelas')->required(),
            Forms\Components\Select::make('guru_id')->relationship('guru', 'NAMA')->label('Guru Pembimbing'),
            Forms\Components\Select::make('dudi_id')->relationship('dudi', 'nama_perusahaan')->label('Tempat PKL'),
        ])->columns(2),
    ]);
}

    public static function table(Table $table): Table
    {
        return $table->columns([
        Tables\Columns\ImageColumn::make('foto_profil')
        ->disk('public')
        ->circular(),
        Tables\Columns\TextColumn::make('nama_lengkap')->searchable()->sortable(),
        Tables\Columns\TextColumn::make('nisn')->label('NISN')->searchable(),
        Tables\Columns\TextColumn::make('jurusan.nama_jurusan')->badge(),
        Tables\Columns\TextColumn::make('kelas.nama_kelas'),
        Tables\Columns\TextColumn::make('dudi.nama_perusahaan')->label('Tempat PKL')->placeholder('Belum ada'),
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
