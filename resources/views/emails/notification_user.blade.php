@extends('layout')

@section('title', '¡Bienvenido a ALDEA!')

@section('content')
  <h1>¡Bienvenido ALDEA!</h1>
  Hola {{ $persona->fullName() }},

  <p>
    Tu cuenta ha sido creada exitosamente. <br />
    A continuación, encontrarás tus credenciales de acceso: <br /> <br />
    <strong>Usuario: {{ $email }}</strong> <br>
    <strong>Contraseña: {{ $password }}</strong> <br />
  </p>
  <p>
    Recuerda cambiar tu contraseña en cuanto ingreses por primera vez para mayor seguridad. <br />
  </p>
  <a href="{{ url('/login') }}" class="primary button">
    Iniciar Sesion
  </a>
  <p>
    Si tienes alguna pregunta o necesitas ayuda, no dudes en ponerte en contacto con nosotros. <br />
    Gracias por unirte,<br>
    El equipo de ALDEA
  </p>
@endsection
