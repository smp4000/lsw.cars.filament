<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [];

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
        $out = [];
        foreach ($map as $key => $label) {
            if ($this->{$key}) $out[] = $label;
        }
        return $out;
    }
}
