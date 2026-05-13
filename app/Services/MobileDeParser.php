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

        $klimaMap = [
            'AUTOMATIC_CLIMATISATION'         => true,
            'AUTOMATIC_CLIMATISATION_2_ZONES' => true,
            'AUTOMATIC_CLIMATISATION_3_ZONES' => true,
            'AUTOMATIC_CLIMATISATION_4_ZONES' => true,
            'MANUAL_CLIMATISATION'            => true,
            'NO_CLIMATISATION'                => false,
        ];

        $klimaanlage = $klimaMap[$ad['climatisation'] ?? ''] ?? false;

        $navigation = ! empty($ad['navigationSystem']) || ! empty($ad['navigationPreparation']);

        $sitzheizung = ! empty($ad['electricHeatedSeats']);

        $parkAssistants = $ad['parkingAssistants'] ?? [];
        $einparkhilfe = ! empty($parkAssistants);

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

        return [
            'mobile_ad_id'      => $ad['mobileAdId'] ?? null,
            'fahrgestellnummer' => $ad['vin'] ?? null,
            'marke'             => $make,
            'modell'            => $model,
            'titel'             => trim($make . ' ' . $desc),
            'beschreibung'      => $beschreibung,
            'preis'             => $preis,
            'karosserie'        => $karosserie,
            'erstzulassung'     => $ez,
            'kilometerstand'    => $ad['mileage'] ?? 0,
            'kraftstoff'        => $fuelMap[strtoupper($ad['fuel'] ?? '')] ?? null,
            'getriebe'          => $gearboxMap[$ad['gearbox'] ?? ''] ?? null,
            'leistung_kw'       => $leistungKw,
            'leistung_ps'       => $leistungPs,
            'hubraum'           => $ad['cubicCapacity'] ?? null,
            'farbe'             => $colorMap[$ad['exteriorColor'] ?? ''] ?? ucfirst(strtolower($ad['exteriorColor'] ?? '')),
            'tueren'            => $doorMap[$ad['doors'] ?? ''] ?? null,
            'sitze'             => $ad['seats'] ?? null,
            'zustand'           => $conditionMap[$ad['condition'] ?? ''] ?? 'Gebraucht',
            'hu'                => $hu,
            'anzahl_halter'     => $ad['numberOfPreviousOwners'] ?? null,
            'klimaanlage'       => $klimaanlage,
            'navigation'        => $navigation,
            'sitzheizung'       => $sitzheizung,
            'einparkhilfe'      => $einparkhilfe,
            'tempomat'          => $tempomat,
            'anhaengerkupplung' => $anhaengerkupplung,
            'ledersitze'        => $ledersitze,
            'schiebedach'       => $schiebedach,
            'verfuegbar'        => true,
            'verkauft'          => false,
            'images'            => $images,
        ];
    }
}
