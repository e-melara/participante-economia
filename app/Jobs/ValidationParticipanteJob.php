<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Trait\GetDataApiTrait;
use App\Trait\ValidadoDataTrait;
use App\Models\CtcPersonaInformation;
use App\Models\CtcPersonaContactoDocumento;

class ValidationParticipanteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ValidadoDataTrait, GetDataApiTrait;

    public array $data = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
      try {
        DB::beginTransaction();
        $validation = $this->validadoToPerson($this->data);
        $emailToPerson = $this->data['email'];
        $personIsAproved = $validation['valido'];

        Log::info(json_encode($validation));

        CtcPersonaInformation::create(
          [
            'respuestas' => $this->data,
            'persona_id' => $this->data['personaId'],
            'estado' => $personIsAproved  ? 'aprobado' : 'rechazado',
            'observacion' => $personIsAproved ? '' : $validation['observacion'],
          ]
        );

        if ($personIsAproved) {
          $personaId = $this->data['personaId'];

          $ctcPersonaDocumento = CtcPersonaContactoDocumento::where(function($query) use ($personaId) {
            $query->where('model_type', 'App\Models\CtcDocumento')
              ->where('persona_id', $personaId)
              ->where('model_id', 1);
          })->select('valor')->first();

          $payload = [
            'email' => $emailToPerson,
            'fecha_nacimiento' => $this->data['fecha_nacimiento'],
            'documento' => $ctcPersonaDocumento->valor,
          ];
          $this->getDataSICAF($payload);
        } else {
//          SendEmailJob::dispatch([
//            'email' => $emailToPerson,
//            'asunto' => 'Resolucion de la validacion',
//            'template' => 'emails.notification_user_not_aprove',
//            'data' => [
//              'persona' => $this->data['persona_full'],
//            ]
//          ]);
        }
        DB::commit();
      } catch (\Exception $e) {
        Log::error("Error: {$e->getMessage()}");
        DB::rollBack();
      }
    }
}
