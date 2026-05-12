<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $heading = 'Übersicht';

    protected function getStats(): array
    {
        $total    = Vehicle::count();
        $avail    = Vehicle::where('verfuegbar', true)->where('verkauft', false)->count();
        $sold     = Vehicle::where('verkauft', true)->count();
        $unread   = ContactMessage::where('gelesen', false)->count();

        return [
            Stat::make('Fahrzeuge gesamt', $total)
                ->description('Im Bestand')
                ->descriptionIcon('heroicon-o-truck')
                ->color('primary'),

            Stat::make('Verfügbar', $avail)
                ->description('Aktiv online')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('success'),

            Stat::make('Verkauft', $sold)
                ->description('Bereits abgewickelt')
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('gray'),

            Stat::make('Ungelesen', $unread)
                ->description('Anfragen warten')
                ->descriptionIcon('heroicon-o-envelope')
                ->color($unread > 0 ? 'warning' : 'gray'),
        ];
    }
}
