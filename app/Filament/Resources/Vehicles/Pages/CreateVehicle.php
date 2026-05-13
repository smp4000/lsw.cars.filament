<?php

namespace App\Filament\Resources\Vehicles\Pages;

use App\Filament\Resources\Vehicles\VehicleResource;
use App\Models\EquipmentCategory;
use App\Models\VehicleImage;
use App\Services\DatVxsParser;
use App\Services\EquipmentSync;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard\Step;

class CreateVehicle extends CreateRecord
{
    use HasWizard;

    protected static string $resource = VehicleResource::class;

    public function getSteps(): array
    {
        return [
            Step::make('Import')
                ->icon('heroicon-o-arrow-down-tray')
                ->description('Optional: DAT-Daten vorab laden')
                ->schema([
                    FileUpload::make('dat_xml_import')
                        ->label('DAT VXS-Datei (.xml)')
                        ->helperText('Optional: XML aus SilverDAT 3 hochladen, um Felder automatisch zu befüllen. Oder einfach auf "Weiter" klicken für manuelle Eingabe.')
                        ->acceptedFileTypes(['text/xml', 'application/xml'])
                        ->directory('dat-imports')
                        ->visibility('private')
                        ->maxSize(2048)
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, $state) {
                            if (! $state) return;
                            $this->importDatXml($set, $state);
                        })
                        ->dehydrated(false),
                ]),

            Step::make('Fahrzeug & Zustand')
                ->icon('heroicon-o-truck')
                ->description('Typ, Zustand, Identifikation')
                ->schema([
                    Section::make('Fahrzeugtyp')->schema([
                        Grid::make(3)->schema([
                            Select::make('fahrzeugtyp')->options([
                                'PKW' => 'PKW', 'LKW' => 'LKW',
                                'Wohnmobil' => 'Wohnmobil', 'Motorrad' => 'Motorrad',
                            ])->default('PKW'),
                            Select::make('zustand')->options([
                                'Neu' => 'Neu', 'Gebraucht' => 'Gebraucht',
                                'Jahreswagen' => 'Jahreswagen', 'Vorführwagen' => 'Vorführwagen',
                                'Oldtimer' => 'Oldtimer',
                            ])->default('Gebraucht')->required(),
                            Select::make('karosserie')->options([
                                'Limousine' => 'Limousine', 'Kombi' => 'Kombi',
                                'Kleinwagen' => 'Kleinwagen', 'SUV' => 'SUV',
                                'Coupé' => 'Coupé', 'Cabrio' => 'Cabrio',
                                'Van' => 'Van', 'Pickup' => 'Pickup',
                                'Sonstige' => 'Sonstige',
                            ]),
                        ]),
                    ]),
                    Section::make('Identifikation')->schema([
                        Grid::make(3)->schema([
                            TextInput::make('fahrgestellnummer')
                                ->label('Fahrgestellnummer (VIN)')
                                ->maxLength(17)
                                ->placeholder('WXXXXXXXXXXXXXX'),
                            TextInput::make('hsn')
                                ->label('HSN')
                                ->maxLength(10)
                                ->placeholder('0005'),
                            TextInput::make('tsn')
                                ->label('TSN')
                                ->maxLength(10)
                                ->placeholder('AKE'),
                        ]),
                    ]),
                ]),

