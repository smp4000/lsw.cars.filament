<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Models\EquipmentCategory;
use App\Services\DatVxsParser;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Fahrzeug')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Grunddaten')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make(2)->schema([
                                    Select::make('fahrzeugtyp')->options([
                                        'PKW' => 'PKW', 'LKW' => 'LKW',
                                        'Wohnmobil' => 'Wohnmobil', 'Motorrad' => 'Motorrad',
                                    ])->default('PKW'),
                                    Select::make('zustand')->options([
                                        'Neu' => 'Neu', 'Gebraucht' => 'Gebraucht',
                                        'Jahreswagen' => 'Jahreswagen', 'Vorführwagen' => 'Vorführwagen',
                                        'Oldtimer' => 'Oldtimer',
                                    ])->default('Gebraucht'),
                                    TextInput::make('marke')->required()->maxLength(80),
                                    TextInput::make('modell')->required()->maxLength(120),
                                    TextInput::make('titel')
                                        ->required()
                                        ->maxLength(200)
                                        ->columnSpanFull()
                                        ->placeholder('z. B. BMW 320d Touring M-Paket'),
                                    TextInput::make('baureihe')->maxLength(80),
                                    TextInput::make('ausstattungslinie')->maxLength(80),
                                    TextInput::make('preis')
                                        ->required()
                                        ->numeric()
                                        ->prefix('€')
                                        ->minValue(0)
                                        ->step(100),
                                    Select::make('mwst')->options([
                                        'MwSt. ausweisbar' => 'MwSt. ausweisbar',
                                        'Differenzbesteuert' => 'Differenzbesteuert',
                                    ])->label('MwSt.'),
                                    Select::make('karosserie')->options([
                                        'Limousine' => 'Limousine', 'Kombi' => 'Kombi',
                                        'Kleinwagen' => 'Kleinwagen', 'SUV' => 'SUV',
                                        'Coupé' => 'Coupé', 'Cabrio' => 'Cabrio',
                                        'Van' => 'Van', 'Pickup' => 'Pickup',
                                        'Sonstige' => 'Sonstige',
                                    ]),
                                    TextInput::make('kilometerstand')->numeric()->minValue(0)->suffix('km')->default(0),
                                    Toggle::make('unfallfrei')->label('Unfallfrei')->default(true)->inline(false),
                                    Textarea::make('beschreibung')->rows(5)->columnSpanFull(),
                                ]),
                            ]),

                        Tab::make('Technik')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('erstzulassung')->placeholder('JJJJ-MM')->maxLength(10),
                                    TextInput::make('produktionsdatum')->placeholder('JJJJ-MM')->maxLength(10)->label('Produktionsdatum'),
                                    TextInput::make('anzahl_halter')->numeric()->minValue(0)->label('Halter'),
                                    Select::make('kraftstoff')->options([
                                        'Benzin' => 'Benzin', 'Diesel' => 'Diesel', 'Elektro' => 'Elektro',
                                        'Hybrid' => 'Hybrid', 'LPG' => 'LPG', 'CNG' => 'CNG',
                                    ]),
                                    Select::make('getriebe')->options([
                                        'Automatik' => 'Automatik', 'Schaltgetriebe' => 'Schaltgetriebe',
                                    ]),
                                    Select::make('antriebsart')->options([
                                        'Frontantrieb' => 'Frontantrieb', 'Heckantrieb' => 'Heckantrieb',
                                        'Allradantrieb' => 'Allradantrieb',
                                    ]),
                                    TextInput::make('leistung_kw')->numeric()->minValue(0)->suffix('kW')->label('Leistung (kW)'),
                                    TextInput::make('leistung_ps')->numeric()->minValue(0)->suffix('PS')->label('Leistung (PS)'),
                                    TextInput::make('hubraum')->numeric()->minValue(0)->suffix('cm³'),
                                    TextInput::make('zylinder')->numeric()->minValue(0)->maxValue(16),
                                    TextInput::make('tankgroesse')->numeric()->minValue(0)->suffix('l')->label('Tankgröße'),
                                    TextInput::make('gewicht')->numeric()->minValue(0)->suffix('kg'),
                                    TextInput::make('anhaengelast_gebremst')->numeric()->minValue(0)->suffix('kg')->label('Anh.-Last gebr.'),
                                    TextInput::make('anhaengelast_ungebremst')->numeric()->minValue(0)->suffix('kg')->label('Anh.-Last ungebr.'),
                                ]),
                            ]),

                        Tab::make('Verbrauch & Emissionen')
                            ->icon('heroicon-o-fire')
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('energieverbrauch')->numeric()->minValue(0)->suffix('l/100km')->label('Verbrauch (komb.)'),
                                    TextInput::make('verbrauch_innerorts')->numeric()->minValue(0)->suffix('l/100km')->label('Verbrauch (innerorts)'),
                                    TextInput::make('verbrauch_ausserorts')->numeric()->minValue(0)->suffix('l/100km')->label('Verbrauch (außerorts)'),
                                    TextInput::make('co2_emissionen')->numeric()->minValue(0)->suffix('g/km')->label('CO₂'),
                                    Select::make('co2_klasse')->options([
                                        'A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D',
                                        'E' => 'E', 'F' => 'F', 'G' => 'G',
                                    ])->label('CO₂-Klasse'),
                                    TextInput::make('energiekosten')->numeric()->minValue(0)->prefix('€')->label('Energiekosten/Jahr'),
                                    Select::make('schadstoffklasse')->options([
                                        'Euro 1' => 'Euro 1', 'Euro 2' => 'Euro 2', 'Euro 3' => 'Euro 3',
                                        'Euro 4' => 'Euro 4', 'Euro 5' => 'Euro 5', 'Euro 6' => 'Euro 6',
                                        'Euro 6d' => 'Euro 6d', 'Euro 6d-TEMP' => 'Euro 6d-TEMP',
                                    ]),
                                    Select::make('umweltplakette')->options([
                                        '4 (Grün)' => '4 (Grün)', '3 (Gelb)' => '3 (Gelb)',
                                        '2 (Rot)' => '2 (Rot)', 'Keine' => 'Keine',
                                    ]),
                                ]),
                            ]),

                        Tab::make('Optik & Innen')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('farbe')->maxLength(60),
                                    TextInput::make('farbe_hersteller')->maxLength(120)->label('Farbe (Hersteller)'),
                                    TextInput::make('lackierung')->maxLength(30)->label('Lackierung'),
                                    Toggle::make('metallic')->label('Metallic')->inline(false),
                                    TextInput::make('innenausstattung')->maxLength(80),
                                    TextInput::make('tueren')->numeric()->minValue(0)->maxValue(9)->label('Türen'),
                                    Toggle::make('schiebetuer')->label('Schiebetür')->inline(false),
                                    TextInput::make('sitze')->numeric()->minValue(0)->maxValue(9),
                                    TextInput::make('airbags')->maxLength(120),
                                    TextInput::make('klimatisierung')->maxLength(60),
                                    TextInput::make('einparkhilfe_detail')->maxLength(60)->label('Einparkhilfe (Detail)'),
                                ]),
                            ]),

                        Tab::make('Ausstattung')
                            ->icon('heroicon-o-check-circle')
                            ->schema([
                                ...self::equipmentSections(),
                                Grid::make(1)->schema([
                                    Textarea::make('ausstattung_sonder')
                                        ->label('Sonderausstattung')
                                        ->rows(8)
                                        ->placeholder("15490 Außenspiegel mit Totwinkel-Assistent\n29500 Sitzheizung vorn"),
                                    Textarea::make('ausstattung_serie')
                                        ->label('Serienausstattung')
                                        ->rows(8),
                                ]),
                            ]),

                        Tab::make('Historie')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('anzahl_halter')->numeric()->minValue(0)->label('Vorbesitzer'),
                                    Toggle::make('unfallschaden')->label('Unfallschaden')->inline(false),
                                    Toggle::make('verkehrstauglich')->label('Verkehrstüchtig')->default(true)->inline(false),
                                    Toggle::make('nichtraucher')->label('Nichtraucherfahrzeug')->inline(false),
                                    Toggle::make('scheckheftgepflegt')->label('Scheckheftgepflegt')->inline(false),
                                    TextInput::make('garantie')->maxLength(80)->label('Garantie'),
                                ]),
                            ]),

                        Tab::make('Identifikation')
                            ->icon('heroicon-o-finger-print')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('fahrgestellnummer')
                                        ->label('Fahrgestellnummer (VIN)')
                                        ->maxLength(17)
                                        ->placeholder('WXXXXXXXXXXXXXX'),
                                    TextInput::make('hsn')->label('HSN')->maxLength(10),
                                    TextInput::make('tsn')->label('TSN')->maxLength(10),
                                    TextInput::make('dat_ecode')
                                        ->label('DAT-ECode')
                                        ->maxLength(20)
                                        ->disabled()
                                        ->dehydrated(),
                                    TextInput::make('herkunft')->maxLength(80),
                                    TextInput::make('hu')->label('HU bis')->placeholder('MM/JJJJ')->maxLength(10),
                                    TextInput::make('letzte_wartung_datum')->maxLength(10)->placeholder('MM/JJJJ')->label('Letzte Wartung'),
                                    TextInput::make('letzte_wartung_km')->numeric()->minValue(0)->suffix('km')->label('Letzte Wartung (km)'),
                                ]),
                            ]),

                        Tab::make('Status & Import')
                            ->icon('heroicon-o-arrow-path')
                            ->schema([
                                Grid::make(2)->schema([
                                    Toggle::make('verfuegbar')
                                        ->label('Auf Webseite anzeigen')
                                        ->default(true)
                                        ->inline(false),
                                    Toggle::make('verkauft')
                                        ->label('Bereits verkauft')
                                        ->inline(false),
                                ]),
                                Grid::make(1)->schema([
                                    FileUpload::make('dat_xml_import')
                                        ->label('DAT VXS-Datei (.xml)')
                                        ->acceptedFileTypes(['text/xml', 'application/xml'])
                                        ->directory('dat-imports')
                                        ->visibility('private')
                                        ->maxSize(2048)
                                        ->reactive()
                                        ->afterStateUpdated(function (Set $set, $state) {
                                            if (! $state) return;

                                            try {
                                                $path = is_string($state)
                                                    ? storage_path('app/private/' . $state)
                                                    : $state->getRealPath();
                                                $xml = file_get_contents($path);
                                                $data = DatVxsParser::parse($xml);

                                                foreach ($data as $key => $value) {
                                                    $set($key, $value);
                                                }

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
                            ]),
                    ]),
            ]);
    }

    private static function equipmentSections(): array
    {
        $sections = [];

        foreach (EquipmentCategory::orderBy('sortierung')->get() as $cat) {
            $options = $cat->items->pluck('name', 'id')->toArray();
            if (empty($options)) continue;

            $sections[] = \Filament\Schemas\Components\Section::make($cat->name)
                ->collapsible()
                ->collapsed()
                ->schema([
                    CheckboxList::make('equipment_' . $cat->id)
                        ->label('')
                        ->options($options)
                        ->columns(4)
                        ->relationship(
                            'equipment',
                            'name',
                            fn ($query) => $query->where('category_id', $cat->id)
                        ),
                ]);
        }

        return $sections;
    }
}
