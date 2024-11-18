<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\CtcPersona;
use App\Jobs\SendEmailJob;
use App\Trait\GetDataApiTrait;
use App\Jobs\GuadarDataParticipanteJob;
use App\Jobs\ValidationParticipanteJob;
use App\Http\Requests\PersonaStoreRequest;

class CtcPersonaController extends Controller
{
    use GetDataApiTrait;

    public function __construct()
    {
        $this->middleware('auth')->except('store', 'generateToken');
    }

    private function getValidatedDocumentoOrContacto($value = '', $model = 'App\Models\CtcDocumento') {
        return DB::table('ctt_persona_contactos_documentos')
            ->where('valor', $value)
            ->where('model_type', $model)
            ->count();
    }

    public function store(PersonaStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {

            $request->validated([
              'token' => 'required|string|max:6',
              'dui' => 'required|string|max:10',
              'birthdate' => 'required|date',
              'phone' => 'required|string|max:9',
              'email' => 'required|email'
            ]);

            $validatedOtp = (new Otp())->validate(
              $request->input('email'),
              trim($request->input('token'))
            );

            if (!$validatedOtp->status) {
              return redirect()
                ->back()
                ->with('error',
                  $validatedOtp->message == 'OTP is not valid' ?
                    'El token ingresado no es válido. Por favor, verifica el token e intenta nuevamente.' :
                    'El token ingresado ha expirado. Por favor, solicita uno nuevo.'
                );
            }

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

    public function generateToken(Request $request)
    {
      $request->validate([
        'email' => 'required|email',
      ], [
        'email.required' => 'El correo electrónico es requerido.',
        'email.email' => 'El correo electrónico no es válido.',
      ]);

      $generateToken = (new Otp())->generate($request->input('email'), 'numeric', 6);
      SendEmailJob::dispatch([
        'email' => $request->input('email'),
        'asunto' => 'Token de validación',
        'template' => 'emails.token_validation_user',
        'data' => [
          'token' => $generateToken,
        ]
      ]);
    }
}
