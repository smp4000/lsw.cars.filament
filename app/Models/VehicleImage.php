<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class VehicleImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'sortierung' => 'integer',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Liefert eine URL für die Anzeige — funktioniert sowohl mit
     * externen URLs (https://…) als auch mit lokalen Storage-Pfaden.
     */
    public function getUrlAttribute(): string
    {
        if (preg_match('#^https?://#i', $this->dateiname)) {
            return $this->dateiname;
        }
        return Storage::disk('public')->url($this->dateiname);
    }
}
