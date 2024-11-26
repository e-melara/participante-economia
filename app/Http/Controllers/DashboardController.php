<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonaPaginateResource;
use App\Http\Resources\PersonaResource;
use App\Models\CtcPersona;
use App\Models\CtcPersonaInformation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function home(Request $request) : Response
  {
    $persona = CtcPersona::with([
      'estado_civil', 'genero',
      'municipio_nacimiento' => function ($query) {
        $query->with('departamento');
      },
      'municipio_residencia' => function ($query) {
        $query->with('departamento');
      },
    ])
      ->where('id', $request->user()->persona_id)
      ->first();
    $dataToResponse = PersonaResource::make($persona);

    $haveToComplete = true;
    $dataToInformacion = CtcPersonaInformation::where('persona_id', $persona->id)
      ->select('estado', 'respuestas')
      ->first();

    if (empty($dataToInformacion)) {
      $haveToComplete = false;
    }

    return Inertia::render('Dashboard', [
      'persona' => $dataToResponse,
      'haveToComplete' => $haveToComplete,
      'dataToInformacion' => $dataToInformacion,
    ]);
  }

  public function users(): Response
  {
    return Inertia::render('Administrador/Users');
  }

  public function usersPagination(Request $request): JsonResponse
  {
    $query = User::query();
    $query->with([
      'persona' => function($query) {
        $query->with('dui');
      }
    ]);

    $query->whereHas('roles', function ($query) {
      $query->where('id', 3);
    });

    $query->when($request->input('query'), function($query, $search) {
      $query->whereHas('persona', function($query) use ($search) {
        $query->whereHas('dui', function($query) use ($search) {
          $query->where('valor', 'like', '%'.$search.'%');
        });
      });
    });

    return response()->json([
        'personas' => new PersonaPaginateResource($query->paginate(5))
      ]
    );
  }
}
