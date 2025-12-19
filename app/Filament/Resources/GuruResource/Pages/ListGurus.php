<?php

namespace App\Filament\Resources\GuruResource\Pages;

use App\Filament\Resources\GuruResource;
use App\Imports\GuruImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class ListGurus extends ListRecords
{
    protected static string $resource = GuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
            Actions\Action::make('importGuru')
                ->label('Import Guru')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('attachment')
                        ->label('File Excel Guru')
                        ->disk('public')
                        ->directory('temp-imports')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $file = storage_path('app/public/' . $data['attachment']);
                    
                    try {
                        Excel::import(new GuruImport, $file);
                        
                        Notification::make()
                            ->title('Import Berhasil')
                            ->body('Data guru dan akun user berhasil dibuat.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import Gagal')
                            ->body('Kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}