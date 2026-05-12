<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Nachricht')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->disabled(),
                    TextInput::make('email')->disabled(),
                    TextInput::make('telefon')->disabled(),
                    TextInput::make('vehicle.titel')
                        ->label('Fahrzeug')
                        ->disabled(),
                    Textarea::make('nachricht')
                        ->rows(8)
                        ->disabled()
                        ->columnSpanFull(),
                    Toggle::make('gelesen')
                        ->label('Als gelesen markieren')
                        ->inline(false)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
