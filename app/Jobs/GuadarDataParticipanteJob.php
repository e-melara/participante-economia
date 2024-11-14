<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\CtcPersona;
use App\Models\CtcContacto;
use App\Models\CtcDocumento;
use App\Trait\GetDataApiTrait;

class GuadarDataParticipanteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GetDataApiTrait;

    public $data;
    public function __construct($data = array())
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      $phoneToPerson = trim($this->data['phone']);
      $emailToPerson = trim($this->data['email']);

      $documento = trim($this->data['dui']);
      $birthDate =  Carbon::parse(trim($this->data['birthdate']))->format('Y-m-d');

      $response = $this->getDataRNPN($documento, $birthDate);

      if (!(is_array($response) && count($response) > 0)) {
          Log::info('No se encontraron datos en el RNPN -> Documento '. $documento. ' < --- >'. $birthDate);
          exit();
      }

      $dataToPerson = $this->getDataFormat($response[0]);
      try {
        DB::beginTransaction();
        $persona = CtcPersona::create($dataToPerson);

        $persona->documentos_contactos()->create([
          'valor' => $documento,
          'model_id' => 1,
          'model_type' => CtcDocumento::class,
        ]);

        $persona->documentos_contactos()->create([
          'model_id' => 2,
          'model_type' => CtcContacto::class,
          'valor' => $emailToPerson,
        ]);

        $persona->documentos_contactos()->create([
          'model_id' => 1,
          'model_type' => CtcContacto::class,
          'valor' => $phoneToPerson,
        ]);

        $passwordGenerated = Str::random(8);
        $user = $persona->user()->create([
          'email' => $emailToPerson,
          'name' => "{$persona->nombres} {$persona->apellidos}",
          'password' => bcrypt($passwordGenerated),
        ]);

        $user->assignRole('Participante');
        SendEmailJob::dispatch([
          'email' => $emailToPerson,
          'asunto' => 'Bienvenido a la plataforma de Aldea',
          'template' => 'emails.notification_user',
          'data' => [
            'email' => $emailToPerson,
            'persona' => $persona,
            'password' => $passwordGenerated
          ]
        ]);

        DB::commit();
      } catch (\Exception $e) {
          Log::error('Error al guardar los datos del participante: '. $e->getMessage());
          DB::rollBack();
      }
    }
}
