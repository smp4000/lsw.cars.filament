<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

class HomeController extends Controller
{
    public function __invoke()
    {
        $brands = Vehicle::where('verfuegbar', true)
            ->select('marke')
            ->distinct()
            ->orderBy('marke')
            ->pluck('marke');

        $featured = Vehicle::with('images')
            ->where('verfuegbar', true)
            ->where('verkauft', false)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $totalCars = Vehicle::where('verfuegbar', true)->count();

        return view('pages.home', compact('brands', 'featured', 'totalCars'));
    }
}
