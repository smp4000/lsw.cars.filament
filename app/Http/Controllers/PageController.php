<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function services()    { return view('pages.services'); }
    public function about()       { return view('pages.about'); }
    public function impressum()   { return view('pages.impressum'); }
    public function datenschutz() { return view('pages.datenschutz'); }
}
