<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        if (Vehicle::count() > 0) {
            return;
        }

        $u = 'https://images.unsplash.com/';
        $q = '?w=1400&q=80&auto=format&fit=crop';

        $desc = 'Top gepflegtes Fahrzeug aus erster Hand. Scheckheftgepflegt, '
              . 'unfallfrei und mit kompletter Wartungshistorie. Voll ausgestattet '
              . 'und sofort fahrbereit. Wir freuen uns auf Ihre Anfrage – '
              . 'Probefahrt jederzeit möglich.';

        $samples = [
            [
                'marke' => 'BMW', 'modell' => '320d Touring',
                'titel' => 'BMW 320d Touring M-Sport',
                'beschreibung' => $desc, 'preis' => 28900,
                'erstzulassung' => '2021-03', 'kilometerstand' => 45000,
                'kraftstoff' => 'Diesel', 'getriebe' => 'Automatik',
                'leistung_kw' => 140, 'leistung_ps' => 190, 'hubraum' => 1995,
                'farbe' => 'Saphirschwarz Metallic', 'tueren' => 5, 'sitze' => 5,
                'karosserie' => 'Kombi', 'hu' => '03/2026', 'anzahl_halter' => 1,
                'klimaanlage' => true, 'navigation' => true, 'sitzheizung' => true,
                'einparkhilfe' => true, 'tempomat' => true, 'ledersitze' => true,
                'images' => [
                    $u.'photo-1555215695-3004980ad54e'.$q,
                    $u.'photo-1503376780353-7e6692767b70'.$q,
                    $u.'photo-1542362567-b07e54358753'.$q,
                ],
            ],
            [
                'marke' => 'Audi', 'modell' => 'A4 Avant',
                'titel' => 'Audi A4 Avant 40 TFSI S line',
                'beschreibung' => $desc, 'preis' => 32500,
                'erstzulassung' => '2022-06', 'kilometerstand' => 32000,
                'kraftstoff' => 'Benzin', 'getriebe' => 'Automatik',
                'leistung_kw' => 150, 'leistung_ps' => 204, 'hubraum' => 1984,
                'farbe' => 'Ibisweiß', 'tueren' => 5, 'sitze' => 5,
                'karosserie' => 'Kombi', 'hu' => '06/2026', 'anzahl_halter' => 1,
                'klimaanlage' => true, 'navigation' => true, 'sitzheizung' => true,
                'einparkhilfe' => true, 'tempomat' => true, 'ledersitze' => true,
                'schiebedach' => true,
                'images' => [
                    $u.'photo-1606664515524-ed2f786a0bd6'.$q,
                    $u.'photo-1614026480418-bd11fde2f0fd'.$q,
                    $u.'photo-1612825173281-9a193378527e'.$q,
                ],
            ],
            [
                'marke' => 'Mercedes-Benz', 'modell' => 'C 220 d',
                'titel' => 'Mercedes-Benz C 220 d AMG-Line',
                'beschreibung' => $desc, 'preis' => 35900,
                'erstzulassung' => '2022-01', 'kilometerstand' => 28500,
                'kraftstoff' => 'Diesel', 'getriebe' => 'Automatik',
                'leistung_kw' => 147, 'leistung_ps' => 200, 'hubraum' => 1993,
                'farbe' => 'Selenitgrau Metallic', 'tueren' => 4, 'sitze' => 5,
                'karosserie' => 'Limousine', 'hu' => '01/2026', 'anzahl_halter' => 1,
                'klimaanlage' => true, 'navigation' => true, 'sitzheizung' => true,
                'einparkhilfe' => true, 'tempomat' => true, 'ledersitze' => true,
                'images' => [
                    $u.'photo-1618843479313-40f8afb4b4d8'.$q,
                    $u.'photo-1606664515524-ed2f786a0bd6'.$q,
                    $u.'photo-1617531653332-bd46c24f2068'.$q,
                ],
            ],
            [
                'marke' => 'Volkswagen', 'modell' => 'Golf 8 GTI',
                'titel' => 'VW Golf 8 GTI Performance',
                'beschreibung' => $desc, 'preis' => 31900,
                'erstzulassung' => '2021-09', 'kilometerstand' => 38000,
                'kraftstoff' => 'Benzin', 'getriebe' => 'Automatik',
                'leistung_kw' => 180, 'leistung_ps' => 245, 'hubraum' => 1984,
                'farbe' => 'Tornadorot', 'tueren' => 5, 'sitze' => 5,
                'karosserie' => 'Kleinwagen', 'hu' => '09/2026', 'anzahl_halter' => 1,
                'klimaanlage' => true, 'navigation' => true, 'sitzheizung' => true,
                'einparkhilfe' => true, 'tempomat' => true,
                'images' => [
                    $u.'photo-1471444928139-48c5bf5173f8'.$q,
                    $u.'photo-1503376780353-7e6692767b70'.$q,
                    $u.'photo-1494976388531-d1058494cdd8'.$q,
                ],
            ],
            [
                'marke' => 'Porsche', 'modell' => 'Macan S',
                'titel' => 'Porsche Macan S',
                'beschreibung' => $desc, 'preis' => 64900,
                'erstzulassung' => '2020-11', 'kilometerstand' => 55000,
                'kraftstoff' => 'Benzin', 'getriebe' => 'Automatik',
                'leistung_kw' => 260, 'leistung_ps' => 354, 'hubraum' => 2997,
                'farbe' => 'Vulkangrau Metallic', 'tueren' => 5, 'sitze' => 5,
                'karosserie' => 'SUV', 'hu' => '11/2025', 'anzahl_halter' => 2,
                'klimaanlage' => true, 'navigation' => true, 'sitzheizung' => true,
                'einparkhilfe' => true, 'tempomat' => true, 'ledersitze' => true,
                'schiebedach' => true, 'anhaengerkupplung' => true,
                'images' => [
                    $u.'photo-1494976388531-d1058494cdd8'.$q,
                    $u.'photo-1492144534655-ae79c964c9d7'.$q,
                    $u.'photo-1503376780353-7e6692767b70'.$q,
                ],
            ],
            [
                'marke' => 'Tesla', 'modell' => 'Model 3',
                'titel' => 'Tesla Model 3 Long Range AWD',
                'beschreibung' => $desc, 'preis' => 38900,
                'erstzulassung' => '2022-08', 'kilometerstand' => 22000,
                'kraftstoff' => 'Elektro', 'getriebe' => 'Automatik',
                'leistung_kw' => 324, 'leistung_ps' => 440,
                'farbe' => 'Pearl White Multi-Coat', 'tueren' => 4, 'sitze' => 5,
                'karosserie' => 'Limousine', 'hu' => '08/2026', 'anzahl_halter' => 1,
                'klimaanlage' => true, 'navigation' => true, 'sitzheizung' => true,
                'einparkhilfe' => true, 'tempomat' => true, 'ledersitze' => true,
                'schiebedach' => true,
                'images' => [
                    $u.'photo-1560958089-b8a1929cea89'.$q,
                    $u.'photo-1617704548623-340376564e68'.$q,
                    $u.'photo-1571987502227-9231b837d92a'.$q,
                ],
            ],
        ];

        foreach ($samples as $row) {
            $images = $row['images'];
            unset($row['images']);
            $v = Vehicle::create($row);
            foreach ($images as $i => $url) {
                VehicleImage::create([
                    'vehicle_id' => $v->id,
                    'dateiname'  => $url,
                    'sortierung' => $i,
                ]);
            }
        }
    }
}
