<?php

namespace App\Trait;


use Carbon\Carbon;

trait ValidadoDataTrait
{
  public function validadoToPerson($data = array()) {
    $reponseToArray = array(
      'observacion' => '',
      'valido' => false,
    );

    $edad = Carbon::parse($data['fecha_nacimiento'])->age;
    if($edad > 40) {
      $reponseToArray['observacion'] = 'La edad del participante es mayor a 40 años';
      return $reponseToArray;
    }

    if(strcmp($data['ocupacion'], 'EMPLEO') == 0) {
      $reponseToArray['observacion'] = 'La ocupacion de la persona no es la correcta';
      return $reponseToArray;
    }

    $escolaridadValida = ['SECUNDARIA', 'MEDIA', 'SUPERIOR'];
    if(!in_array($data['nivel_escolaridad'], $escolaridadValida)) {
      $reponseToArray['observacion'] = 'El nivel de escolaridad no es el correcto';
      return $reponseToArray;
    }

    if(strcmp($data['estudiando'], 'SI') == 0) {
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
}
