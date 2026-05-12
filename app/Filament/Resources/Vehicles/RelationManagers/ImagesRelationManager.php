<?php

namespace App\Filament\Resources\Vehicles\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Bilder';

    protected static ?string $recordTitleAttribute = 'dateiname';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            FileUpload::make('dateiname')
                ->label('Bild hochladen')
                ->image()
                ->disk('public')
                ->directory('vehicles')
                ->imageEditor()
                ->maxSize(10240)
                ->required(),
            TextInput::make('sortierung')
                ->numeric()
                ->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('dateiname')
                    ->label('Vorschau')
                    ->disk('public')
                    ->square()
                    ->height(80),
                TextColumn::make('dateiname')
                    ->label('Datei / URL')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('sortierung')
                    ->sortable(),
            ])
            ->defaultSort('sortierung')
            ->reorderable('sortierung')
            ->headerActions([
                CreateAction::make()
                    ->label('Bild hinzufügen'),
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
