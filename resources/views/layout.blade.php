<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Aquí puedes agregar tu propio CSS */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
        .container { padding: 20px; max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; }
        .header { font-size: 24px; font-weight: bold; color: #007bff; text-align: center; }
        .content { margin-top: 20px; }
        .footer { font-size: 12px; text-align: center; color: #777; margin-top: 30px; }
        .button { display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px; }
        .primary { background-color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @yield('title', 'Notificación')
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Tu Aplicación. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>