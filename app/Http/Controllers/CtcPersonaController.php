<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\CtcPersona;
use App\Models\CtcContacto;
use App\Models\CtcDocumento;

use App\Trait\GetDataApiTrait;
use App\Http\Requests\PersonaStoreRequest;

class CtcPersonaController extends Controller
{
    use GetDataApiTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    private function getValidatedDocumentoOrContacto($value = '', $model = 'App\Models\CtcDocumento') {
        return DB::table('ctt_persona_contactos_documentos')
            ->where('valor', $value)
            ->where('model_type', $model)
            ->count();
    }

    /**
     * Store a newly created resource in storage.
     * @param PersonaStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PersonaStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $documento = $request->input('dui');
            if ($this->getValidatedDocumentoOrContacto($documento) != 0) {
                return redirect()->back()->with('error', 'El documento que has ingresado ya existe en nuestro sistema. Verifica los datos proporcionados o contacta soporte si necesitas asistencia adicional.');
            }

            if($this->getValidatedDocumentoOrContacto($request->input('email'), 'App\Models\CtcContacto') != 0) {
                return redirect()->back()->with('error', 'El correo electrónico que has proporcionado ya se encuentra registrado en nuestro sistema. Por favor, intenta con otro correo o inicia sesión si ya tienes una cuenta.');
            }

            $carbon = Carbon::parse($request->input('birthdate'))->format('Y-m-d');
            $response = $this->getDataRNPN($documento, $carbon);
            $dataToPerson = $this->getDataFormat($response[0]);

            DB::beginTransaction();

            $persona = CtcPersona::create($dataToPerson);
            $persona->documentos_contactos()->create([
                'valor' => trim($documento),
                'model_id' => 1,
                'model_type' => CtcDocumento::class,
            ]);

            $persona->documentos_contactos()->create([
                'model_id' => 2,
                'model_type' => CtcContacto::class,
                'valor' => trim($request->input('email')),
            ]);

            $persona->documentos_contactos()->create([
                'model_id' => 1,
                'model_type' => CtcContacto::class,
                'valor' => trim($request->input('phone')),
            ]);

            // Crear usuario
            $passwordGenerated = Str::random(8);
            Log::info("Password generated: {$passwordGenerated}");
            $user = $persona->user()->create([
                'email' => $request->input('email'),
                'name' => "{$persona->nombres} {$persona->apellidos}",
                'password' => bcrypt($passwordGenerated),
            ]);

            $user->assignRole('Participante');
            DB::commit();
            return redirect()->back()->with('success', 'Gracias por enviar tus datos. Te enviaremos un correo electrónico con los pasos siguientes para continuar.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error: {$e->getMessage()}");
            return redirect()->back()->with('error', 'Por el momento tenemos problemas con el servicio, intente más tarde');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CtcPersona $ctcPersona)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CtcPersona $ctcPersona)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CtcPersona $ctcPersona)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CtcPersona $ctcPersona)
    {
        //
    }
}
