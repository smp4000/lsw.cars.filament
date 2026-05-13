<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'preis'             => 'decimal:2',
        'kilometerstand'    => 'integer',
        'leistung_kw'       => 'integer',
        'leistung_ps'       => 'integer',
        'hubraum'           => 'integer',
        'tueren'            => 'integer',
        'sitze'             => 'integer',
        'anzahl_halter'     => 'integer',
        'klimaanlage'       => 'boolean',
        'navigation'        => 'boolean',
        'sitzheizung'       => 'boolean',
        'einparkhilfe'      => 'boolean',
        'tempomat'          => 'boolean',
        'anhaengerkupplung' => 'boolean',
        'ledersitze'        => 'boolean',
        'schiebedach'       => 'boolean',
        'ausstattung_serie' => 'array',
        'ausstattung_sonder' => 'array',
        'verfuegbar'        => 'boolean',
        'verkauft'          => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class)->orderBy('sortierung')->orderBy('id');
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
        $out = [];

        $map = [
            'klimaanlage'       => 'Klimaanlage',
            'navigation'        => 'Navigationssystem',
            'sitzheizung'       => 'Sitzheizung',
            'einparkhilfe'      => 'Einparkhilfe',
            'tempomat'          => 'Tempomat',
            'anhaengerkupplung' => 'Anhängerkupplung',
            'ledersitze'        => 'Ledersitze',
            'schiebedach'       => 'Schiebedach',
        ];
        foreach ($map as $key => $label) {
            if ($this->{$key}) $out[] = $label;
        }

        foreach ($this->ausstattung_sonder ?? [] as $item) {
            $label = $item['description'] ?? null;
            if ($label && ! in_array($label, $out)) {
                $out[] = $label;
            }
        }

        return $out;
    }
}
