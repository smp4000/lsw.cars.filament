<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Grunddaten')
                    ->columns(2)
                    ->schema([
                        TextInput::make('marke')->required()->maxLength(80),
                        TextInput::make('modell')->required()->maxLength(120),
                        TextInput::make('titel')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull()
                            ->placeholder('z. B. BMW 320d Touring M-Paket'),
                        Textarea::make('beschreibung')
                            ->rows(5)
                            ->columnSpanFull(),
                        TextInput::make('preis')
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->minValue(0)
                            ->step(100),
                        Select::make('karosserie')
                            ->options([
                                'Limousine'  => 'Limousine',
                                'Kombi'      => 'Kombi',
                                'Kleinwagen' => 'Kleinwagen',
                                'SUV'        => 'SUV',
                                'Coupé'      => 'Coupé',
                                'Cabrio'     => 'Cabrio',
                                'Van'        => 'Van',
                                'Pickup'     => 'Pickup',
                                'Sonstige'   => 'Sonstige',
                            ]),
                    ]),

                Section::make('Technische Daten')
                    ->columns(2)
                    ->schema([
                        TextInput::make('erstzulassung')
                            ->placeholder('JJJJ-MM')
                            ->maxLength(10),
                        TextInput::make('kilometerstand')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('km')
                            ->default(0),
                        Select::make('kraftstoff')->options([
                            'Benzin'  => 'Benzin',
                            'Diesel'  => 'Diesel',
                            'Elektro' => 'Elektro',
                            'Hybrid'  => 'Hybrid',
                            'LPG'     => 'LPG',
                            'CNG'     => 'CNG',
                        ]),
                        Select::make('getriebe')->options([
                            'Automatik'      => 'Automatik',
                            'Schaltgetriebe' => 'Schaltgetriebe',
                        ]),
                        TextInput::make('leistung_kw')->numeric()->minValue(0)->suffix('kW')->label('Leistung (kW)'),
                        TextInput::make('leistung_ps')->numeric()->minValue(0)->suffix('PS')->label('Leistung (PS)'),
                        TextInput::make('hubraum')->numeric()->minValue(0)->suffix('cm³'),
                        TextInput::make('farbe')->maxLength(60),
                        TextInput::make('tueren')->numeric()->minValue(0)->maxValue(9)->label('Türen'),
                        TextInput::make('sitze')->numeric()->minValue(0)->maxValue(9),
                        Select::make('zustand')
                            ->options([
                                'Neu'           => 'Neu',
                                'Gebraucht'     => 'Gebraucht',
                                'Jahreswagen'   => 'Jahreswagen',
                                'Vorführwagen'  => 'Vorführwagen',
                                'Oldtimer'      => 'Oldtimer',
                            ])->default('Gebraucht'),
                        TextInput::make('hu')->label('HU bis')->placeholder('MM/JJJJ')->maxLength(10),
                        TextInput::make('anzahl_halter')->numeric()->minValue(0)->label('Anzahl Halter'),
                    ]),

                Section::make('Ausstattung')
                    ->columns(4)
                    ->schema([
                        Checkbox::make('klimaanlage'),
                        Checkbox::make('navigation'),
                        Checkbox::make('sitzheizung'),
                        Checkbox::make('einparkhilfe'),
                        Checkbox::make('tempomat'),
                        Checkbox::make('anhaengerkupplung')->label('Anhängerkupplung'),
                        Checkbox::make('ledersitze'),
                        Checkbox::make('schiebedach'),
                    ]),

                Section::make('Sichtbarkeit & Status')
                    ->columns(2)
                    ->schema([
                        Toggle::make('verfuegbar')
                            ->label('Auf Webseite anzeigen')
                            ->default(true)
                            ->inline(false),
                        Toggle::make('verkauft')
                            ->label('Bereits verkauft')
                            ->inline(false),
                    ]),
            ]);
    }
}
