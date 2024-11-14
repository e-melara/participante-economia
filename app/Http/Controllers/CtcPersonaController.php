<?php

namespace App\Http\Controllers;

use App\Jobs\ValidationParticipanteJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\CtcPersona;
use App\Trait\GetDataApiTrait;
use App\Jobs\GuadarDataParticipanteJob;
use App\Http\Requests\PersonaStoreRequest;

class CtcPersonaController extends Controller
{
    use GetDataApiTrait;

    public function __construct()
    {
        $this->middleware('auth')->except('store');
    }

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
            if ($this->getValidatedDocumentoOrContacto($request->input('dui')) != 0) {
                return redirect()->back()->with('error', 'El documento que has ingresado ya existe en nuestro sistema. Verifica los datos proporcionados o contacta soporte si necesitas asistencia adicional.');
            }

            if($this->getValidatedDocumentoOrContacto($request->input('email'), 'App\Models\CtcContacto') != 0) {
                return redirect()->back()->with('error', 'El correo electrónico que has proporcionado ya se encuentra registrado en nuestro sistema. Por favor, intenta con otro correo o inicia sesión si ya tienes una cuenta.');
            }

            GuadarDataParticipanteJob::dispatch([
                'dui' => $request->input('dui'),
                'birthdate' => $request->input('birthdate'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
            ]);
            return redirect()->back()->with('success', 'Gracias por enviar tus datos. Te enviaremos un correo electrónico con los pasos siguientes para continuar.');
        } catch (\Exception $e) {
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

    public function validationPerson($data = array())
    {
      $reponseToArray = array(
        'observacion' => '',
        'valido' => false,
      );

      $edad = Carbon::parse($data['fecha_nacimiento'])->age;
      if($edad > 40) {
        $reponseToArray['observacion'] = 'La edad del participante es mayor a 40 años';
        return $reponseToArray;
      }

      if(strcmp($data['ocupacion'], 'EMPLEO')) {
        $reponseToArray['observacion'] = 'La ocupacion de la persona no es la correcta';
        return $reponseToArray;
      }

      $escolaridadValida = ['SECUNDARIA', 'MEDIA', 'SUPERIOR'];
      if(!in_array($data['nivel_escolaridad'], $escolaridadValida)) {
        $reponseToArray['observacion'] = 'El nivel de escolaridad no es el correcto';
        return $reponseToArray;
      }

      if(strcmp($data['estudiando'], 'SI')) {
        $reponseToArray['observacion'] = 'La persona esta estudiando';
        return $reponseToArray;
      }

      // calculado el ingreso percapita
      $ingresoPerCapita = $data['ingresos'] / $data['numero_personas'];
      if($ingresoPerCapita < 67.67 && $ingresoPerCapita > 270) {
        $reponseToArray['observacion'] = 'El ingreso percapita es menor a 67.67 y mayor a 270';
        return $reponseToArray;
      }

      $reponseToArray['valido'] = true;
      return $reponseToArray;
    }

    public function validar(Request $request)
    {
      $request->validate([
        'ingresos' => 'required|numeric',
        'numero_personas' => 'required|numeric',
        'estudiando' => 'required|string',
        'nivel_escolaridad' => 'required|string',
        'ocupacion' => 'required|string',
      ]);

      $persona = $request->user()->persona;
      if($persona->informacion) {
        return redirect()->back()->with('error', 'Ya has enviado tus datos para la validación.');
      }

      $dataValidation = [
        'personaId' => $persona->id,
        'email' => $request->user()->email,
        'fecha_nacimiento' => $persona->fecha_nacimiento,
        'ingresos' => $request->input('ingresos'),
        'ocupacion' => $request->input('ocupacion'),
        'estudiando' => $request->input('estudiando'),
        'numero_personas' => $request->input('numero_personas'),
        'nivel_escolaridad' => $request->input('nivel_escolaridad'),
        'participa_o_recibe' => $request->input('participa_o_recibe'),
        'persona_full' => $persona->fullName(),
      ];

      ValidationParticipanteJob::dispatch($dataValidation);
      return redirect()->back()
          ->with('success', 'Gracias por enviar su información. La hemos recibido exitosamente y nuestro equipo se comunicará con usted en breve para brindarle más detalles y los próximos pasos a seguir.');
    }
}
