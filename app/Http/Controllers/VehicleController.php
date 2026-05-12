<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $q = Vehicle::with('images')->where('verfuegbar', true);

        if ($request->filled('marke'))      $q->where('marke', $request->marke);
        if ($request->filled('kraftstoff')) $q->where('kraftstoff', $request->kraftstoff);
        if ($request->filled('getriebe'))   $q->where('getriebe', $request->getriebe);
        if ($request->filled('karosserie')) $q->where('karosserie', $request->karosserie);
        if ($request->filled('preis_max'))  $q->where('preis', '<=', (int) $request->preis_max);
        if ($request->filled('km_max'))     $q->where('kilometerstand', '<=', (int) $request->km_max);

        $sort = $request->input('sort', 'neu');
        match ($sort) {
            'preis_asc'  => $q->orderBy('preis'),
            'preis_desc' => $q->orderByDesc('preis'),
            'km_asc'     => $q->orderBy('kilometerstand'),
            'jahr_desc'  => $q->orderByDesc('erstzulassung'),
            default      => $q->orderByDesc('created_at'),
        };

        $vehicles = $q->get();

        $brands     = Vehicle::where('verfuegbar', true)
            ->select('marke')->distinct()->orderBy('marke')->pluck('marke');
        $bodyTypes  = Vehicle::where('verfuegbar', true)
            ->whereNotNull('karosserie')
            ->select('karosserie')->distinct()->orderBy('karosserie')->pluck('karosserie');

        return view('vehicles.index', compact('vehicles', 'brands', 'bodyTypes', 'sort'));
    }

    public function show(Vehicle $vehicle)
    {
        abort_unless($vehicle->verfuegbar, 404);

        $vehicle->load('images');

        return view('vehicles.show', compact('vehicle'));
    }
}
