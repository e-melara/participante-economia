@extends('layout')

@section('title', 'Resultado del Proceso de Selección')

@section('content')
  Estimado {{ $persona }},
  <p>
    Lamentamos informarte que, después de un análisis exhaustivo, hemos decidido
    no continuar con tu proceso de selección en esta ocasión. <br />
    Agradecemos sinceramente el tiempo y
    esfuerzo que dedicaste a tu postulación. <br />
  </p>

  <p>
    Te animamos a seguir adelante en tu búsqueda y a
    considerar otras oportunidades con nosotros en el futuro.
  </p>

  <p>¡Mucho éxito en tus próximos pasos!</p>

  <p>Atentamente,</p>
  <p>El equipo de ALDEA</p>
@endsection
