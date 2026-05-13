<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::saving(function (Vehicle $vehicle) {
            if (! $vehicle->slug || $vehicle->isDirty('titel')) {
                $base = Str::slug($vehicle->titel);
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->where('id', '!=', $vehicle->id ?? 0)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $vehicle->slug = $slug;
            }
        });
    }

    protected $casts = [
        'preis'                  => 'decimal:2',
        'kilometerstand'         => 'integer',
        'leistung_kw'            => 'integer',
        'leistung_ps'            => 'integer',
        'hubraum'                => 'integer',
        'zylinder'               => 'integer',
        'tankgroesse'            => 'integer',
        'energieverbrauch'       => 'decimal:1',
        'verbrauch_innerorts'    => 'decimal:1',
        'verbrauch_ausserorts'   => 'decimal:1',
        'co2_emissionen'         => 'decimal:1',
        'energiekosten'          => 'decimal:2',
        'tueren'                 => 'integer',
        'sitze'                  => 'integer',
        'anzahl_halter'          => 'integer',
        'anhaengelast_gebremst'  => 'integer',
        'anhaengelast_ungebremst'=> 'integer',
        'gewicht'                => 'integer',
        'letzte_wartung_km'      => 'integer',
        'klimaanlage'            => 'boolean',
        'navigation'             => 'boolean',
        'sitzheizung'            => 'boolean',
        'einparkhilfe'           => 'boolean',
        'tempomat'               => 'boolean',
        'anhaengerkupplung'      => 'boolean',
        'ledersitze'             => 'boolean',
        'schiebedach'            => 'boolean',
        'schiebetuer'            => 'boolean',
        'metallic'               => 'boolean',
        'nichtraucher'           => 'boolean',
        'scheckheftgepflegt'     => 'boolean',
        'verkehrstauglich'       => 'boolean',
        'unfallschaden'          => 'boolean',
        'verfuegbar'             => 'boolean',
        'verkauft'               => 'boolean',
        'unfallfrei'             => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class)->orderBy('sortierung')->orderBy('id');
    }

    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(EquipmentItem::class, 'vehicle_equipment');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ContactMessage::class);
    }

    public function firstImageUrl(): ?string
    {
        return $this->images->first()?->url;
    }

    public function getErstzulassungFormatiertAttribute(): string
    {
        if (!$this->erstzulassung) return '–';
        if (preg_match('/^(\d{4})-(\d{2})/', $this->erstzulassung, $m)) {
            return $m[2] . '/' . $m[1];
        }
        return $this->erstzulassung;
    }

    public function getPreisFormatiertAttribute(): string
    {
        return number_format((float) $this->preis, 0, ',', '.') . ' €';
    }

    public function getKmFormatiertAttribute(): string
    {
        return number_format((float) $this->kilometerstand, 0, ',', '.') . ' km';
    }

    public function ausstattungsListe(): array
    {
        $out = $this->equipment()->with('category')->get()
            ->pluck('name')
            ->toArray();

        foreach (explode("\n", $this->ausstattung_sonder ?? '') as $line) {
            $line = trim($line);
            if (! $line) continue;
            $label = preg_replace('/^\d+\s+/', '', $line);
            if ($label && ! in_array($label, $out)) {
                $out[] = $label;
            }
        }

        return $out;
    }

    public function ausstattungsListeGruppiert(): array
    {
        return $this->equipment()->with('category')->get()
            ->groupBy(fn ($item) => $item->category->name)
            ->map(fn ($items) => $items->pluck('name')->toArray())
            ->toArray();
    }
}
