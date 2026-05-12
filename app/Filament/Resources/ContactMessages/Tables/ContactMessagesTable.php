<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('gelesen')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-s-envelope')
                    ->trueColor('gray')
                    ->falseColor('warning'),
                TextColumn::make('created_at')
                    ->label('Empfangen')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('telefon')
                    ->toggleable(),
                TextColumn::make('vehicle.titel')
                    ->label('Fahrzeug')
                    ->limit(40)
                    ->placeholder('— allgemein —'),
                TextColumn::make('nachricht')
                    ->limit(80)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('gelesen')
                    ->label('Gelesen')
                    ->placeholder('Alle')
                    ->trueLabel('Nur gelesen')
                    ->falseLabel('Nur ungelesen'),
            ])
            ->recordActions([
                Action::make('toggle_gelesen')
                    ->label(fn ($record) => $record->gelesen ? 'Ungelesen' : 'Gelesen')
                    ->icon('heroicon-o-envelope')
                    ->action(fn ($record) => $record->update(['gelesen' => ! $record->gelesen])),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('mark_read')
                        ->label('Als gelesen markieren')
                        ->icon('heroicon-o-envelope-open')
                        ->action(fn (Collection $records) => $records->each->update(['gelesen' => true])),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
