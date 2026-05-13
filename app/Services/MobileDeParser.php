<?php

namespace App\Services;

class MobileDeParser
{
    public static function parseAdsJson(string $json): array
    {
        $data = json_decode($json, true);

        if (! isset($data['ads']) || ! is_array($data['ads'])) {
            throw new \RuntimeException('Ungültiges mobile.de-Format: "ads"-Array fehlt.');
        }

        return array_map([self::class, 'mapAd'], $data['ads']);
    }

    public static function mapAd(array $ad): array
    {
        $make  = ucwords(strtolower($ad['make'] ?? ''));
        $model = $ad['model'] ?? '';
        $desc  = $ad['modelDescription'] ?? trim($make . ' ' . $model);

        $fuelMap = [
            'PETROL'          => 'Benzin',
            'DIESEL'          => 'Diesel',
            'ELECTRICITY'     => 'Elektro',
            'HYBRID'          => 'Hybrid',
            'HYBRID_DIESEL'   => 'Hybrid',
            'LPG'             => 'LPG',
            'CNG'             => 'CNG',
            'HYDROGEN'        => 'Elektro',
        ];

        $gearboxMap = [
            'AUTOMATIC_GEAR'      => 'Automatik',
            'SEMIAUTOMATIC_GEAR'  => 'Automatik',
            'MANUAL_GEAR'         => 'Schaltgetriebe',
        ];

        $colorMap = [
            'BLACK'   => 'Schwarz',
            'WHITE'   => 'Weiß',
            'SILVER'  => 'Silber',
            'GREY'    => 'Grau',
            'BLUE'    => 'Blau',
            'RED'     => 'Rot',
            'GREEN'   => 'Grün',
            'BROWN'   => 'Braun',
            'BEIGE'   => 'Beige',
            'ORANGE'  => 'Orange',
            'YELLOW'  => 'Gelb',
            'GOLD'    => 'Gold',
            'PURPLE'  => 'Violett',
            'BRONZE'  => 'Bronze',
        ];

        $doorMap = [
            'TWO_OR_THREE' => 2,
            'FOUR_OR_FIVE' => 4,
            'SIX_OR_MORE'  => 6,
        ];

        $conditionMap = [
            'NEW'           => 'Neu',
            'USED'          => 'Gebraucht',
            'EMPLOYEES_CAR' => 'Jahreswagen',
            'DEMONSTRATION' => 'Vorführwagen',
            'PRE_REGISTRATION' => 'Jahreswagen',
            'OLDTIMER'      => 'Oldtimer',
        ];

        $categoryMap = [
            'Limousine'   => ['Saloon'],
            'Kombi'       => ['EstateCar', 'StationWagon'],
            'Kleinwagen'  => ['SmallCar', 'CityBuggy'],
            'SUV'         => ['OffRoad', 'SUV'],
            'Coupé'       => ['SportsCar'],
            'Cabrio'      => ['Cabrio', 'Roadster'],
            'Van'         => ['Van', 'VanMinibus'],
            'Pickup'      => ['Pickup'],
        ];

        $karosserie = null;
        $category = $ad['category'] ?? '';
        foreach ($categoryMap as $type => $cats) {
            if (in_array($category, $cats, true)) {
                $karosserie = $type;
                break;
            }
        }

        $ez = null;
        if (! empty($ad['firstRegistration']) && preg_match('/^(\d{4})(\d{2})$/', $ad['firstRegistration'], $m)) {
            $ez = $m[1] . '-' . $m[2];
        }

        $hu = null;
        if (! empty($ad['generalInspection']) && preg_match('/^(\d{4})(\d{2})$/', $ad['generalInspection'], $m)) {
            $hu = $m[2] . '/' . $m[1];
        }

        $leistungKw = isset($ad['power']) ? (int) $ad['power'] : null;
        $leistungPs = $leistungKw ? (int) round($leistungKw * 1.35962) : null;

        $klimaDetailMap = [
            'AUTOMATIC_CLIMATISATION'         => 'Klimaautomatik',
            'AUTOMATIC_CLIMATISATION_2_ZONES' => '2-Zonen-Klimaautomatik',
            'AUTOMATIC_CLIMATISATION_3_ZONES' => '3-Zonen-Klimaautomatik',
            'AUTOMATIC_CLIMATISATION_4_ZONES' => '4-Zonen-Klimaautomatik',
            'MANUAL_CLIMATISATION'            => 'Manuelle Klimaanlage',
            'NO_CLIMATISATION'                => null,
        ];

        $klimatisierung = $klimaDetailMap[$ad['climatisation'] ?? ''] ?? null;
        $klimaanlage = $klimatisierung !== null;

        $navigation = ! empty($ad['navigationSystem']) || ! empty($ad['navigationPreparation']);

        $sitzheizung = ! empty($ad['electricHeatedSeats']);

        $parkAssistants = $ad['parkingAssistants'] ?? [];
        $einparkhilfe = ! empty($parkAssistants);

        $parkMap = [
            'REAR_SENSORS'           => 'Hinten',
            'FRONT_SENSORS'          => 'Vorne',
            'CAMERA'                 => 'Kamera',
            'SELF_STEERING_SYSTEM'   => 'Selbstlenkendes System',
        ];
        $einparkhilfeDetail = implode(', ', array_filter(array_map(
            fn($p) => $parkMap[$p] ?? $p,
            $parkAssistants
        )));

        $airbagMap = [
            'DRIVER_AIRBAG'                       => 'Fahrerairbag',
            'FRONT_AIRBAGS'                       => 'Front-Airbags',
            'FRONT_AND_SIDE_AIRBAGS'              => 'Front- und Seitenairbags',
            'FRONT_AND_SIDE_AND_MORE_AIRBAGS'     => 'Front-, Seiten- und weitere Airbags',
        ];
        $airbags = $airbagMap[$ad['airbag'] ?? ''] ?? null;

        $driveMap = [
            'FRONT'      => 'Frontantrieb',
            'REAR'       => 'Heckantrieb',
            'ALL_WHEEL'  => 'Allradantrieb',
        ];
        $antriebsart = $driveMap[$ad['driveType'] ?? ''] ?? null;

        $emissionMap = [
            'EURO1' => 'Euro 1', 'EURO2' => 'Euro 2', 'EURO3' => 'Euro 3',
            'EURO4' => 'Euro 4', 'EURO5' => 'Euro 5', 'EURO6' => 'Euro 6',
            'EURO6D' => 'Euro 6d', 'EURO6D_TEMP' => 'Euro 6d-TEMP',
        ];
        $schadstoffklasse = $emissionMap[$ad['emissionClass'] ?? ''] ?? null;

        $stickerMap = [
            'EMISSIONSSTICKER_GREEN'  => '4 (Grün)',
            'EMISSIONSSTICKER_YELLOW' => '3 (Gelb)',
            'EMISSIONSSTICKER_RED'    => '2 (Rot)',
            'EMISSIONSSTICKER_NONE'   => 'Keine',
        ];
        $umweltplakette = $stickerMap[$ad['emissionSticker'] ?? ''] ?? null;

        $countryMap = ['DE' => 'Deutsche Ausführung'];
        $herkunft = $countryMap[$ad['countryVersion'] ?? ''] ?? ($ad['countryVersion'] ?? null);

        $interiorTypeMap = [
            'LEATHER'       => 'Leder',
            'PART_LEATHER'  => 'Teilleder',
            'FABRIC'        => 'Stoff',
            'ALCANTARA'     => 'Alcantara',
            'VELOUR'        => 'Velours',
        ];
        $interiorColorMap = [
            'BLACK' => 'Schwarz', 'GREY' => 'Grau', 'BEIGE' => 'Beige',
            'BROWN' => 'Braun', 'WHITE' => 'Weiß', 'RED' => 'Rot', 'BLUE' => 'Blau',
        ];
        $innenParts = array_filter([
            $interiorTypeMap[$ad['interiorType'] ?? ''] ?? null,
            $interiorColorMap[$ad['interiorColor'] ?? ''] ?? null,
        ]);
        $innenausstattung = $innenParts ? implode(', ', $innenParts) : null;

        $letzteWartung = null;
        if (! empty($ad['lastMaintenanceDate']) && preg_match('/^(\d{4})(\d{2})$/', $ad['lastMaintenanceDate'], $m)) {
            $letzteWartung = $m[2] . '/' . $m[1];
        }

        $speedCtrl = $ad['speedControl'] ?? '';
        $tempomat = in_array($speedCtrl, ['CRUISE_CONTROL', 'ADAPTIVE_CRUISE_CONTROL'], true);

        $anhaengerkupplung = ! empty($ad['trailerCouplingType']);

        $interiorType = $ad['interiorType'] ?? '';
        $ledersitze = in_array($interiorType, ['LEATHER', 'PART_LEATHER'], true);

        $schiebedach = ! empty($ad['sunroof']) || ! empty($ad['panoramicRoof']);

        $preis = null;
        if (isset($ad['price']['consumerPriceGross'])) {
            $preis = (float) $ad['price']['consumerPriceGross'];
        }

        $images = [];
        foreach ($ad['images'] ?? [] as $img) {
            if (! empty($img['ref'])) {
                $images[] = $img['ref'];
            }
        }

        $beschreibung = $ad['description'] ?? '';
        $beschreibung = str_replace('\\\\', "\n", $beschreibung);
        $beschreibung = preg_replace('/\*{2,}([^*]+)\*{2,}/', '$1', $beschreibung);
        $beschreibung = trim($beschreibung);

        $produktionsdatum = null;
        if (! empty($ad['productionDate']) && preg_match('/^(\d{4})(\d{2})$/', $ad['productionDate'], $m)) {
            $produktionsdatum = $m[1] . '-' . $m[2];
        }

        $schiebetuer = ! empty($ad['slidingDoor']) || ! empty($ad['slidingDoorLeft']) || ! empty($ad['slidingDoorRight']);

        $paintMap = [
            'METALLIC' => 'Metallic',
            'NON_METALLIC' => 'Uni',
        ];
        $lackierung = $paintMap[$ad['paintType'] ?? ''] ?? null;
        $metallic = ($ad['paintType'] ?? '') === 'METALLIC';

        $mwstMap = [
            'GROSS' => 'MwSt. ausweisbar',
            'MARGIN' => 'Differenzbesteuert',
        ];
        $mwst = $mwstMap[$ad['vatType'] ?? ''] ?? null;

        $fahrzeugtypMap = [
            'CAR' => 'PKW',
            'TRUCK' => 'LKW',
            'MOTORHOME' => 'Wohnmobil',
            'MOTORCYCLE' => 'Motorrad',
        ];
        $fahrzeugtyp = $fahrzeugtypMap[$ad['vehicleType'] ?? ''] ?? 'PKW';

        return [
            'mobile_ad_id'      => $ad['mobileAdId'] ?? null,
            'fahrgestellnummer' => $ad['vin'] ?? null,
            'hsn'               => $ad['hsn'] ?? null,
            'tsn'               => $ad['tsn'] ?? null,
            'marke'             => $make,
            'modell'            => $model,
            'titel'             => trim($make . ' ' . $desc),
            'beschreibung'      => $beschreibung,
            'preis'             => $preis,
            'mwst'              => $mwst,
            'fahrzeugtyp'       => $fahrzeugtyp,
            'karosserie'            => $karosserie,
            'baureihe'              => $ad['modelRange'] ?? null,
            'ausstattungslinie'     => $ad['trimLine'] ?? null,
            'herkunft'              => $herkunft,
            'erstzulassung'         => $ez,
            'produktionsdatum'      => $produktionsdatum,
            'kilometerstand'        => $ad['mileage'] ?? 0,
            'kraftstoff'            => $fuelMap[strtoupper($ad['fuel'] ?? '')] ?? null,
            'getriebe'              => $gearboxMap[$ad['gearbox'] ?? ''] ?? null,
            'antriebsart'           => $antriebsart,
            'leistung_kw'           => $leistungKw,
            'leistung_ps'           => $leistungPs,
            'hubraum'               => $ad['cubicCapacity'] ?? null,
            'zylinder'              => $ad['cylinder'] ?? null,
            'tankgroesse'           => $ad['fuelTankVolume'] ?? null,
            'energieverbrauch'      => $ad['consumptions']['fuel']['combined'] ?? null,
            'verbrauch_innerorts'   => $ad['consumptions']['fuel']['innerCity'] ?? null,
            'verbrauch_ausserorts'  => $ad['consumptions']['fuel']['outerCity'] ?? null,
            'co2_emissionen'        => $ad['emissions']['combined']['co2'] ?? null,
            'co2_klasse'            => $ad['co2Class'] ?? null,
            'energiekosten'         => $ad['energyCosts'] ?? null,
            'schadstoffklasse'      => $schadstoffklasse,
            'umweltplakette'        => $umweltplakette,
            'farbe'                 => $colorMap[$ad['exteriorColor'] ?? ''] ?? ucfirst(strtolower($ad['exteriorColor'] ?? '')),
            'farbe_hersteller'      => $ad['manufacturerColorName'] ?? null,
            'lackierung'            => $lackierung,
            'metallic'              => $metallic,
            'innenausstattung'      => $innenausstattung,
            'tueren'                => $doorMap[$ad['doors'] ?? ''] ?? null,
            'schiebetuer'           => $schiebetuer,
            'sitze'                 => $ad['seats'] ?? null,
            'anhaengelast_gebremst'  => $ad['trailerLoadBraked'] ?? null,
            'anhaengelast_ungebremst'=> $ad['trailerLoadUnbraked'] ?? null,
            'gewicht'               => $ad['weight'] ?? null,
            'zustand'               => $conditionMap[$ad['condition'] ?? ''] ?? 'Gebraucht',
            'hu'                    => $hu,
            'letzte_wartung_datum'   => $letzteWartung,
            'letzte_wartung_km'     => $ad['lastMaintenanceMileage'] ?? null,
            'anzahl_halter'         => $ad['numberOfPreviousOwners'] ?? null,
            'klimaanlage'           => $klimaanlage,
            'klimatisierung'        => $klimatisierung,
            'navigation'            => $navigation,
            'sitzheizung'           => $sitzheizung,
            'einparkhilfe'          => $einparkhilfe,
            'einparkhilfe_detail'   => $einparkhilfeDetail ?: null,
            'airbags'               => $airbags,
            'tempomat'              => $tempomat,
            'anhaengerkupplung'     => $anhaengerkupplung,
            'ledersitze'            => $ledersitze,
            'schiebedach'           => $schiebedach,
            'nichtraucher'          => ! empty($ad['nonSmokerVehicle']),
            'scheckheftgepflegt'    => ! empty($ad['fullServiceHistory']),
            'verkehrstauglich'      => ! ($ad['notRoadworthy'] ?? false),
            'unfallschaden'         => ! empty($ad['accidentDamaged']),
            'unfallfrei'            => ! ($ad['accidentDamaged'] ?? false),
            'garantie'              => $ad['warranty'] ?? null,
            'verfuegbar'            => true,
            'verkauft'              => false,
            'images'                => $images,
            'equipment_names'       => self::extractEquipment($ad),
        ];
    }

