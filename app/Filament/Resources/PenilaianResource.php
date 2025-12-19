<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianResource\Pages;
use App\Filament\Resources\PenilaianResource\RelationManagers;
use App\Models\Penilaian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenilaianResource extends Resource
{
    protected static ?string $model = Penilaian::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Monitoring';

    public static function form(Form $form): Form
    {
       return $form->schema([
        Forms\Components\Select::make('siswa_id')->relationship('siswa', 'nama_lengkap')->required(),
        Forms\Components\Select::make('guru_id')->relationship('guru', 'NAMA')->required(),
        Forms\Components\TextInput::make('lama_pkl')->placeholder('Contoh: 3 Bulan'),
        Forms\Components\Repeater::make('nilai')->schema([
            Forms\Components\TextInput::make('aspek')->required(),
            Forms\Components\TextInput::make('skor')->numeric()->required()->minValue(0)->maxValue(100),
        ])->columnSpanFull(),
        Forms\Components\TextInput::make('rata_rata')->numeric(),
        Forms\Components\Select::make('status')->options(['belum_dinilai' => 'Belum Dinilai', 'sudah_dinilai' => 'Sudah Dinilai']),
        Forms\Components\Textarea::make('catatan')->columnSpanFull(),
    ]);
    }

    public static function table(Table $table): Table
    {
       return $table->columns([
        Tables\Columns\TextColumn::make('siswa.nama_lengkap')->searchable()->sortable(),
        Tables\Columns\TextColumn::make('guru.NAMA')->label('Penilai')->searchable(),
        Tables\Columns\TextColumn::make('rata_rata')->sortable(),
        Tables\Columns\TextColumn::make('status')->badge(),
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
            'index' => Pages\ListPenilaians::route('/'),
            'create' => Pages\CreatePenilaian::route('/create'),
            'edit' => Pages\EditPenilaian::route('/{record}/edit'),
        ];
    }
}
