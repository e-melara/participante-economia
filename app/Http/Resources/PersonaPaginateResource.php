<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
        return [
          'email' => $item->email,
          'id' => $item->persona->id,
          'status' => $item->persona->status,
          'profesion' => $item->persona->profesion,
          'full_name' => $item->persona->fullName(),
          'nombres' => $item->persona->nombres,
          'apellidos' => $item->persona->apellidos,
          'photo_url' => $item->persona->photo_url(),
          'edad' => Carbon::parse($item->persona->fecha_nacimiento)->age,
          'fecha_nacimiento' => Carbon::parse($item->persona->fecha_nacimiento)->format('d/m/Y'),
        ];
      })
    ];
  }
}
