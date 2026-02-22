<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with('specialty')->get()->makeHidden([
            'street',
            'house',
            'city',
        ]);

        return response()->json($applications);
    }

    public function show($id)
    {
        $application = Application::with('specialty')->findOrFail($id);
        $application->makeHidden([
            'street',
            'house',
            'city',
        ]);

        return response()->json($application);
    }
}
