<?php

namespace App\Filament\Widgets;

use App\Models\Guru; //
use App\Models\Dudi; //
use App\Models\Siswa; //
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Guru', Guru::count()) // Mengambil jumlah dari tabel gurus
                ->description('Jumlah guru pembimbing aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Total Dudi', Dudi::count()) // Mengambil jumlah dari tabel dudis
                ->description('Jumlah mitra industri')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),

            Stat::make('Total Siswa', Siswa::count()) // Mengambil jumlah dari tabel siswas
                ->description('Jumlah siswa PKL terdaftar')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),
        ];
    }
}