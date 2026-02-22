<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::orderBy('name')->get();
        return view('specialties.index', compact('specialties'));
    }

    public function show(Specialty $specialty)
    {
        return view('specialties.show', compact('specialty'));
    }
}
