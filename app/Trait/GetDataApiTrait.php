<?php

namespace App\Trait;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

// Modelos
use App\Models\CtcMunicipio;
use App\Models\CtcEstadoCivil;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait GetDataApiTrait
{
    public function getToken()
    {
        $response = Http::post('https://api-servicios-externos.goes-dev.net/api/token/client', [
            'grant_type' => env('TYPE_CREDENTIALS_RNPN'),
            'client_id' => env('CLIENT_ID_RNPN'),
            'client_secret' => env('CLIENT_SECRET_RNPN'),
            'scope' => '',
        ]);
        return $response->json();
    }

    public function getDataRNPN($numeroDocumento, $fechaNacimiento, $tipoDocumento = 1)
    {
        $payload = [
            "document_type" => $tipoDocumento,
            "document_number" => $numeroDocumento,
            "birth_date" => $fechaNacimiento,
        ];
        $tokenAuth = $this->getToken();

        $headers = array(
            'Authorization' => 'Bearer ' . $tokenAuth['access_token'],
            'Content-Type' => 'application/json',
        );

        $response = Http::withHeaders($headers)->post("https://api-servicios-externos.goes-dev.net/api/person/info", $payload);
        return $response->json();
    }

    public function getDataFormat($resquest = array())
    {
        $genero = strcasecmp($resquest['sexo'], 'M') == 0 ? 1 : 2;
        $estadoCivil = CtcEstadoCivil::whereRaw('LOWER(nombre) = ?', [Str::lower($resquest['estaFami'])])->first();
        $muniResidencia = CtcMunicipio::whereRaw('LOWER(nombre) = ?', [Str::lower($resquest['muniDomi'])])->first();
        $muniNacimiento = CtcMunicipio::whereRaw('LOWER(nombre) = ?', [Str::lower($resquest['muniNaci'])])->first();

        // guardar la imagen y subir el avatar al servidor
        $image = $this->saveAvatarPersona($resquest);

        // segundo apellido (Por saber si sera de casa o de soltera)
        $segundoApellido = $resquest['ape2'];
        if(strcmp($resquest['apelCsda'], '') != 0) {
            $segundoApellido = $resquest['apelCsda'];
        }

        return array(
            'avatar' => $image,
            'apellidos' => $resquest['ape1']." ". $segundoApellido,
            'nombres' => $resquest['nom1']." ". $resquest['nom2'],
            'fecha_nacimiento' => Carbon::createFromFormat('d/m/Y', $resquest['fechNaci']),
            'fecha_expedicion_documento' => Carbon::createFromFormat('d/m/Y', $resquest['fechExpi']),
            'profesion' => $resquest['prof'],

            'genero_id' => $genero,
            'estado_civil_id' => $estadoCivil->id,
            'municipio_residencia_id' => empty($muniResidencia) ? 263 : $muniResidencia->id,
            'municipio_nacimiento_id' => empty($muniNacimiento) ? 263 : $muniNacimiento->id,
        );
    }


    public function saveAvatarPersona($resquest)
    {
        $documentoFormat = str_replace('-', '', $resquest['dui']);
        $contenidoImage = file_get_contents($resquest['img_url']);
        $nameFile = "{$documentoFormat}.jpg";
        Storage::disk('public')->put($nameFile, $contenidoImage);
        return $nameFile;
    }
}
