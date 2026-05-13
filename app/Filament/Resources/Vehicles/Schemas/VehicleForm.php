<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Services\DatVxsParser;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('DAT Import')
                    ->description('XML-Datei aus SilverDAT hochladen – alle Felder werden automatisch befüllt. Alternativ alles per Hand eingeben.')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->collapsed()
                    ->schema([
                        FileUpload::make('dat_xml_import')
                            ->label('DAT VXS-Datei (.xml)')
                            ->acceptedFileTypes(['text/xml', 'application/xml'])
                            ->directory('dat-imports')
                            ->visibility('private')
                            ->maxSize(2048)
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, ?TemporaryUploadedFile $state) {
                                if (! $state) return;

                                try {
                                    $xml = file_get_contents($state->getRealPath());
                                    $data = DatVxsParser::parse($xml);

                                    $set('fahrgestellnummer', $data['fahrgestellnummer'] ?? null);
                                    $set('dat_ecode', $data['dat_ecode'] ?? null);
                                    $set('marke', $data['marke'] ?? null);
                                    $set('modell', $data['modell'] ?? null);
                                    $set('titel', $data['titel'] ?? null);
                                    $set('kraftstoff', $data['kraftstoff'] ?? null);
                                    $set('getriebe', $data['getriebe'] ?? null);
                                    $set('leistung_kw', $data['leistung_kw'] ?? null);
                                    $set('leistung_ps', $data['leistung_ps'] ?? null);
                                    $set('hubraum', $data['hubraum'] ?? null);
                                    $set('farbe', $data['farbe'] ?? null);
                                    $set('tueren', $data['tueren'] ?? null);
                                    $set('karosserie', $data['karosserie'] ?? null);
                                    $set('zustand', $data['zustand'] ?? 'Gebraucht');
                                    $set('klimaanlage', $data['klimaanlage'] ?? false);
                                    $set('navigation', $data['navigation'] ?? false);
                                    $set('sitzheizung', $data['sitzheizung'] ?? false);
                                    $set('einparkhilfe', $data['einparkhilfe'] ?? false);
                                    $set('tempomat', $data['tempomat'] ?? false);
                                    $set('anhaengerkupplung', $data['anhaengerkupplung'] ?? false);
                                    $set('ledersitze', $data['ledersitze'] ?? false);
                                    $set('schiebedach', $data['schiebedach'] ?? false);
                                    $set('ausstattung_serie', $data['ausstattung_serie'] ?? []);
                                    $set('ausstattung_sonder', $data['ausstattung_sonder'] ?? []);

                                    Notification::make()
                                        ->title('DAT-Daten importiert')
                                        ->body($data['titel'] . ' – bitte Felder prüfen und ergänzen (Preis, KM, EZ, etc.)')
                                        ->success()
                                        ->send();
                                } catch (\Throwable $e) {
                                    Notification::make()
                                        ->title('Import fehlgeschlagen')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            })
                            ->dehydrated(false),
                    ]),

                Section::make('Grunddaten')
                    ->columns(2)
                    ->schema([
                        TextInput::make('fahrgestellnummer')
                            ->label('Fahrgestellnummer (VIN)')
                            ->maxLength(17)
                            ->placeholder('WXXXXXXXXXXXXXX'),
                        TextInput::make('dat_ecode')
                            ->label('DAT-ECode')
                            ->maxLength(20)
                            ->disabled()
                            ->dehydrated(),
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

                Section::make('Ausstattung (Schnellauswahl)')
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

                Section::make('Sonderausstattung (DAT)')
                    ->description('Wird automatisch durch den DAT-Import befüllt. Einträge können manuell hinzugefügt oder entfernt werden.')
                    ->collapsed()
                    ->schema([
                        Repeater::make('ausstattung_sonder')
                            ->label('')
                            ->schema([
                                TextInput::make('description')
                                    ->label('Bezeichnung')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(1)
                            ->addActionLabel('Ausstattung hinzufügen')
                            ->collapsible()
                            ->defaultItems(0)
                            ->reorderable(false),
                    ]),

                Section::make('Serienausstattung (DAT)')
                    ->description('Automatisch aus der DAT-Datei. Wird normalerweise nicht manuell geändert.')
                    ->collapsed()
                    ->schema([
                        Repeater::make('ausstattung_serie')
                            ->label('')
                            ->schema([
                                TextInput::make('description')
                                    ->label('Bezeichnung')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(1)
                            ->addActionLabel('Serienausstattung hinzufügen')
                            ->collapsible()
                            ->defaultItems(0)
                            ->reorderable(false),
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