    private static function extractEquipment(array $ad): array
    {
        $equipment = [];

        $featureMap = [
            'Komfort' => [
                'ambientLighting' => 'Ambiente-Beleuchtung',
                'armRest' => 'Armlehne',
                'heatedWindshield' => 'Beheizbare Frontscheibe',
                'heatedSteeringWheel' => 'Beheizbares Lenkrad',
                'electricWindows' => 'Elektr. Fensterheber',
                'electricTailgate' => 'Elektr. Heckklappe',
                'electricMirrors' => 'Elektr. Seitenspiegel',
                'foldingMirrors' => 'Elektrisch anklappbare Seitenspiegel',
                'electricSeats' => 'Elektr. Sitzeinstellung',
                'seatMemory' => 'Sitzeinstellung mit Memory-Funktion',
                'panoramicRoof' => 'Panorama-Dach',
                'sunroof' => 'Schiebedach',
                'keylessEntry' => 'Schlüssellose Zentralverriegelung (Keyless)',
                'electricHeatedSeats' => 'Sitzheizung',
                'rearSeatHeating' => 'Sitzheizung hinten',
                'ventilatedSeats' => 'Sitzbelüftung',
                'auxiliaryHeating' => 'Standheizung',
                'centralLocking' => 'Zentralverriegelung',
                'hillStartAssist' => 'Berganfahrassistent',
                'onBoardComputer' => 'Bordcomputer',
                'headUpDisplay' => 'Head-Up Display',
                'leatherSteeringWheel' => 'Lederlenkrad',
                'lumbarSupport' => 'Lordosenstütze',
                'massageSeats' => 'Massagesitze',
            ],
            'Infotainment' => [
                'multiSteeringWheel' => 'Multifunktionslenkrad',
                'paddleShifters' => 'Schaltwippen',
                'digitalCockpit' => 'Volldigitales Kombiinstrument',
                'androidAuto' => 'Android Auto',
                'bluetooth' => 'Bluetooth',
                'appleCarPlay' => 'Apple CarPlay',
                'cdPlayer' => 'CD-Spieler',
                'handsFreeKit' => 'Freisprecheinrichtung',
                'wirelessCharging' => 'Induktionsladen für Smartphones',
                'integratedMusicStreaming' => 'Musikstreaming integriert',
                'navigationSystem' => 'Navigationssystem',
                'navigationPreparation' => 'Navigationsvorbereitung',
                'soundSystem' => 'Soundsystem',
                'voiceControl' => 'Sprachsteuerung',
                'touchscreen' => 'Touchscreen',
                'tv' => 'TV',
                'usb' => 'USB',
                'wlan' => 'WLAN / Wifi Hotspot',
            ],
            'Tuner/Radio' => [
                'dabRadio' => 'Radio DAB',
                'radio' => 'Tuner/Radio',
            ],
            'Sicherheit' => [
                'alarm' => 'Alarmanlage',
                'abs' => 'ABS',
                'distanceWarner' => 'Abstandswarner',
                'adaptiveSuspension' => 'Adaptives Fahrwerk',
                'esp' => 'ESP',
                'highBeamAssist' => 'Fernlichtassistent',
                'speedLimiter' => 'Geschwindigkeitsbegrenzer',
                'isofix' => 'Isofix',
                'lightSensor' => 'Lichtsensor',
                'fatigueWarner' => 'Müdigkeitswarner',
                'nightVision' => 'Nachtsicht-Assistent',
                'fogLights' => 'Nebelscheinwerfer',
                'emergencyBrake' => 'Notbremsassistent',
                'emergencyCall' => 'Notrufsystem',
                'rainSensor' => 'Regensensor',
                'tirePressure' => 'Reifendruckkontrolle',
                'laneAssist' => 'Spurhalteassistent',
                'startStop' => 'Start/Stopp-Automatik',
                'blindSpotAssist' => 'Totwinkel-Assistent',
                'tractionControl' => 'Traktionskontrolle',
                'trafficSignRecognition' => 'Verkehrszeichenerkennung',
                'immobilizer' => 'Elektr. Wegfahrsperre',
            ],
            'Exterieur' => [
                'tintedWindows' => 'Abgedunkelte Scheiben',
                'alloyWheels' => 'Leichtmetallfelgen',
                'roofRails' => 'Dachreling',
                'airSuspension' => 'Luftfederung',
                'headlightWasher' => 'Scheinwerferreinigung',
                'winterPackage' => 'Winterpaket',
            ],
        ];

        foreach ($featureMap as $category => $features) {
            foreach ($features as $jsonKey => $germanName) {
                if (! empty($ad[$jsonKey])) {
                    $equipment[] = ['category' => $category, 'name' => $germanName];
                }
            }
        }

        $features = $ad['features'] ?? [];
        if (is_array($features)) {
            foreach ($features as $feature) {
                $name = is_string($feature) ? $feature : ($feature['description'] ?? null);
                if ($name) {
                    $equipment[] = ['category' => 'Sonstiges', 'name' => $name];
                }
            }
        }

        return $equipment;
    }
}
