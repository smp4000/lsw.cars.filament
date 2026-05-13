<?php

namespace App\Services;

use SimpleXMLElement;

class DatVxsParser
{
    public static function parse(string $xmlContent): array
    {
        $xml = new SimpleXMLElement($xmlContent);
        $ns = 'http://www.dat.de/vxs';
        $xml->registerXPathNamespace('ns2', $ns);

        $dossier = $xml->children($ns)->Dossier;
        $vehicle = $dossier->Vehicle;
        $equipment = $vehicle->Equipment;
        $engine = $vehicle->Engine;
        $tech = $vehicle->TechInfo;

        $marke = (string) $vehicle->ManufacturerName;
        $baseModel = (string) $vehicle->BaseModelName;
        $subModel = (string) $vehicle->SubModelName;

        $baseModel = preg_replace('/\s*\(.*$/', '', $baseModel);
        $subModel = preg_replace('/\s*\(.*$/', '', $subModel);

        $fuelMap = [
            'gasoline' => 'Benzin',
            'diesel'   => 'Diesel',
            'electric' => 'Elektro',
            'hybrid'   => 'Hybrid',
            'lpg'      => 'LPG',
            'cng'      => 'CNG',
        ];
        $fuel = strtolower((string) $engine->FuelMethod);

        $leistungKw = null;
        $leistungPs = null;
        $hubraum = null;
        $getriebe = null;

        $serieItems = self::parseEquipmentList($equipment->SeriesEquipment, $ns);
        $sonderItems = self::parseEquipmentList($equipment->SpecialEquipment, $ns);

        $allDescriptions = array_merge(
            array_column($serieItems, 'description'),
            array_column($sonderItems, 'description'),
        );

        foreach ($allDescriptions as $desc) {
            if (preg_match('/Motor.*?(\d+)\s*kW/', $desc, $m)) {
                $leistungKw = (int) $m[1];
                $leistungPs = (int) round($leistungKw * 1.35962);
            }
            if (preg_match('/(\d)[.,](\d)\s*Liter/', $desc, $m)) {
                $hubraum = (int) ($m[1] . $m[2]) * 100;
            }
            if (preg_match('/Hubraum\s+(\d)[.,](\d)\s*Liter/', $desc, $m)) {
                $hubraum = (int) ($m[1] . $m[2]) * 100;
            }
            if (preg_match('/Getriebe\s+(\d+)-Gang/', $desc)) {
                $getriebe = 'Automatik';
            }
            if (preg_match('/Schaltgetriebe/', $desc)) {
                $getriebe = 'Schaltgetriebe';
            }
        }

        $booleanMap = [
            'klimaanlage'       => ['Klimaautomatik', 'Klimaanlage', 'Thermotronik'],
            'navigation'        => ['Navigation', 'COMAND', 'Navigationssystem'],
            'sitzheizung'       => ['Sitzheizung'],
            'einparkhilfe'      => ['Parktronic', 'Einparkhilfe', 'PDC'],
            'tempomat'          => ['Tempomat'],
            'anhaengerkupplung' => ['Anhängerkupplung'],
            'ledersitze'        => ['Leder', 'Nappa'],
            'schiebedach'       => ['Panoramadach', 'Schiebedach', 'Glasdach'],
        ];

        $booleans = [];
        $allText = implode('|', $allDescriptions);
        foreach ($booleanMap as $field => $keywords) {
            $booleans[$field] = false;
            foreach ($keywords as $kw) {
                if (stripos($allText, $kw) !== false) {
                    $booleans[$field] = true;
                    break;
                }
            }
        }

        $farbe = (string) $equipment->Color;
        $farbe = ucwords(strtolower($farbe));

        $tueren = (string) $tech->VehicleDoors ?: null;

        $karosserie = self::detectKarosserie($baseModel, $subModel, $allDescriptions);

        return [
            'fahrgestellnummer' => (string) $vehicle->VehicleIdentNumber,
            'dat_ecode'         => (string) $vehicle->DatECode,
            'marke'             => $marke,
            'modell'            => trim($baseModel . ' ' . $subModel),
            'titel'             => trim($marke . ' ' . $baseModel . ' ' . $subModel),
            'kraftstoff'        => $fuelMap[$fuel] ?? ucfirst($fuel),
            'getriebe'          => $getriebe,
            'leistung_kw'       => $leistungKw,
            'leistung_ps'       => $leistungPs,
            'hubraum'           => $hubraum,
            'farbe'             => $farbe,
            'tueren'            => $tueren ? (int) $tueren : null,
            'karosserie'        => $karosserie,
            'zustand'           => 'Gebraucht',
            ...$booleans,
            'ausstattung_serie'  => $serieItems,
            'ausstattung_sonder' => $sonderItems,
        ];
    }

    private static function parseEquipmentList(?SimpleXMLElement $list, string $ns): array
    {
        if (! $list) return [];

        $items = [];
        foreach ($list->children($ns) as $pos) {
            $id = (string) $pos->DatEquipmentId;
            $desc = (string) $pos->Description;
            $group = (string) $pos->EquipmentGroup;
            $auto = (string) $pos->AddedByLogikCheck === 'true';

            if (! $desc) continue;

            $items[] = array_filter([
                'dat_id'      => $id,
                'description' => $desc,
                'group'       => $group ?: null,
                'auto'        => $auto ?: null,
            ], fn($v) => $v !== null);
        }

        return $items;
    }

    private static function detectKarosserie(string $baseModel, string $subModel, array $descriptions): ?string
    {
        $combined = strtolower($baseModel . ' ' . $subModel . ' ' . implode(' ', $descriptions));

        $map = [
            'coupé'     => ['coupe', 'coupé'],
            'Cabrio'    => ['cabrio', 'cabriolet', 'roadster', 'spider'],
            'Kombi'     => ['touring', 'avant', 'variant', 'kombi', 'estate', 'break'],
            'SUV'       => ['suv', 'macan', 'cayenne', 'x1', 'x3', 'x5', 'gle', 'glc', 'tiguan', 'q3', 'q5', 'q7'],
            'Van'       => ['van', 'v-klasse', 'touran', 'sharan'],
            'Pickup'    => ['pickup', 'x-klasse', 'amarok'],
            'Kleinwagen'=> ['polo', 'up!', 'twingo', 'smart'],
        ];

        foreach ($map as $type => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($combined, $kw)) return $type;
            }
        }

        if (str_contains($combined, 'limousine') || str_contains($combined, 'sedan')) return 'Limousine';

        return null;
    }
}
