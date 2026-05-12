<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:120',
            'email'       => 'required|email|max:180',
            'telefon'     => 'nullable|string|max:60',
            'nachricht'   => 'required|string|max:5000',
            'vehicle_id'  => 'nullable|exists:vehicles,id',
            'datenschutz' => 'accepted',
        ], [
            'datenschutz.accepted' => 'Bitte stimmen Sie der Datenschutzerklärung zu.',
        ]);

        ContactMessage::create([
            'vehicle_id' => $data['vehicle_id'] ?? null,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'telefon'    => $data['telefon'] ?? null,
            'nachricht'  => $data['nachricht'],
        ]);

        return back()->with('contact_success', true);
    }
}
