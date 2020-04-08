<?php

namespace App\Http\Controllers;

use App\ProgramEdition;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard', [
            'ended' => ProgramEdition::status('ended')->latest()->limit(10)->get(),
            'active' => ProgramEdition::status('active')->latest()->get(),
            'future' => ProgramEdition::status('future')->latest()->get(),
        ]);
    }
}
