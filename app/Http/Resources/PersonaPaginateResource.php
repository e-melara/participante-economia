<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;

class PersonaPaginateResource extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @return array<int|string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'data' => $this->collection->transform(function ($item) {
        $persona = $item->persona;
        return [
          'id' => $persona->id,
          'email' => $item->email,
          'status' => $persona->status,
          'nombres' => $persona->nombres,
          'profesion' => $persona->profesion,
          'full_name' => $persona->fullName(),
          'apellidos' => $persona->apellidos,
          'photo_url' => $persona->photo_url(),
          'edad' => Carbon::parse($persona->fecha_nacimiento)->age,
          'fecha_nacimiento' => Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y'),
          'dui' => $persona->dui->valor,
        ];
      }),
      'meta' => [
        'total' => $this->total(),
        'per_page' => $this->perPage(),
        'page' => $this->currentPage(),
      ],
    ];
  }
}
