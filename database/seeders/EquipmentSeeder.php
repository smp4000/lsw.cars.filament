<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Komfort' => [
                'Ambiente-Beleuchtung', 'Armlehne', 'Beheizbare Frontscheibe',
                'Beheizbares Lenkrad', 'Elektr. Fensterheber', 'Elektr. Heckklappe',
                'Elektr. Seitenspiegel', 'Elektrisch anklappbare Seitenspiegel',
                'Elektr. Sitzeinstellung', 'Sitzeinstellung mit Memory-Funktion',
                'Elektr. Sitzeinstellung, hinten', 'Faltdach', 'Innenspiegel autom. abblendend',
                'Lederlenkrad', 'Lordosenstütze', 'Massagesitze', 'Panorama-Dach',
                'Raucherpaket', 'Rechtlenker', 'Schiebedach', 'Schlüssellose Zentralverriegelung (Keyless)',
                'Sitzheizung', 'Sitzheizung hinten', 'Sitzbelüftung', 'Skisack',
                'Standheizung', 'Umklappbarer Beifahrersitz', 'Zentralverriegelung',
                'Berganfahrassistent', 'Bordcomputer', 'Head-Up Display',
            ],
            'Infotainment' => [
                'Multifunktionslenkrad', 'Schaltwippen', 'Servolenkung',
                'Virtuelle Seitenspiegel', 'Volldigitales Kombiinstrument',
                'Android Auto', 'Bluetooth', 'Apple CarPlay', 'CD-Spieler',
                'Multi-CD-Wechsler', 'Freisprecheinrichtung',
                'Induktionsladen für Smartphones', 'Musikstreaming integriert',
                'Navigationssystem', 'Navigationsvorbereitung',
                'Soundsystem', 'Sprachsteuerung', 'Touchscreen', 'TV', 'USB',
                'WLAN / Wifi Hotspot', 'Wärmepumpe (nur E-Autos)',
            ],
            'Tuner/Radio' => [
                'Radio DAB', 'Tuner/Radio',
            ],
            'Sicherheit' => [
                'Alarmanlage', 'ABS', 'Abstandswarner', 'Adaptives Fahrwerk',
                'Anhängerrangierassistent', 'Blendfreies Fernlicht', 'Elektr. Wegfahrsperre',
                'ESP', 'Fernlichtassistent', 'Gepäckraumabtrennung',
                'Geschwindigkeitsbegrenzer', 'Isofix', 'Isofix Beifahrersitz',
                'Lichtsensor', 'Müdigkeitswarner', 'Nachtsicht-Assistent',
                'Nebelscheinwerfer', 'Notbremsassistent', 'Notrufsystem',
                'Regensensor', 'Reifendruckkontrolle', 'Spurhalteassistent',
                'Start/Stopp-Automatik', 'Totwinkel-Assistent', 'Traktionskontrolle',
                'Verkehrszeichenerkennung',
            ],
            'Einparkhilfe' => [
                'Ausparkassistent', '360°-Kamera', 'Kamera', 'Vorne', 'Hinten',
                'Selbstlenkende Systeme',
            ],
            'Exterieur' => [
                'Abgedunkelte Scheiben', 'Allwetterreifen', 'Sommerreifen',
                'Winterreifen', 'Leichtmetallfelgen', 'Stahlfelgen', 'Dachreling',
                'Luftfederung', 'Scheinwerferreinigung', 'Winterpaket',
            ],
            'Geschwindigkeitsregulierung' => [
                'Tempomat', 'Adaptiver Tempomat',
            ],
            'Klimatisierung' => [
                'Klimaanlage', 'Klimaautomatik', '2-Zonen-Klimaautomatik',
                '3-Zonen-Klimaautomatik', '4-Zonen-Klimaautomatik',
            ],
        ];

        $catSort = 1;
        foreach ($categories as $categoryName => $items) {
            $catId = DB::table('equipment_categories')->insertGetId([
                'name'       => $categoryName,
                'sortierung' => $catSort++,
            ]);

            $itemSort = 1;
            foreach ($items as $item) {
                DB::table('equipment_items')->insert([
                    'category_id' => $catId,
                    'name'        => $item,
                    'sortierung'  => $itemSort++,
                ]);
            }
        }
    }
}
