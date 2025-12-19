<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalResource\Pages;
use App\Filament\Resources\JurnalResource\RelationManagers;
use App\Models\Jurnal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JurnalResource extends Resource
{
    protected static ?string $model = Jurnal::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Monitoring';

    public static function form(Form $form): Form
    {
       return $form->schema([
        Forms\Components\Select::make('siswa_id')->relationship('siswa', 'nama_lengkap')->required()->searchable(),
        Forms\Components\DatePicker::make('tanggal')->required(),
        Forms\Components\RichEditor::make('deskripsi_kegiatan')->required()->columnSpanFull(),
        Forms\Components\Select::make('keterangan')->options(['masuk' => 'Masuk', 'sakit' => 'Sakit', 'izin' => 'Izin'])->required(),
        Forms\Components\Select::make('status')->options(['pending' => 'Pending', 'disetujui' => 'Disetujui', 'revisi' => 'Revisi'])->required(),
        Forms\Components\Textarea::make('catatan_pembimbing')->columnSpanFull(),
        Forms\Components\FileUpload::make('foto_dokumentasi')->image()->directory('jurnal-foto'),
    ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
        Tables\Columns\TextColumn::make('siswa.nama_lengkap')->searchable()->sortable(),
        Tables\Columns\TextColumn::make('tanggal')->date()->sortable(),
        Tables\Columns\TextColumn::make('keterangan')->badge()->color(fn (string $state): string => match ($state) {
            'masuk' => 'success', 'sakit' => 'warning', 'izin' => 'info',
        }),
        Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
            'disetujui' => 'success', 'pending' => 'gray', 'revisi' => 'danger',
        }),
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
            'index' => Pages\ListJurnals::route('/'),
            'create' => Pages\CreateJurnal::route('/create'),
            'edit' => Pages\EditJurnal::route('/{record}/edit'),
        ];
    }
}
