@extends('layout')

@section('title', '¡Bienvenido a ALDEA!')

@section('content')
  <h1>¡Bienvenido ALDEA - Validación de Código!</h1>
  <p>
    Gracias por registrarte en nuestra plataforma. <br />
    Para completar tu proceso de validación, por favor ingresa el siguiente código de verificación en la aplicación.
  </p>
  <p class="code">{{ $token->token }}</p>
  <p>
    Este código es válido por 10 minutos. <br />
    Si no completaste la validación, puedes ignorar este mensaje.
  </p>
  <p>
    Si tienes alguna pregunta o necesitas ayuda, no dudes en ponerte en contacto con nosotros. <br />
    Gracias por unirte,<br>
    El equipo de ALDEA
  </p>
@endsection