            Step::make('Modell & Aufbau')
                ->icon('heroicon-o-document-text')
                ->description('Marke, Modell, Aufbau')
                ->schema([
                    Section::make('Bezeichnung')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('marke')->required()->maxLength(80)->placeholder('z. B. BMW'),
                            TextInput::make('modell')->required()->maxLength(120)->placeholder('z. B. 320d'),
                            TextInput::make('titel')
                                ->required()
                                ->maxLength(200)
                                ->columnSpanFull()
                                ->placeholder('z. B. BMW 320d Touring M-Paket'),
                            TextInput::make('baureihe')->maxLength(80)->placeholder('z. B. 3er'),
                            TextInput::make('ausstattungslinie')->maxLength(80)->placeholder('z. B. M Sport'),
                        ]),
                    ]),
                    Section::make('Aufbau')->schema([
                        Grid::make(3)->schema([
                            TextInput::make('tueren')->numeric()->minValue(0)->maxValue(9)->label('Türen'),
                            Toggle::make('schiebetuer')->label('Schiebetür')->inline(false),
                            TextInput::make('sitze')->numeric()->minValue(0)->maxValue(9),
                            TextInput::make('gewicht')->numeric()->minValue(0)->suffix('kg'),
                            TextInput::make('anhaengelast_gebremst')->numeric()->minValue(0)->suffix('kg')->label('Anh.-Last gebremst'),
                            TextInput::make('anhaengelast_ungebremst')->numeric()->minValue(0)->suffix('kg')->label('Anh.-Last ungebremst'),
                        ]),
                    ]),
                    Section::make('Kilometerstand')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('kilometerstand')->numeric()->minValue(0)->suffix('km')->default(0)->required(),
                        ]),
                    ]),
                ]),

            Step::make('Zulassung & Wartung')
                ->icon('heroicon-o-calendar-days')
                ->description('EZ, HU, Wartung, Herkunft')
                ->schema([
                    Section::make('Zulassung')->schema([
                        Grid::make(3)->schema([
                            TextInput::make('erstzulassung')->placeholder('JJJJ-MM')->maxLength(10)->label('Erstzulassung'),
                            TextInput::make('produktionsdatum')->placeholder('JJJJ-MM')->maxLength(10)->label('Produktionsdatum'),
                            TextInput::make('herkunft')->maxLength(80)->placeholder('z. B. Deutsche Ausführung'),
                        ]),
                    ]),
                    Section::make('Hauptuntersuchung & Wartung')->schema([
                        Grid::make(3)->schema([
                            TextInput::make('hu')->label('HU bis')->placeholder('MM/JJJJ')->maxLength(10),
                            TextInput::make('letzte_wartung_datum')->maxLength(10)->placeholder('MM/JJJJ')->label('Letzte Wartung'),
                            TextInput::make('letzte_wartung_km')->numeric()->minValue(0)->suffix('km')->label('Letzte Wartung (km)'),
                        ]),
                    ]),
                ]),

            Step::make('Motor & Antrieb')
                ->icon('heroicon-o-cog-6-tooth')
                ->description('Motor, Getriebe, Leistung')
                ->schema([
                    Section::make('Antrieb')->schema([
                        Grid::make(3)->schema([
                            Select::make('kraftstoff')->options([
                                'Benzin' => 'Benzin', 'Diesel' => 'Diesel',
                                'Elektro' => 'Elektro', 'Hybrid' => 'Hybrid',
                                'LPG' => 'LPG', 'CNG' => 'CNG',
                            ]),
                            Select::make('getriebe')->options([
                                'Automatik' => 'Automatik',
                                'Schaltgetriebe' => 'Schaltgetriebe',
                            ]),
                            Select::make('antriebsart')->options([
                                'Frontantrieb' => 'Frontantrieb',
                                'Heckantrieb' => 'Heckantrieb',
                                'Allradantrieb' => 'Allradantrieb',
                            ]),
                        ]),
                    ]),
                    Section::make('Motorisierung')->schema([
                        Grid::make(3)->schema([
                            TextInput::make('leistung_kw')->numeric()->minValue(0)->suffix('kW')->label('Leistung (kW)'),
                            TextInput::make('leistung_ps')->numeric()->minValue(0)->suffix('PS')->label('Leistung (PS)'),
                            TextInput::make('hubraum')->numeric()->minValue(0)->suffix('cm³'),
                            TextInput::make('zylinder')->numeric()->minValue(0)->maxValue(16),
                            TextInput::make('tankgroesse')->numeric()->minValue(0)->suffix('l')->label('Tankgröße'),
                        ]),
                    ]),
                ]),

            Step::make('Farbe & Optik')
                ->icon('heroicon-o-paint-brush')
                ->description('Außenfarbe, Innenausstattung')
                ->schema([
                    Section::make('Außenfarbe')->schema([
                        Grid::make(3)->schema([
                            TextInput::make('farbe')->maxLength(60)->label('Farbe'),
                            TextInput::make('farbe_hersteller')->maxLength(120)->label('Herstellerfarbe'),
                            TextInput::make('lackierung')->maxLength(30)->label('Lackierung'),
                            Toggle::make('metallic')->label('Metallic')->inline(false),
                        ]),
                    ]),
                    Section::make('Innenausstattung')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('innenausstattung')->maxLength(80)->label('Innenausstattung'),
                            TextInput::make('airbags')->maxLength(120),
                        ]),
                    ]),
                ]),

            Step::make('Historie & Zustand')
                ->icon('heroicon-o-shield-check')
                ->description('Unfälle, Halter, Garantie')
                ->schema([
                    Section::make('Fahrzeughistorie')->schema([
                        Grid::make(3)->schema([
                            TextInput::make('anzahl_halter')->numeric()->minValue(0)->label('Anzahl Vorbesitzer'),
                            Toggle::make('unfallfrei')->label('Unfallfrei')->default(true)->inline(false),
                            Toggle::make('unfallschaden')->label('Unfallschaden')->inline(false),
                            Toggle::make('verkehrstauglich')->label('Verkehrstüchtig')->default(true)->inline(false),
                            Toggle::make('nichtraucher')->label('Nichtraucherfahrzeug')->inline(false),
                            Toggle::make('scheckheftgepflegt')->label('Scheckheftgepflegt')->inline(false),
                        ]),
                    ]),
                    Section::make('Garantie')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('garantie')->maxLength(80)->placeholder('z. B. 24 Monate'),
                        ]),
                    ]),
                ]),

            Step::make('Verbrauch & Emissionen')
                ->icon('heroicon-o-fire')
                ->description('CO₂, Verbrauch, Schadstoffklasse')
                ->schema([
                    Section::make('Verbrauch')->schema([
                        Grid::make(3)->schema([
                            TextInput::make('energieverbrauch')->numeric()->minValue(0)->suffix('l/100km')->label('Verbrauch (kombiniert)'),
                            TextInput::make('verbrauch_innerorts')->numeric()->minValue(0)->suffix('l/100km')->label('Verbrauch (innerorts)'),
                            TextInput::make('verbrauch_ausserorts')->numeric()->minValue(0)->suffix('l/100km')->label('Verbrauch (außerorts)'),
                        ]),
                    ]),
                    Section::make('Emissionen')->schema([
                        Grid::make(3)->schema([
                            TextInput::make('co2_emissionen')->numeric()->minValue(0)->suffix('g/km')->label('CO₂-Emissionen'),
                            Select::make('co2_klasse')->options([
                                'A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D',
                                'E' => 'E', 'F' => 'F', 'G' => 'G',
                            ])->label('CO₂-Klasse'),
                            TextInput::make('energiekosten')->numeric()->minValue(0)->prefix('€')->label('Energiekosten/Jahr'),
                        ]),
                    ]),
                    Section::make('Umwelt')->schema([
                        Grid::make(2)->schema([
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
                ]),

            Step::make('Ausstattung')
                ->icon('heroicon-o-check-circle')
                ->description('Features & Sonderausstattung')
                ->schema([
                    ...self::equipmentSections(),
                    Section::make('Freitext-Ausstattung')->schema([
                        Textarea::make('ausstattung_sonder')
                            ->label('Sonderausstattung')
                            ->rows(6)
                            ->placeholder("15490 Außenspiegel mit Totwinkel-Assistent\n29500 Sitzheizung vorn"),
                        Textarea::make('ausstattung_serie')
                            ->label('Serienausstattung')
                            ->rows(6),
                    ]),
                ]),

            Step::make('Bilder')
                ->icon('heroicon-o-photo')
                ->description('Fahrzeugbilder hochladen')
                ->schema([
                    Section::make('Fahrzeugbilder')->schema([
                        FileUpload::make('_uploaded_images')
                            ->label('Bilder hochladen')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->maxSize(10240)
                            ->disk('public')
                            ->directory('vehicles')
                            ->imageEditor()
                            ->panelLayout('grid')
                            ->hint('Bilder per Drag & Drop sortieren. Erstes Bild = Titelbild.')
                            ->dehydrated(false),
                    ]),
                ]),

            Step::make('Preis & Beschreibung')
                ->icon('heroicon-o-currency-euro')
                ->description('Preis, MwSt., Beschreibung')
                ->schema([
                    Section::make('Preis')->schema([
                        Grid::make(2)->schema([
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
                        ]),
                    ]),
                    Section::make('Beschreibung')->schema([
                        Textarea::make('beschreibung')->rows(8)->columnSpanFull(),
                    ]),
                    Section::make('Status')->schema([
                        Grid::make(2)->schema([
                            Toggle::make('verfuegbar')->label('Auf Webseite anzeigen')->default(true)->inline(false),
                            Toggle::make('verkauft')->label('Bereits verkauft')->inline(false),
                        ]),
                    ]),
                ]),
        ];
    }

    protected function afterCreate(): void
    {
        $sort = 1;
        $uploaded = $this->data['_uploaded_images'] ?? [];
        foreach ($uploaded as $path) {
            VehicleImage::create([
                'vehicle_id' => $this->record->id,
                'dateiname'  => $path,
                'sortierung' => $sort++,
            ]);
        }

        $equipmentIds = [];
        foreach (EquipmentCategory::all() as $cat) {
            $ids = $this->data['_equip_' . $cat->id] ?? [];
            if (is_array($ids)) {
                $equipmentIds = array_merge($equipmentIds, $ids);
            }
        }
        if (! empty($equipmentIds)) {
            $this->record->equipment()->sync($equipmentIds);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['dat_xml_import'], $data['_uploaded_images']);

        foreach (array_keys($data) as $key) {
            if (str_starts_with($key, '_equip_')) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    private static function equipmentSections(): array
    {
        $sections = [];

        foreach (EquipmentCategory::orderBy('sortierung')->get() as $cat) {
            $options = $cat->items->pluck('name', 'id')->toArray();
            if (empty($options)) continue;

            $sections[] = Section::make($cat->name)
                ->collapsible()
                ->schema([
                    CheckboxList::make('_equip_' . $cat->id)
                        ->label('')
                        ->options($options)
                        ->columns(4)
                        ->dehydrated(false),
                ]);
        }

        return $sections;
    }

    private function importDatXml(Set $set, string $state): void
    {
        try {
            $path = is_string($state)
                ? storage_path('app/private/' . $state)
                : $state;
            $xml = file_get_contents($path);
            $data = DatVxsParser::parse($xml);

            foreach ($data as $key => $value) {
                $set($key, $value);
            }

            Notification::make()
                ->title('DAT-Daten importiert')
                ->body($data['titel'] . ' – bitte Felder prüfen und ergänzen.')
                ->success()->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Import fehlgeschlagen')
                ->body($e->getMessage())
                ->danger()->send();
        }
    }
}
