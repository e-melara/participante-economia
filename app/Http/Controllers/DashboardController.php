<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonaResource;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
        $persona = $request->user()->persona->with([
            'estado_civil', 'genero', 
            'municipio_nacimiento' => function($query) {
                $query->with('departamento');
            }, 
            'municipio_residencia' => function($query) {
                $query->with('departamento');
            },
        ])->first();
        
        $dataToResponse = PersonaResource::make($persona);
        return Inertia::render('Dashboard', [
            'persona' => $dataToResponse
        ]);
    }
}
