<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombres' => $this->nombres,
            'photo' => $this->photo_url(),
            'edad' => Carbon::parse($this->fecha_nacimiento)->age,
            'profesion' => $this->profesion,
            'apellidos' => $this->apellidos,
            'fecha_nacimiento' => Carbon::parse($this->fecha_nacimiento)->format('d/m/y'),
            'estado_civil' => $this->estado_civil->nombre,
            'genero' => $this->genero->nombre,
            'municipio_nacimiento' => $this->municipio_nacimiento->nombre,
            'municipio_residencia' => $this->municipio_residencia->nombre,
            'departamento_nacimiento' => $this->municipio_nacimiento->departamento->nombre,
            'departamento_residencia' => $this->municipio_residencia->departamento->nombre,
        ];
    }
}
