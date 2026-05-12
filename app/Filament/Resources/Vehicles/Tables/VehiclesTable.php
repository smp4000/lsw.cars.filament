<?php

namespace App\Filament\Resources\Vehicles\Tables;

use App\Models\Vehicle;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titel')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(60),
                TextColumn::make('marke')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('preis')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('kilometerstand')
                    ->label('Km')
                    ->numeric(thousandsSeparator: '.')
                    ->suffix(' km')
                    ->sortable(),
                TextColumn::make('erstzulassung')
                    ->label('EZ')
                    ->sortable(),
                TextColumn::make('images_count')
                    ->label('Bilder')
                    ->counts('images')
                    ->badge()
                    ->color('gray'),
                IconColumn::make('verfuegbar')
                    ->label('Verfügbar')
                    ->boolean(),
                IconColumn::make('verkauft')
                    ->label('Verkauft')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Erstellt')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('marke')
                    ->options(fn () => Vehicle::query()
                        ->distinct()
                        ->orderBy('marke')
                        ->pluck('marke', 'marke')
                        ->toArray()),
                SelectFilter::make('kraftstoff')
                    ->options([
                        'Benzin'  => 'Benzin',
                        'Diesel'  => 'Diesel',
                        'Elektro' => 'Elektro',
                        'Hybrid'  => 'Hybrid',
                    ]),
                TernaryFilter::make('verfuegbar')->label('Verfügbar'),
                TernaryFilter::make('verkauft')->label('Verkauft'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
